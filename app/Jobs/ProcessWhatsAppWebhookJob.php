<?php

namespace App\Jobs;

use App\Models\EventRsvp;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\WhatsAppBusinessAccount;
use App\Models\WhatsAppContactConsent;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppPhoneNumber;
use App\Models\WhatsAppWebhookEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessWhatsAppWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    protected array $payload;
    protected ?int $webhookEventId;

    public function __construct(array $payload, ?int $webhookEventId = null)
    {
        $this->payload = $payload;
        $this->webhookEventId = $webhookEventId;
    }

    public function handle(): void
    {
        try {
            $entries = $this->payload['entry'] ?? [];

            foreach ($entries as $entry) {
                $wabaId = $entry['id'] ?? null;
                $wabaAccount = $wabaId ? WhatsAppBusinessAccount::where('waba_id', $wabaId)->first() : null;

                foreach ($entry['changes'] ?? [] as $change) {
                    $value = $change['value'] ?? [];
                    $phoneNumberId = $value['metadata']['phone_number_id'] ?? null;

                    $phoneNumber = $phoneNumberId
                        ? WhatsAppPhoneNumber::where('phone_number_id', $phoneNumberId)->first()
                        : $wabaAccount?->defaultPhoneNumber;

                    // 1. Process Message Statuses (Delivery Receipts, Read Receipts, Failures)
                    if (!empty($value['statuses'])) {
                        foreach ($value['statuses'] as $status) {
                            $this->handleStatusUpdate($status, $phoneNumber, $wabaAccount);
                        }
                    }

                    // 2. Process Inbound Messages from Guests
                    if (!empty($value['messages'])) {
                        foreach ($value['messages'] as $message) {
                            $this->handleIncomingMessage($message, $phoneNumber, $wabaAccount);
                        }
                    }
                }
            }

            if ($this->webhookEventId) {
                WhatsAppWebhookEvent::where('id', $this->webhookEventId)->update([
                    'is_processed' => true,
                    'processed_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('ProcessWhatsAppWebhookJob exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'webhook_event_id' => $this->webhookEventId,
            ]);

            if ($this->webhookEventId) {
                WhatsAppWebhookEvent::where('id', $this->webhookEventId)->update([
                    'error_message' => $e->getMessage(),
                ]);
            }

            throw $e;
        }
    }

    /**
     * Process status updates (sent, delivered, read, failed).
     */
    protected function handleStatusUpdate(array $status, ?WhatsAppPhoneNumber $phoneNumber, ?WhatsAppBusinessAccount $wabaAccount): void
    {
        $wamid = $status['id'] ?? null;
        $statusCode = strtolower($status['status'] ?? '');
        $timestamp = isset($status['timestamp']) ? now()->setTimestamp((int) $status['timestamp']) : now();

        if (empty($wamid)) {
            return;
        }

        // Find existing message log by wamid
        $message = WhatsAppMessage::where('wamid', $wamid)->first();

        if ($message) {
            $updates = [
                'status' => $statusCode,
            ];

            if ($statusCode === 'delivered' && empty($message->delivered_at)) {
                $updates['delivered_at'] = $timestamp;
            } elseif ($statusCode === 'read') {
                $updates['read_at'] = $timestamp;
                if (empty($message->delivered_at)) {
                    $updates['delivered_at'] = $timestamp;
                }
            } elseif ($statusCode === 'failed') {
                $errorData = $status['errors'][0] ?? [];
                $updates['failed_at'] = $timestamp;
                $updates['error_code'] = $errorData['code'] ?? null;
                $updates['error_message'] = $errorData['title'] ?? ($errorData['message'] ?? 'Delivery failed');
            }

            $message->update($updates);

            // Also synchronize with Invitation model if linked
            if ($message->guest_id && $message->event_id) {
                $invitation = Invitation::where('event_id', $message->event_id)
                    ->where('guest_id', $message->guest_id)
                    ->first();

                if ($invitation) {
                    $invUpdates = [];
                    if ($statusCode === 'delivered') {
                        $invUpdates['delivered_at'] = $timestamp;
                        $invUpdates['whatsapp_delivered_at'] = $timestamp;
                        $invUpdates['status'] = 'delivered';
                    } elseif ($statusCode === 'read') {
                        $invUpdates['opened_at'] = $timestamp;
                        $invUpdates['whatsapp_read_at'] = $timestamp;
                        $invUpdates['status'] = 'opened';
                    } elseif ($statusCode === 'failed') {
                        $invUpdates['status'] = 'failed';
                        $invUpdates['whatsapp_error_message'] = $updates['error_message'] ?? 'Failed';
                    }

                    if (!empty($invUpdates)) {
                        $invitation->update($invUpdates);
                    }
                }
            }
        }
    }

    /**
     * Process incoming messages from guests (responses, opt-outs, button clicks).
     */
    protected function handleIncomingMessage(array $message, ?WhatsAppPhoneNumber $phoneNumber, ?WhatsAppBusinessAccount $wabaAccount): void
    {
        $wamid = $message['id'] ?? null;
        $from = $message['from'] ?? null;
        $type = $message['type'] ?? 'text';
        $timestamp = isset($message['timestamp']) ? now()->setTimestamp((int) $message['timestamp']) : now();

        if (empty($wamid) || empty($from)) {
            return;
        }

        // Deduplication: check if already ingested
        if (WhatsAppMessage::where('wamid', $wamid)->exists()) {
            return;
        }

        $hostId = $phoneNumber?->host_id ?? $wabaAccount?->host_id;
        if (!$hostId) {
            Log::warning('Inbound WhatsApp message without identified Host/Tenant', ['from' => $from, 'wamid' => $wamid]);
            return;
        }

        // Extract message body or interactive payload
        $body = match ($type) {
            'text' => $message['text']['body'] ?? null,
            'button' => $message['button']['text'] ?? ($message['button']['payload'] ?? null),
            'interactive' => $message['interactive']['button_reply']['title']
                ?? ($message['interactive']['list_reply']['title'] ?? null),
            default => "[{$type} message]",
        };

        // Locate guest and event if phone matches
        $phoneDigits = preg_replace('/[^\d]/', '', $from);
        $guest = Guest::whereHas('event', function ($q) use ($hostId) {
            $q->where('host_id', $hostId);
        })->where(function ($q) use ($phoneDigits) {
            $q->where('phone', 'like', "%{$phoneDigits}%")
              ->orWhereRaw("REPLACE(REPLACE(phone, '+', ''), ' ', '') like ?", ["%{$phoneDigits}%"]);
        })->latest('id')->first();

        // 1. Opt-out & Opt-in management (Meta Policy)
        $cleanBody = strtoupper(trim((string) $body));
        if (in_array($cleanBody, ['STOP', 'UNSUBSCRIBE', 'CANCEL', 'OPT OUT', 'OPTOUT'])) {
            WhatsAppContactConsent::updateOrCreate(
                ['host_id' => $hostId, 'phone' => $from],
                ['status' => 'opted_out', 'opted_out_at' => now()]
            );
            Log::info("Contact opted out of WhatsApp messages: {$from} for host {$hostId}");
        } elseif (in_array($cleanBody, ['START', 'UNSTOP', 'SUBSCRIBE'])) {
            WhatsAppContactConsent::updateOrCreate(
                ['host_id' => $hostId, 'phone' => $from],
                ['status' => 'opted_in', 'opted_in_at' => now()]
            );
        }

        // 2. Interactive RSVP handling if user clicked [Attend] or [Decline]
        if ($guest) {
            if (str_contains($cleanBody, 'ACCEPT') || str_contains($cleanBody, 'ATTEND') || str_contains($cleanBody, 'NDIYO')) {
                $guest->update(['rsvp_status' => 'attending']);
                EventRsvp::where('guest_id', $guest->id)->update(['attendance_status' => 'attending']);
            } elseif (str_contains($cleanBody, 'DECLINE') || str_contains($cleanBody, 'HAPANA')) {
                $guest->update(['rsvp_status' => 'declined']);
                EventRsvp::where('guest_id', $guest->id)->update(['attendance_status' => 'declined']);
            }
        }

        // 3. Save Inbound Message Log
        WhatsAppMessage::create([
            'host_id' => $hostId,
            'event_id' => $guest?->event_id,
            'guest_id' => $guest?->id,
            'whatsapp_phone_number_id' => $phoneNumber?->id,
            'wamid' => $wamid,
            'direction' => 'inbound',
            'recipient_phone' => $phoneNumber?->display_phone_number ?? 'MyNumber',
            'sender_phone' => $from,
            'type' => $type,
            'content' => $body,
            'status' => 'delivered',
            'delivered_at' => $timestamp,
            'metadata' => $message,
        ]);
    }
}

