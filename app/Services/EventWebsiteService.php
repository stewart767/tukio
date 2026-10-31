<?php

namespace App\Services;

use App\Models\EventWebsite;
use App\Models\EventRsvp;
use App\Models\Guest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventWebsiteService
{
    /**
     * Ensure a QR code exists for the website or an RSVP pass.
     */
    public function ensureWebsiteQrCode(EventWebsite $website): string
    {
        $relativePath = 'websites/qrcodes/' . $website->slug . '.svg';
        
        if (!Storage::disk('public')->exists($relativePath)) {
            try {
                $content = QrCode::format('svg')
                    ->size(360)
                    ->margin(2)
                    ->errorCorrection('M')
                    ->generate($website->public_url);

                Storage::disk('public')->put($relativePath, $content);
            } catch (\Throwable $e) {
                Log::warning('Failed generating website QR code: ' . $e->getMessage());
            }
        }

        return Storage::url($relativePath);
    }

    /**
     * Ensure a QR code pass exists for an RSVP confirmation.
     */
    public function ensureRsvpQrCode(EventRsvp $rsvp): string
    {
        if (!empty($rsvp->qr_code_path) && Storage::disk('public')->exists($rsvp->qr_code_path)) {
            return Storage::url($rsvp->qr_code_path);
        }

        $relativePath = 'rsvps/qrcodes/' . $rsvp->confirmation_code . '.svg';

        try {
            // Encode verification URL for staff scanner
            $verificationUrl = route('track') . '?query=' . urlencode($rsvp->confirmation_code);
            $content = QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->errorCorrection('M')
                ->generate($verificationUrl);

            Storage::disk('public')->put($relativePath, $content);
            $rsvp->update(['qr_code_path' => $relativePath]);
        } catch (\Throwable $e) {
            Log::warning('Failed generating RSVP QR pass: ' . $e->getMessage());
        }

        return Storage::url($relativePath);
    }

    /**
     * Build Google Calendar URL for event.
     */
    public function generateGoogleCalendarUrl(EventWebsite $website): string
    {
        $title = urlencode($website->title);
        $details = urlencode($website->tagline . "\n\nRSVP & Event Website: " . $website->public_url);
        $location = urlencode($website->venue_name . ', ' . $website->venue_address);

        $dateStr = $website->event_date ? $website->event_date->format('Ymd') : date('Ymd');
        $timeStr = $website->event_time ? str_replace(':', '', $website->event_time) . '00' : '180000';
        $start = $dateStr . 'T' . $timeStr;
        // Default 4 hours duration
        $end = date('Ymd\THis', strtotime($website->event_date?->format('Y-m-d') . ' ' . ($website->event_time ?: '18:00') . ' +4 hours'));

        return "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$title}&dates={$start}/{$end}&details={$details}&location={$location}";
    }

    /**
     * Build direct WhatsApp Web / App share link for a guest.
     */
    public function buildWhatsAppShareUrl(string $phone, string $message): string
    {
        $normalizedPhone = preg_replace('/[^0-9]/', '', $phone);
        $encodedMessage = urlencode($message);

        return "https://wa.me/{$normalizedPhone}?text={$encodedMessage}";
    }

    /**
     * Send personalized SMS via Beem SMS API.
     */
    public function sendSmsViaBeem(string $phone, string $message): array
    {
        $config = config('services.sms.beem', []);
        $apiKey = $config['api_key'] ?? env('BEEM_SMS_API_KEY');
        $secretKey = $config['secret_key'] ?? env('BEEM_SMS_SECRET_KEY');
        $senderId = $config['sender_id'] ?? env('BEEM_SMS_SENDER_ID', 'INFO');

        if (empty($apiKey) || empty($secretKey)) {
            Log::warning('Beem SMS credentials not configured in system settings.');
            return [
                'success' => false,
                'error' => 'Beem SMS service is not configured. Please configure in system settings.',
            ];
        }

        // Normalize phone to international format without plus (e.g. 2557XXXXXXXX)
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '255' . substr($cleanPhone, 1);
        } elseif (!str_starts_with($cleanPhone, '255') && strlen($cleanPhone) <= 10) {
            $cleanPhone = '255' . $cleanPhone;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($apiKey . ':' . $secretKey),
                'Content-Type' => 'application/json',
            ])->post('https://apisms.beem.africa/v1/send', [
                'source_addr' => $senderId,
                'schedule_time' => '',
                'encoding' => 0,
                'message' => $message,
                'recipients' => [
                    [
                        'recipient_id' => 1,
                        'dest_addr' => $cleanPhone,
                    ],
                ],
            ]);

            if ($response->successful()) {
                Log::info('Beem SMS sent successfully to ' . $cleanPhone);
                return ['success' => true, 'response' => $response->json()];
            }

            Log::error('Beem SMS send error: ' . $response->body());
            return ['success' => false, 'error' => $response->body()];
        } catch (\Throwable $e) {
            Log::error('Beem SMS exception: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

