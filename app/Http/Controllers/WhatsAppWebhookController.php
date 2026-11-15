<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessWhatsAppWebhookJob;
use App\Models\WhatsAppWebhookEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    /**
     * Handle WhatsApp webhook verification GET request from Meta.
     */
    public function verify(Request $request): Response
    {
        $mode = $request->query('hub_mode', $request->query('hub.mode'));
        $token = $request->query('hub_verify_token', $request->query('hub.verify_token'));
        $challenge = $request->query('hub_challenge', $request->query('hub.challenge'));

        $expectedToken = config('services.whatsapp.webhook_verify_token');

        if ($mode === 'subscribe' && !empty($expectedToken) && hash_equals($expectedToken, (string) $token)) {
            Log::info('[WhatsAppWebhook] Verification challenge passed successfully.');
            return response((string) $challenge, 200, ['Content-Type' => 'text/plain']);
        }

        Log::warning('[WhatsAppWebhook] Verification challenge failed.', [
            'mode' => $mode,
            'received_token' => $token ? substr((string) $token, 0, 4) . '***' : null,
            'expected_configured' => !empty($expectedToken),
        ]);

        return response('Forbidden: Verification failed', 403);
    }

    /**
     * Handle incoming WhatsApp webhook POST events from Meta.
     */
    public function webhook(Request $request): Response
    {
        $payload = $request->all();
        $rawContent = $request->getContent();

        // 1. Verify X-Hub-Signature-256 if app secret is configured
        if (!$this->verifySignature($request, $rawContent)) {
            Log::warning('[WhatsAppWebhook] Signature validation failed for payload.');
            return response('Unauthorized: Invalid signature', 401);
        }

        // 2. Extract metadata for audit & logging
        $wabaId = null;
        $phoneNumberId = null;
        $eventType = 'unknown';

        if (!empty($payload['entry'][0])) {
            $entry = $payload['entry'][0];
            $wabaId = $entry['id'] ?? null;
            if (!empty($entry['changes'][0]['value'])) {
                $value = $entry['changes'][0]['value'];
                $phoneNumberId = $value['metadata']['phone_number_id'] ?? null;
                if (!empty($value['statuses'])) {
                    $eventType = 'status';
                } elseif (!empty($value['messages'])) {
                    $eventType = 'message';
                } elseif (!empty($value['event'])) {
                    $eventType = $value['event'];
                }
            }
        }

        // 3. Persist raw event to database for audit and idempotency
        $webhookRecord = null;
        try {
            $webhookRecord = WhatsAppWebhookEvent::create([
                'waba_id' => $wabaId,
                'phone_number_id' => $phoneNumberId,
                'event_type' => $eventType,
                'payload' => $payload,
                'status' => 'pending',
            ]);
        } catch (\Throwable $e) {
            Log::error('[WhatsAppWebhook] Failed to save webhook record to DB: ' . $e->getMessage());
        }

        // 4. Dispatch processing asynchronously via Laravel Queue
        ProcessWhatsAppWebhookJob::dispatch($payload, $webhookRecord?->id);

        // 5. Meta requires a prompt 200 OK response within 3 seconds
        return response('EVENT_RECEIVED', 200);
    }

    /**
     * Verify HMAC SHA-256 signature from X-Hub-Signature-256 header.
     */
    protected function verifySignature(Request $request, string $rawContent): bool
    {
        $appSecret = config('services.whatsapp.app_secret');

        // If app_secret is not set in development, bypass strict check with a warning
        if (empty($appSecret)) {
            Log::debug('[WhatsAppWebhook] Skipping signature check: WHATSAPP_APP_SECRET not configured.');
            return true;
        }

        $hubSignature = $request->header('X-Hub-Signature-256');
        if (empty($hubSignature)) {
            Log::warning('[WhatsAppWebhook] Missing X-Hub-Signature-256 header.');
            return false;
        }

        $expected = 'sha256=' . hash_hmac('sha256', $rawContent, $appSecret);

        return hash_equals($expected, $hubSignature);
    }
}
