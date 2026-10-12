<?php

namespace App\Services\Invitations;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Host;
use App\Models\Invitation;
use App\Models\Template;
use App\Services\InvitationQrService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TemplateFieldResolver
{
    public function __construct(
        protected InvitationQrService $invitationQrService
    ) {}

    /**
     * Resolve all template data into a unified, secure DTO/array.
     */
    public function resolve(
        Invitation $invitation,
        ?Guest $guest = null,
        ?Event $event = null,
        ?Template $template = null,
        array $overrides = [],
        bool $isPreview = false
    ): array {
        $event = $event ?? $invitation->event;
        $guest = $guest ?? $invitation->guest;
        $template = $template ?? $invitation->template;
        $host = $event?->host;

        // Ensure QR code is generated for the invitation
        if (!$isPreview && $invitation->id) {
            $invitation = $this->invitationQrService->ensureQrCode($invitation);
        }

        // Extract raw event & custom data
        $customData = $event?->custom_data ?? [];
        if (is_string($customData)) {
            $customData = json_decode($customData, true) ?: [];
        }
        $invitationCustom = $invitation->custom_data ?? [];
        if (is_string($invitationCustom)) {
            $invitationCustom = json_decode($invitationCustom, true) ?: [];
        }

        // Date and Time parsing
        $eventDate = $event?->date ? Carbon::parse($event->date) : now()->addMonths(1);
        $timeStr = $event?->time ?: '14:00';
        $eventDateTime = Carbon::parse($eventDate->format('Y-m-d') . ' ' . $timeStr);

        // Couple names resolution
        $brideName = $invitationCustom['bride_name'] ?? $customData['bride_name'] ?? $customData['bride'] ?? 'Anna';
        $groomName = $invitationCustom['groom_name'] ?? $customData['groom_name'] ?? $customData['groom'] ?? 'John';
        $coupleNames = $invitationCustom['couple_names'] ?? $customData['couple_names'] ?? ($brideName && $groomName ? "{$groomName} & {$brideName}" : ($event?->title ?: 'Celebration'));

        // Host names resolution
        $hostName = $customData['host_name'] ?? $customData['host_fullname'] ?? $host?->full_name ?? "Familia ya {$groomName}";

        // Guest info resolution
        $guestName = $guest?->full_name ?: ($isPreview ? 'Mpendwa Mgeni Wetu (Sample Guest)' : 'Mpendwa Mgeni Wetu');
        $guestCode = $guest?->invitation_code ?: ($guest?->id ? 'G-' . str_pad($guest->id, 4, '0', STR_PAD_LEFT) : 'GUEST-01');
        $guestTable = $invitationCustom['guest_table'] ?? $customData['guest_table'] ?? ($guest?->guest_type === 'vip' ? 'VIP Table 01' : 'Table 04');
        $guestType = $guest?->guest_type ?: 'regular';
        $plusOneCount = $invitation->plus_one_count ?? 0;

        // Verification & QR Info
        $verificationUrl = ($invitation->slug && \Illuminate\Support\Facades\Route::has('invitation.show'))
            ? route('invitation.show', ['slug' => $invitation->slug])
            : url('/invitation/' . ($invitation->slug ?: 'sample'));
        $pdfDownloadUrl = ($invitation->slug && \Illuminate\Support\Facades\Route::has('invitation.pdf'))
            ? route('invitation.pdf', ['slug' => $invitation->slug])
            : '#';
        $rsvpSubmitUrl = ($invitation->slug && \Illuminate\Support\Facades\Route::has('invitation.rsvp'))
            ? route('invitation.rsvp', ['slug' => $invitation->slug])
            : '#';
        $qrCodePath = $invitation->qr_code_path;
        $qrCodeUrl = $qrCodePath ? storage_url($qrCodePath) : null;

        // Gallery & Photos resolution
        $gallery = $this->resolveGalleryImages($event, $customData, $invitationCustom);
        $couplePhoto = $this->resolveCouplePhoto($event, $customData, $invitationCustom);
        $coverImage = $this->resolveCoverImage($template, $customData);

        // Venue & Maps resolution
        $venueName = $event?->venue ?: 'Golden Tulip Hotel & Conference Centre';
        $venueAddress = $event?->venue_address ?: ($customData['venue_address'] ?? 'Masaki, Dar es Salaam, Tanzania');
        $venueCity = $customData['venue_city'] ?? 'Dar es Salaam';
        $mapQuery = urlencode("{$venueName}, {$venueAddress}");
        $venueMapUrl = "https://www.google.com/maps/search/?api=1&query={$mapQuery}";
        $venueEmbedMapUrl = "https://maps.google.com/maps?q={$mapQuery}&t=&z=14&ie=UTF8&iwloc=&output=embed";

        // Swahili Date Formats
        $swahiliMonths = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Machi', 4 => 'Aprili',
            5 => 'Mei', 6 => 'Juni', 7 => 'Julai', 8 => 'Agosti',
            9 => 'Septemba', 10 => 'Oktoba', 11 => 'Novemba', 12 => 'Desemba'
        ];
        $swahiliDays = [
            'Sunday' => 'Jumapili', 'Monday' => 'Jumatatu', 'Tuesday' => 'Jumanne',
            'Wednesday' => 'Jumatano', 'Thursday' => 'Alhamisi', 'Friday' => 'Ijumaa', 'Saturday' => 'Jumamosi'
        ];
        $dayName = $swahiliDays[$eventDate->format('l')] ?? $eventDate->format('l');
        $monthName = $swahiliMonths[$eventDate->month] ?? $eventDate->format('F');
        $eventDateSwahili = "{$dayName}, {$eventDate->day} {$monthName} {$eventDate->year}";

        // WhatsApp Share Link
        $shareText = "Habari {$guestName}! Unaalikwa rasmi kwenye {$event?->title}. Bonyeza kiungo hiki kutazama kadi yako ya mualiko na QR code: {$verificationUrl}";
        $whatsappShareUrl = "https://api.whatsapp.com/send?text=" . urlencode($shareText);

        // Payment status & Access
        $isPaidTemplate = $template?->isPaid() ?? false;
        $isUnlocked = true;
        if ($isPaidTemplate && !$isPreview) {
            $invoice = $event?->invoice;
            $isUnlocked = $invoice && $invoice->isPaid();
        }

        $data = [
            // Identifiers & Meta
            'invitation_id' => $invitation->id,
            'slug' => $invitation->slug,
            'is_preview' => $isPreview,
            'is_paid' => $isPaidTemplate,
            'is_unlocked' => $isUnlocked,
            'watermark' => (!$isUnlocked && $isPaidTemplate) ? 'PREVIEW ONLY - PAYMENT REQUIRED' : null,
            'pdf_download_url' => $pdfDownloadUrl,

            // Event
            'event_title' => $event?->title ?: 'Royal Celebration & Reception',
            'event_description' => $event?->description ?: 'Tukio hili la furaha litakutanisha ndugu, jamaa na marafiki kusherehekea pamoja.',
            'event_category' => $event?->category ?: 'wedding',
            'event_date' => $eventDate->format('F d, Y'),
            'event_date_formatted' => $eventDate->format('l, d M Y'),
            'event_date_swahili' => $eventDateSwahili,
            'event_date_iso' => $eventDateTime->toIso8601String(),
            'event_time' => $timeStr,
            'event_end_time' => $customData['end_time'] ?? '23:00',

            // Couple & Host
            'bride_name' => $brideName,
            'groom_name' => $groomName,
            'couple_names' => $coupleNames,
            'host_name' => $hostName,
            'host_title' => $customData['host_title'] ?? 'Wazazi / Waandaaji',

            // Venue
            'venue_name' => $venueName,
            'venue_address' => $venueAddress,
            'venue_city' => $venueCity,
            'venue_map_url' => $venueMapUrl,
            'venue_embed_map_url' => $venueEmbedMapUrl,

            // Guest
            'guest_name' => $guestName,
            'guest_code' => $guestCode,
            'guest_table' => $guestTable,
            'guest_type' => $guestType,
            'guest_plus_one' => $plusOneCount,
            'is_personalized' => (bool) $guest,

            // RSVP
            'rsvp_status' => $invitation->rsvp_status ?: 'pending',
            'rsvp_at' => $invitation->rsvp_at?->format('M d, Y H:i'),
            'rsvp_notes' => $invitation->rsvp_notes,
            'rsvp_url' => $verificationUrl . '#rsvp',
            'rsvp_submit_url' => $rsvpSubmitUrl,
            'can_rsvp' => true,

            // QR Pass
            'qr_code_path' => $qrCodePath,
            'qr_code_url' => $qrCodeUrl,
            'qr_code_value' => $verificationUrl,
            'qr_url' => $verificationUrl,
            'verification_url' => $verificationUrl,
            'invitation_code' => $guestCode,

            // Countdown
            'countdown_target_iso' => $eventDateTime->toIso8601String(),
            'countdown_date' => $eventDate->format('Y-m-d'),

            // Media
            'couple_photo' => $couplePhoto,
            'cover_image' => $coverImage,
            'gallery' => $gallery,

            // Contact & Social
            'phone' => $customData['contact_phone'] ?? $host?->phone ?? '+255 700 000 000',
            'whatsapp' => $customData['whatsapp_phone'] ?? $host?->phone ?? '+255 700 000 000',
            'email' => $customData['contact_email'] ?? $host?->email ?? 'info@tukio360.co.tz',
            'whatsapp_share_url' => $whatsappShareUrl,
        ];

        return array_merge($data, $overrides);
    }

    /**
     * Resolve realistic demo dataset for template previews.
     */
    public function resolveSampleData(Template $template): array
    {
        $dummyInvitation = new Invitation([
            'id' => 9999,
            'slug' => 'sample-preview-' . $template->slug,
            'status' => 'pending',
            'rsvp_status' => 'pending',
            'plus_one_count' => 1,
        ]);

        $dummyEvent = new Event([
            'id' => 9999,
            'title' => 'The Royal Nuptials of Anna & John',
            'description' => 'Tunapenda kuwakaribisha kwa moyo mkunjufu kwenye sherehe yetu ya harusi. Uwepo wako utapamba na kukamilisha furaha ya siku hii adhimu.',
            'category' => $template->category ?: 'wedding',
            'date' => now()->addDays(45)->format('Y-m-d'),
            'time' => '15:00',
            'venue' => 'Serena Hotel Ballroom',
            'venue_address' => 'Ohio Street, City Centre, Dar es Salaam',
            'custom_data' => [
                'bride_name' => 'Anna Mwambenja',
                'groom_name' => 'John Kweka',
                'couple_names' => 'Anna & John',
                'host_name' => 'Familia ya Mzee J. Kweka & Bw. E. Mwambenja',
            ]
        ]);

        $dummyGuest = new Guest([
            'id' => 9999,
            'full_name' => 'Mhe. Josephat & Mama Maria Lyimo',
            'guest_type' => 'vip',
            'phone' => '+255 712 345 678',
            'invitation_code' => 'ROYAL-7890',
        ]);

        return $this->resolve(
            invitation: $dummyInvitation,
            guest: $dummyGuest,
            event: $dummyEvent,
            template: $template,
            isPreview: true
        );
    }

    /**
     * Safe placeholder replacement inside HTML template content without eval().
     */
    public function replacePlaceholdersInHtml(string $html, array $data): string
    {
        // Find all {{ placeholder }} occurrences safely
        return preg_replace_callback('/\{\{\s*([a-zA-Z0-9_\-]+)\s*\}\}/', function ($matches) use ($data) {
            $key = trim($matches[1]);
            
            if (array_key_exists($key, $data)) {
                $val = $data[$key];
                if (is_array($val)) {
                    return '';
                }
                return e((string) $val);
            }

            return '';
        }, $html);
    }

    protected function resolveCouplePhoto(?Event $event, array $customData, array $invitationCustom): ?string
    {
        $photo = $invitationCustom['couple_photo'] ?? $customData['couple_photo_main'] ?? $customData['couple_photo'] ?? $customData['photo'] ?? null;
        if ($photo) {
            if (Str::startsWith($photo, ['http://', 'https://', '/'])) {
                return $photo;
            }
            return storage_url('event_uploads/' . $photo);
        }
        return null;
    }

    protected function resolveCoverImage(?Template $template, array $customData): ?string
    {
        if (!empty($customData['cover_image'])) {
            return storage_url($customData['cover_image']);
        }
        if ($template?->preview_image) {
            return storage_url($template->preview_image);
        }
        return null;
    }

    protected function resolveGalleryImages(?Event $event, array $customData, array $invitationCustom): array
    {
        $gallery = [];
        $rawGallery = $invitationCustom['gallery'] ?? $customData['gallery'] ?? [];

        if (is_string($rawGallery)) {
            $rawGallery = json_decode($rawGallery, true) ?: [];
        }

        if (is_array($rawGallery)) {
            foreach ($rawGallery as $img) {
                if (is_string($img)) {
                    $gallery[] = Str::startsWith($img, ['http://', 'https://', '/']) ? $img : storage_url('event_uploads/' . $img);
                }
            }
        }

        return $gallery;
    }
}
