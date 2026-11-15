<?php

namespace App\Services\WhatsApp;

use App\Models\Event;
use App\Models\EventRsvp;
use App\Models\Guest;
use App\Models\Host;
use App\Models\Invitation;
use App\Models\WhatsAppContactConsent;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppPhoneNumber;
use Illuminate\Support\Facades\Log;

class WhatsAppMessagingService
{
    protected WhatsAppClient $client;

    public function __construct(WhatsAppClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send a template message using the host's connected WhatsApp Business phone number.
     */
    public function sendTemplate(
        Host $host,
        string $recipientPhone,
        string $templateName,
        array $bodyParameters = [],
        string $languageCode = 'en',
        ?Event $event = null,
        ?Guest $guest = null,
        ?string $headerMediaId = null
    ): array {
        $account = $host->whatsappAccount;
        $phoneNumber = $host->whatsappPhoneNumber;

        if (!$account || !$account->isConnected()) {
            return [
                'success' => false,
                'error' => 'No active WhatsApp Business Account connected for this organizer.',
            ];
        }

        if (!$phoneNumber) {
            return [
                'success' => false,
                'error' => 'No registered WhatsApp phone number found for this organizer.',
            ];
        }

        $formattedPhone = $this->formatPhoneNumber($recipientPhone);

        // Check if contact has explicitly opted out
        if ($this->isOptedOut($host->id, $formattedPhone)) {
            Log::info('Skipping WhatsApp message; contact has opted out', [
                'host_id' => $host->id,
                'phone' => $formattedPhone,
            ]);

            return [
                'success' => false,
                'error' => 'Contact has opted out of WhatsApp messages.',
                'opted_out' => true,
            ];
        }

        // Build Meta Components
        $components = [];

        // Optional Media Header (e.g. invitation card image or PDF)
        if (!empty($headerMediaId)) {
            $components[] = [
                'type' => 'header',
                'parameters' => [
                    [
                        'type' => 'image',
                        'image' => ['id' => $headerMediaId],
                    ],
                ],
            ];
        }

        // Body Text Parameters
        if (!empty($bodyParameters)) {
            $bodyParams = [];
            foreach ($bodyParameters as $param) {
                $bodyParams[] = [
                    'type' => 'text',
                    'text' => (string) $param,
                ];
            }
            $components[] = [
                'type' => 'body',
                'parameters' => $bodyParams,
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $formattedPhone,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $languageCode,
                ],
            ],
        ];

        if (!empty($components)) {
            $payload['template']['components'] = $components;
        }

        // Create a queued message log
        $messageLog = WhatsAppMessage::create([
            'host_id' => $host->id,
            'event_id' => $event?->id,
            'guest_id' => $guest?->id,
            'whatsapp_phone_number_id' => $phoneNumber->id,
            'direction' => 'outbound',
            'recipient_phone' => $formattedPhone,
            'sender_phone' => $phoneNumber->display_phone_number,
            'type' => 'template',
            'template_name' => $templateName,
            'content' => json_encode(['body_parameters' => $bodyParameters]),
            'status' => 'queued',
            'metadata' => ['payload' => $payload],
        ]);

        // Send to Meta Cloud API
        $endpoint = "{$phoneNumber->phone_number_id}/messages";
        $result = $this->client->post($endpoint, $payload, $account->access_token);

        if ($result['success']) {
            $wamid = $result['data']['messages'][0]['id'] ?? null;
            $messageLog->update([
                'wamid' => $wamid,
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            return [
                'success' => true,
                'message_id' => $messageLog->id,
                'wamid' => $wamid,
            ];
        }

        $errorCode = $result['code'] ?? null;
        $errorMessage = $result['error'] ?? 'Sending failed';

        $messageLog->update([
            'status' => 'failed',
            'error_code' => $errorCode,
            'error_message' => $errorMessage,
            'failed_at' => now(),
        ]);

        return [
            'success' => false,
            'error' => $errorMessage,
            'code' => $errorCode,
            'message_id' => $messageLog->id,
        ];
    }

    /**
     * Send freeform text message (Valid within 24-hr service window or replies).
     */
    public function sendTextMessage(Host $host, string $recipientPhone, string $text, ?Event $event = null, ?Guest $guest = null): array
    {
        $account = $host->whatsappAccount;
        $phoneNumber = $host->whatsappPhoneNumber;

        if (!$account || !$account->isConnected() || !$phoneNumber) {
            return ['success' => false, 'error' => 'No active WhatsApp account or phone number.'];
        }

        $formattedPhone = $this->formatPhoneNumber($recipientPhone);

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $formattedPhone,
            'type' => 'text',
            'text' => [
                'preview_url' => true,
                'body' => $text,
            ],
        ];

        $messageLog = WhatsAppMessage::create([
            'host_id' => $host->id,
            'event_id' => $event?->id,
            'guest_id' => $guest?->id,
            'whatsapp_phone_number_id' => $phoneNumber->id,
            'direction' => 'outbound',
            'recipient_phone' => $formattedPhone,
            'sender_phone' => $phoneNumber->display_phone_number,
            'type' => 'text',
            'content' => $text,
            'status' => 'queued',
        ]);

        $endpoint = "{$phoneNumber->phone_number_id}/messages";
        $result = $this->client->post($endpoint, $payload, $account->access_token);

        if ($result['success']) {
            $wamid = $result['data']['messages'][0]['id'] ?? null;
            $messageLog->update([
                'wamid' => $wamid,
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            return [
                'success' => true,
                'message_id' => $messageLog->id,
                'wamid' => $wamid,
            ];
        }

        $messageLog->update([
            'status' => 'failed',
            'error_code' => $result['code'] ?? null,
            'error_message' => $result['error'] ?? 'Sending failed',
            'failed_at' => now(),
        ]);

        return [
            'success' => false,
            'error' => $result['error'] ?? 'Sending failed',
        ];
    }

    /**
     * Send personalized digital invitation via WhatsApp.
     */
    public function sendInvitation(Host $host, Invitation $invitation): array
    {
        $guest = $invitation->guest;
        $event = $invitation->event;

        if (!$guest || !$event) {
            return ['success' => false, 'error' => 'Invitation missing associated guest or event record.'];
        }

        $website = $event->website;
        $invitationUrl = $invitation->slug
            ? route('invitation.show', $invitation->slug)
            : ($website ? $website->public_url : url('/'));

        $eventDateStr = $event->date ? $event->date->format('l, F j, Y') : 'Date to be announced';
        $venueStr = $event->venue ?: ($event->venue_address ?: 'Venue details inside');

        // Parameters matching standard template: {{1}}=Name, {{2}}=Event Title, {{3}}=Date, {{4}}=Venue, {{5}}=Link
        $parameters = [
            $guest->full_name,
            $event->title,
            $eventDateStr,
            $venueStr,
            $invitationUrl,
        ];

        $result = $this->sendTemplate(
            $host,
            $guest->phone,
            'tukio_digital_invitation',
            $parameters,
            'en',
            $event,
            $guest
        );

        if ($result['success']) {
            $invitation->update([
                'status' => 'sent',
                'sent_at' => now(),
                'whatsapp_sent_at' => now(),
                'whatsapp_message_id' => $result['wamid'] ?? null,
            ]);
        } else {
            $invitation->update([
                'status' => 'failed',
                'whatsapp_error_message' => $result['error'] ?? 'WhatsApp send failed',
            ]);
        }

        return $result;
    }

    /**
     * Send RSVP confirmation pass notification via WhatsApp.
     */
    public function sendRsvpConfirmation(Host $host, EventRsvp $rsvp): array
    {
        $event = $rsvp->event;
        $passUrl = route('track', ['query' => $rsvp->confirmation_code]);

        // Parameters matching template: {{1}}=Name, {{2}}=Event Title, {{3}}=Pass Code, {{4}}=Pass Link
        $parameters = [
            $rsvp->full_name,
            $event?->title ?? 'Celebration',
            $rsvp->confirmation_code,
            $passUrl,
        ];

        return $this->sendTemplate(
            $host,
            $rsvp->phone,
            'tukio_rsvp_confirmation',
            $parameters,
            'en',
            $event,
            $rsvp->guest
        );
    }

    /**
     * Send countdown reminder via WhatsApp.
     */
    public function sendEventReminder(Host $host, Guest $guest, Event $event, int $daysLeft): array
    {
        $website = $event->website;
        $eventDateStr = $event->date ? $event->date->format('l, F j, Y') : 'Celebration day';
        $url = $website ? $website->public_url : url('/');

        // Parameters: {{1}}=Name, {{2}}=Event Title, {{3}}=Days Left, {{4}}=Event Date, {{5}}=Link
        $parameters = [
            $guest->full_name,
            $event->title,
            (string) $daysLeft,
            $eventDateStr,
            $url,
        ];

        return $this->sendTemplate(
            $host,
            $guest->phone,
            'tukio_event_reminder',
            $parameters,
            'en',
            $event,
            $guest
        );
    }

    /**
     * Format phone number to clean international E.164 without leading plus.
     * Defaults to Tanzania (+255) if national format (e.g. 0712... or 0655...).
     */
    public function formatPhoneNumber(string $phoneNumber): string
    {
        $digits = preg_replace('/[^\d]/', '', $phoneNumber);

        if (str_starts_with($digits, '0')) {
            $digits = '255' . substr($digits, 1);
        } elseif (strlen($digits) === 9 && (str_starts_with($digits, '7') || str_starts_with($digits, '6'))) {
            $digits = '255' . $digits;
        }

        return $digits;
    }

    /**
     * Check if recipient phone number has opted out.
     */
    protected function isOptedOut(int $hostId, string $phone): bool
    {
        return WhatsAppContactConsent::where('host_id', $hostId)
            ->where('phone', $phone)
            ->where('status', 'opted_out')
            ->exists();
    }
}

