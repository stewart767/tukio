<?php

namespace App\Services\WhatsApp;

use App\Models\Host;
use App\Models\WhatsAppBusinessAccount;
use App\Models\WhatsAppPhoneNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WhatsAppAuthService
{
    protected WhatsAppClient $client;

    public function __construct(WhatsAppClient $client)
    {
        $this->client = $client;
    }

    /**
     * Exchange authorization code from Embedded Signup for a long-lived access token.
     */
    public function exchangeCodeForLongLivedToken(string $code): array
    {
        $appId = $this->client->getAppId();
        $appSecret = $this->client->getAppSecret();

        if (empty($appId) || empty($appSecret)) {
            return [
                'success' => false,
                'error' => 'Meta App ID or App Secret is not configured in services.whatsapp',
            ];
        }

        // 1. Exchange authorization code for short-lived user token
        $tokenExchangeResult = $this->client->get('oauth/access_token', [
            'client_id' => $appId,
            'client_secret' => $appSecret,
            'code' => $code,
        ]);

        if (!$tokenExchangeResult['success']) {
            return [
                'success' => false,
                'error' => 'Failed to exchange authorization code: ' . ($tokenExchangeResult['error'] ?? 'Unknown error'),
                'details' => $tokenExchangeResult,
            ];
        }

        $shortLivedToken = $tokenExchangeResult['data']['access_token'] ?? null;
        if (empty($shortLivedToken)) {
            return [
                'success' => false,
                'error' => 'No access token returned from code exchange.',
            ];
        }

        // 2. Exchange short-lived token for long-lived access token
        $longLivedResult = $this->client->get('oauth/access_token', [
            'grant_type' => 'fb_exchange_token',
            'client_id' => $appId,
            'client_secret' => $appSecret,
            'fb_exchange_token' => $shortLivedToken,
        ]);

        $finalToken = $longLivedResult['success']
            ? ($longLivedResult['data']['access_token'] ?? $shortLivedToken)
            : $shortLivedToken;

        $expiresIn = $longLivedResult['data']['expires_in'] ?? $tokenExchangeResult['data']['expires_in'] ?? null;
        $expiresAt = $expiresIn ? now()->addSeconds((int) $expiresIn) : null;

        return [
            'success' => true,
            'access_token' => $finalToken,
            'expires_at' => $expiresAt,
            'token_type' => $longLivedResult['data']['token_type'] ?? 'bearer',
        ];
    }

    /**
     * Inspect and validate token via Meta Debug Token API.
     */
    public function debugToken(string $accessToken): array
    {
        $appId = $this->client->getAppId();
        $appSecret = $this->client->getAppSecret();
        $appAccessToken = "{$appId}|{$appSecret}";

        $result = $this->client->get('debug_token', [
            'input_token' => $accessToken,
            'access_token' => $appAccessToken,
        ]);

        if (!$result['success']) {
            return $result;
        }

        $data = $result['data']['data'] ?? [];
        return [
            'success' => true,
            'is_valid' => $data['is_valid'] ?? false,
            'app_id' => $data['app_id'] ?? null,
            'scopes' => $data['scopes'] ?? [],
            'granular_scopes' => $data['granular_scopes'] ?? [],
            'expires_at' => isset($data['expires_at']) && $data['expires_at'] > 0
                ? now()->setTimestamp((int) $data['expires_at'])
                : null,
            'raw' => $data,
        ];
    }

    /**
     * Fetch WABA metadata from Meta Graph API.
     */
    public function fetchWabaDetails(string $wabaId, string $accessToken): array
    {
        return $this->client->get("{$wabaId}", [
            'fields' => 'id,name,currency,timezone_id,message_template_namespace',
        ], $accessToken);
    }

    /**
     * Fetch registered phone numbers under a WABA.
     */
    public function fetchPhoneNumbers(string $wabaId, string $accessToken): array
    {
        return $this->client->get("{$wabaId}/phone_numbers", [
            'fields' => 'id,display_phone_number,verified_name,quality_rating,code_verification_status,status',
        ], $accessToken);
    }

    /**
     * Subscribe Tukio360 application to receive webhooks for this WABA.
     */
    public function subscribeWabaApps(string $wabaId, string $accessToken): array
    {
        return $this->client->post("{$wabaId}/subscribed_apps", [], $accessToken);
    }

    /**
     * Unsubscribe application from WABA webhooks.
     */
    public function unsubscribeWabaApps(string $wabaId, string $accessToken): array
    {
        return $this->client->delete("{$wabaId}/subscribed_apps", [], $accessToken);
    }

    /**
     * End-to-end customer connection handler.
     */
    public function connectCustomerWaba(Host $host, string $code, ?string $providedWabaId = null, ?string $providedPhoneId = null): array
    {
        // 1. Exchange code for long-lived access token
        $tokenResult = $this->exchangeCodeForLongLivedToken($code);
        if (!$tokenResult['success']) {
            return $tokenResult;
        }

        $accessToken = $tokenResult['access_token'];
        $expiresAt = $tokenResult['expires_at'];

        // 2. Discover WABA ID if not explicitly provided
        $wabaId = $providedWabaId;
        if (empty($wabaId)) {
            $debug = $this->debugToken($accessToken);
            if ($debug['success']) {
                foreach ($debug['granular_scopes'] ?? [] as $scope) {
                    if (($scope['scope'] ?? '') === 'whatsapp_business_management' && !empty($scope['target_ids'])) {
                        $wabaId = $scope['target_ids'][0] ?? null;
                        break;
                    }
                }
            }
        }

        if (empty($wabaId)) {
            return [
                'success' => false,
                'error' => 'Unable to determine WhatsApp Business Account (WABA) ID from authorization response.',
            ];
        }

        // 3. Fetch WABA Metadata
        $wabaDetailsResult = $this->fetchWabaDetails($wabaId, $accessToken);
        $wabaData = $wabaDetailsResult['data'] ?? [];

        // 4. Fetch Phone Numbers
        $phoneResult = $this->fetchPhoneNumbers($wabaId, $accessToken);
        $phoneNumbersList = $phoneResult['data']['data'] ?? [];

        if (empty($phoneNumbersList)) {
            Log::warning('No phone numbers returned for connected WABA', [
                'host_id' => $host->id,
                'waba_id' => $wabaId,
            ]);
        }

        // 5. Subscribe WABA to webhooks
        $subResult = $this->subscribeWabaApps($wabaId, $accessToken);
        $isSubscribed = $subResult['success'] ?? false;

        // 6. Persist to database in a transaction
        try {
            DB::beginTransaction();

            // Mark any previous active WABAs for this host as disconnected
            WhatsAppBusinessAccount::where('host_id', $host->id)
                ->where('status', 'active')
                ->where('waba_id', '!=', $wabaId)
                ->update(['status' => 'disconnected']);

            // Create or update the WABA record
            $wabaAccount = WhatsAppBusinessAccount::updateOrCreate(
                ['waba_id' => $wabaId],
                [
                    'host_id' => $host->id,
                    'account_name' => $wabaData['name'] ?? $host->full_name . "'s WhatsApp",
                    'currency' => $wabaData['currency'] ?? 'TZS',
                    'timezone_id' => $wabaData['timezone_id'] ?? 'Africa/Dar_es_Salaam',
                    'message_template_namespace' => $wabaData['message_template_namespace'] ?? null,
                    'access_token' => $accessToken,
                    'token_type' => $tokenResult['token_type'] ?? 'bearer',
                    'token_expires_at' => $expiresAt,
                    'status' => 'active',
                    'is_webhook_subscribed' => $isSubscribed,
                    'metadata' => [
                        'connected_at' => now()->toIso8601String(),
                        'connected_by_host_id' => $host->id,
                    ],
                ]
            );

            // Sync phone numbers
            $defaultSet = false;
            foreach ($phoneNumbersList as $index => $phone) {
                $isDefault = false;
                if (!empty($providedPhoneId) && $phone['id'] === $providedPhoneId) {
                    $isDefault = true;
                    $defaultSet = true;
                } elseif (!$defaultSet && $index === 0) {
                    $isDefault = true;
                    $defaultSet = true;
                }

                WhatsAppPhoneNumber::updateOrCreate(
                    ['phone_number_id' => $phone['id']],
                    [
                        'whatsapp_business_account_id' => $wabaAccount->id,
                        'host_id' => $host->id,
                        'display_phone_number' => $phone['display_phone_number'] ?? 'Unknown',
                        'verified_name' => $phone['verified_name'] ?? null,
                        'quality_rating' => $phone['quality_rating'] ?? 'UNKNOWN',
                        'code_verification_status' => $phone['code_verification_status'] ?? 'NOT_VERIFIED',
                        'is_default' => $isDefault,
                        'status' => $phone['status'] ?? 'CONNECTED',
                        'metadata' => $phone,
                    ]
                );
            }

            DB::commit();

            Log::info('Host successfully connected WhatsApp Business Account', [
                'host_id' => $host->id,
                'waba_id' => $wabaId,
                'account_name' => $wabaAccount->account_name,
                'phone_count' => count($phoneNumbersList),
            ]);

            return [
                'success' => true,
                'account' => $wabaAccount->load('phoneNumbers'),
                'message' => 'WhatsApp Business Account successfully connected and configured.',
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to save connected WABA to database', [
                'host_id' => $host->id,
                'waba_id' => $wabaId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Database error saving WhatsApp configuration: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Disconnect customer WABA.
     */
    public function disconnectCustomerWaba(Host $host): array
    {
        $account = $host->whatsappAccount;
        if (!$account) {
            return [
                'success' => false,
                'error' => 'No active WhatsApp Business Account found to disconnect.',
            ];
        }

        try {
            // Attempt to unsubscribe from Meta webhooks
            if (!empty($account->access_token)) {
                $this->unsubscribeWabaApps($account->waba_id, $account->access_token);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to unsubscribe WABA during disconnect', [
                'waba_id' => $account->waba_id,
                'error' => $e->getMessage(),
            ]);
        }

        $account->update([
            'status' => 'disconnected',
            'is_webhook_subscribed' => false,
        ]);

        return [
            'success' => true,
            'message' => 'WhatsApp Business Account successfully disconnected.',
        ];
    }
}

