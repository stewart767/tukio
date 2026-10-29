<?php

namespace App\Services;

use App\Models\Invitation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BulkSmsService
{
    protected string $apiKey;
    protected string $secretKey;
    protected string $senderId;
    protected ?string $callbackUrl;
    protected string $endpoint;

    public function __construct(protected SystemSettingsService $settings)
    {
        $this->apiKey = (string) ($this->settings->get('sms.beem.api_key') ?: config('services.sms.beem.api_key', ''));
        $this->secretKey = (string) ($this->settings->get('sms.beem.secret_key') ?: config('services.sms.beem.secret_key', ''));
        $this->senderId = (string) ($this->settings->get('sms.beem.sender_id') ?: config('services.sms.beem.sender_id', 'TUKIO360'));
        $this->callbackUrl = $this->settings->get('sms.beem.callback_url') ?: config('services.sms.beem.callback_url');
        $this->endpoint = (string) config('services.sms.beem.endpoint', 'https://apisms.beem.africa/v1/send');
    }

    /**
     * Check if the Bulk SMS API is properly configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey)
            && !empty($this->secretKey)
            && !empty($this->senderId)
            && $this->apiKey !== 'your_beem_sms_api_key_here'
            && $this->secretKey !== 'your_beem_sms_secret_key_here';
    }

    /**
     * Get HTTP client options including CA bundle if configured/present.
     */
    protected function httpOptions(): array
    {
        $options = [];
        $caCert = config('services.sms.beem.ca_bundle')
            ?: (env('HTTP_CA_BUNDLE') ? base_path((string) env('HTTP_CA_BUNDLE')) : base_path('storage/certs/cacert.pem'));

        if (!empty($caCert) && file_exists($caCert)) {
            $options['verify'] = $caCert;
        }

        return $options;
    }

    /**
     * Normalize phone number to Tanzanian international format (255XXXXXXXXX) without '+'.
     */
    public function formatPhoneNumber(string $phoneNumber): string
    {
        $clean = preg_replace('/[^\d]/', '', $phoneNumber);
        if (str_starts_with($clean, '0')) {
            $clean = '255' . substr($clean, 1);
        } elseif (strlen($clean) === 9 && in_array(substr($clean, 0, 1), ['6', '7'], true)) {
            $clean = '255' . $clean;
        }
        return $clean;
    }

    /**
     * Send a single SMS via Beem Africa Bulk SMS API.
     */
    public function sendSingle(string $phoneNumber, string $message, int $recipientId = 1): array
    {
        return $this->sendBulk([
            [
                'recipient_id' => $recipientId,
                'dest_addr' => $this->formatPhoneNumber($phoneNumber),
            ],
        ], $message);
    }

    /**
     * Send a bulk SMS to multiple recipients with the same message text.
     *
     * @param array<int, array{recipient_id: int|string, dest_addr: string}> $recipients
     */
    public function sendBulk(array $recipients, string $message): array
    {
        if (!$this->isConfigured()) {
            Log::warning('Bulk SMS API is not configured.');
            return [
                'success' => false,
                'error' => 'Bulk SMS API credentials are not configured in Admin Settings.',
            ];
        }

        $formattedRecipients = [];
        foreach ($recipients as $index => $recipient) {
            $dest = $this->formatPhoneNumber((string) ($recipient['dest_addr'] ?? $recipient['phone'] ?? ''));
            if (strlen($dest) >= 10) {
                $formattedRecipients[] = [
                    'recipient_id' => (string) ($recipient['recipient_id'] ?? ($index + 1)),
                    'dest_addr' => $dest,
                ];
            }
        }

        if (empty($formattedRecipients)) {
            return [
                'success' => false,
                'error' => 'No valid recipient phone numbers provided.',
            ];
        }

        $payload = [
            'source_addr' => $this->senderId,
            'schedule_time' => '',
            'encoding' => 0,
            'message' => $message,
            'recipients' => $formattedRecipients,
        ];

        try {
            $response = Http::withOptions($this->httpOptions())->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->secretKey),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(25)->post($this->endpoint, $payload);

            $json = $response->json() ?? [];

            if ($response->successful() && ($json['successful'] ?? true)) {
                Log::info('Bulk SMS sent successfully', [
                    'recipient_count' => count($formattedRecipients),
                    'request_id' => $json['request_id'] ?? null,
                ]);

                return [
                    'success' => true,
                    'request_id' => $json['request_id'] ?? null,
                    'valid' => $json['valid'] ?? count($formattedRecipients),
                    'invalid' => $json['invalid'] ?? 0,
                    'response' => $json,
                ];
            }

            Log::error('Bulk SMS API returned error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $json['message'] ?? $response->body(),
            ];
        } catch (\Throwable $e) {
            Log::error('Bulk SMS exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build personalized Swahili invitation SMS containing Guest Name, Venue, Date, Gate PIN, and Digital Card URL.
     */
    public function prepareInvitationSmsText(Invitation $invitation, ?string $customMessage = null): string
    {
        $guest = $invitation->guest;
        $event = $invitation->event;

        $guestName = $guest?->full_name ?: 'Mgeni Rasmi';
        $eventTitle = $event?->title ?: 'Tukio Rasmi';
        $venue = $event?->venue ?: '';
        $dateFormatted = $event?->date ? Carbon::parse($event->date)->format('d/m/Y') : '';
        $time = $event?->time ?: '';
        $table = $guest?->table_number ?: 'VIP';
        $pin = $guest?->pin_code ?: $invitation->sms_code;
        $pax = max(1, (int) ($guest?->pax ?? 1));

        $cardUrl = $invitation->slug && \Illuminate\Support\Facades\Route::has('invitation.show')
            ? route('invitation.show', ['slug' => $invitation->slug])
            : url('/invitation/' . ($invitation->slug ?: $guest?->invitation_code));

        $intro = $customMessage ? trim($customMessage) : "Habari {$guestName}, unaalikwa kwenye {$eventTitle}.";

        $details = "Tarehe: {$dateFormatted} Saa {$time}. Ukumbi: {$venue}. Meza: {$table} (Watu: {$pax}).";
        $gatePass = $pin ? " Gate PIN: {$pin}." : '';
        $linkPart = " Kadi & QR: {$cardUrl}";

        return trim("{$intro} {$details}{$gatePass}{$linkPart}");
    }

    /**
     * Send personalized invitation SMS for a single Invitation model and update its status.
     */
    public function sendInvitationSms(Invitation $invitation, ?string $customMessage = null): array
    {
        $invitation->loadMissing(['guest', 'event']);
        $guest = $invitation->guest;

        if (!$guest || empty($guest->phone)) {
            return [
                'success' => false,
                'error' => 'Guest phone number is missing.',
            ];
        }

        $smsText = $this->prepareInvitationSmsText($invitation, $customMessage);
        $result = $this->sendSingle($guest->phone, $smsText, (int) $invitation->id);

        if ($result['success']) {
            $invitation->update([
                'status' => 'sent',
                'sent_at' => now(),
                'sms_code' => $guest->pin_code ?: $invitation->sms_code,
            ]);
        } else {
            $invitation->update([
                'status' => 'failed',
            ]);
        }

        return $result;
    }

    /**
     * Send personalized invitation SMS to a collection of Invitations.
     */
    public function sendBulkInvitationSms(iterable $invitations, ?string $customMessage = null): array
    {
        $sent = 0;
        $failed = 0;
        $errors = [];

        foreach ($invitations as $invitation) {
            $res = $this->sendInvitationSms($invitation, $customMessage);
            if ($res['success']) {
                $sent++;
            } else {
                $failed++;
                $errors[] = $res['error'] ?? 'Unknown SMS error';
            }
        }

        return [
            'success' => $sent > 0 && $failed === 0,
            'sent_count' => $sent,
            'failed_count' => $failed,
            'errors' => array_unique($errors),
        ];
    }

    /**
     * Check Beem SMS credit balance / test API credentials.
     */
    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'SMS Gateway credentials are not configured.',
            ];
        }

        try {
            $response = Http::withOptions($this->httpOptions())->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->secretKey),
                'Accept' => 'application/json',
            ])->timeout(15)->get('https://apisms.beem.africa/public/v1/vendors/balance');

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message' => 'Bulk SMS API connected successfully.',
                    'balance' => $data['data']['credit_balance'] ?? null,
                    'data' => $data,
                ];
            }

            return [
                'success' => false,
                'message' => 'Bulk SMS authentication failed: ' . $response->body(),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Bulk SMS connection error: ' . $e->getMessage(),
            ];
        }
    }
}
