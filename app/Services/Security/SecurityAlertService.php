<?php

namespace App\Services\Security;

use App\Models\SecurityIncident;
use App\Models\SecuritySetting;
use App\Services\WhatsApp\WhatsAppClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SecurityAlertService
{
    public function __construct(
        protected WhatsAppClient $whatsappClient
    ) {}

    /**
     * Dispatch multi-channel security alerts for high/critical incidents.
     */
    public function dispatchAlert(SecurityIncident $incident): void
    {
        // Only alert on critical and high severity events
        if (!in_array($incident->severity, ['critical', 'high'], true)) {
            return;
        }

        // 1. Deduplication check: Avoid flooding admin if the same IP spams within 10 minutes
        $dedupKey = "alert:dedup:{$incident->source_ip}:{$incident->threat_type}";
        if (Cache::has($dedupKey)) {
            Log::debug("[SecurityAlert] Skipping duplicate alert for {$incident->source_ip}");
            return;
        }
        Cache::put($dedupKey, true, 600); // 10 minutes TTL

        // 2. Format human-readable alert message
        $alertMessage = $this->buildAlertText($incident);

        // 3. Dispatch WhatsApp Notification
        if (SecuritySetting::get('alert_whatsapp_enabled', true)) {
            $this->sendWhatsAppAlert($incident, $alertMessage);
        }

        // 4. Dispatch Email Notification
        if (SecuritySetting::get('alert_email_enabled', true)) {
            $this->sendEmailAlert($incident, $alertMessage);
        }
    }

    /**
     * Build clean, structured incident alert text.
     */
    protected function buildAlertText(SecurityIncident $incident): string
    {
        $severityEmoji = $incident->severity === 'critical' ? '🔴 CRITICAL' : '🟠 HIGH';
        $timestamp = now()->timezone('Africa/Dar_es_Salaam')->format('d M Y, H:i:s') . ' EAT';

        return "🚨 [TUKIO360 SOC SECURITY ALERT]\n"
            . "Severity: {$severityEmoji}\n"
            . "Incident ID: {$incident->incident_uuid}\n"
            . "Threat Type: " . strtoupper($incident->threat_type) . "\n"
            . "Detection Rule: {$incident->detected_rule}\n"
            . "Source IP: {$incident->source_ip}\n"
            . "Endpoint: {$incident->request_method} {$incident->request_url}\n"
            . "Containment Action: " . strtoupper($incident->containment_action) . "\n"
            . "Timestamp: {$timestamp}\n"
            . "Details: " . substr((string) $incident->payload_sample, 0, 150) . "\n\n"
            . "Investigate in SOC Dashboard: " . url('/admin/security');
    }

    /**
     * Send WhatsApp alert to SOC Administrator.
     */
    protected function sendWhatsAppAlert(SecurityIncident $incident, string $message): void
    {
        $recipientPhone = SecuritySetting::get('alert_whatsapp_recipient') 
            ?: config('services.whatsapp.alert_phone', env('SECURITY_ALERT_PHONE'));

        if (empty($recipientPhone)) {
            Log::debug('[SecurityAlert] WhatsApp alert skipped: No admin phone configured.');
            return;
        }

        // Clean recipient phone
        $cleanPhone = preg_replace('/[^\d]/', '', (string) $recipientPhone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '255' . substr($cleanPhone, 1);
        }

        $phoneNumberId = config('services.whatsapp.phone_number_id');
        $accessToken = config('services.whatsapp.access_token');

        if (empty($phoneNumberId) || empty($accessToken)) {
            Log::debug('[SecurityAlert] WhatsApp alert skipped: Platform credentials not set.');
            return;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $cleanPhone,
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $message,
            ],
        ];

        $endpoint = "{$phoneNumberId}/messages";
        $response = $this->whatsappClient->post($endpoint, $payload, $accessToken);

        if (!empty($response['messages'][0]['id'])) {
            Log::info("[SecurityAlert] WhatsApp alert sent successfully for incident {$incident->incident_uuid}");
        } else {
            Log::warning("[SecurityAlert] WhatsApp alert failed: " . json_encode($response));
        }
    }

    /**
     * Send Email alert to SOC Administrator.
     */
    protected function sendEmailAlert(SecurityIncident $incident, string $message): void
    {
        $recipientEmail = SecuritySetting::get('alert_email_recipient')
            ?: config('mail.from.address', env('SECURITY_ALERT_EMAIL'));

        if (empty($recipientEmail)) {
            return;
        }

        try {
            Mail::raw($message, function ($mail) use ($recipientEmail, $incident) {
                $mail->to($recipientEmail)
                    ->subject("[SOC ALERT - {$incident->severity}] Threat Detected: {$incident->detected_rule} ({$incident->incident_uuid})");
            });
        } catch (\Throwable $e) {
            Log::warning("[SecurityAlert] Email alert failed: " . $e->getMessage());
        }
    }
}

