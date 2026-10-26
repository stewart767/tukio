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

        // Couple names resolution (100% dynamic from Event / Custom Data)
        $brideFullName = trim((string) ($invitationCustom['bride_name'] ?? $customData['bride_name'] ?? $customData['bride'] ?? ''));
        $groomFullName = trim((string) ($invitationCustom['groom_name'] ?? $customData['groom_name'] ?? $customData['groom'] ?? ''));
        $brideFirstName = $brideFullName !== '' ? explode(' ', $brideFullName)[0] : '';
        $groomFirstName = $groomFullName !== '' ? explode(' ', $groomFullName)[0] : '';

        $coupleNames = $invitationCustom['couple_names']
            ?? $customData['couple_names']
            ?? ($groomFirstName !== '' && $brideFirstName !== ''
                ? "{$groomFirstName} & {$brideFirstName}"
                : ($event?->title ?: 'Tukio Rasmi'));

        // Dynamic Parents formatting (automatically prefixes 'FAMILIA YA BWANA & BIBI' if user only typed parent's name)
        $rawGroomParents = trim((string) ($invitationCustom['groom_parents'] ?? $customData['groom_parents'] ?? ''));
        if ($rawGroomParents === '' && $groomFullName !== '') {
            $rawGroomParents = $groomFullName;
        }
        $groomParents = $rawGroomParents !== ''
            ? (Str::startsWith(mb_strtoupper($rawGroomParents), 'FAMILIA')
                ? mb_strtoupper($rawGroomParents)
                : 'FAMILIA YA BWANA & BIBI ' . mb_strtoupper($rawGroomParents))
            : ($host?->full_name ? 'FAMILIA YA ' . mb_strtoupper($host->full_name) : '');

        $rawBrideParents = trim((string) ($invitationCustom['bride_parents'] ?? $customData['bride_parents'] ?? ''));
        if ($rawBrideParents === '' && $brideFullName !== '') {
            $rawBrideParents = $brideFullName;
        }
        $brideParents = $rawBrideParents !== ''
            ? (Str::startsWith(mb_strtoupper($rawBrideParents), 'FAMILIA')
                ? mb_strtoupper($rawBrideParents)
                : 'FAMILIA YA BWANA & BIBI ' . mb_strtoupper($rawBrideParents))
            : '';

        $groomOrigin = $invitationCustom['groom_origin'] ?? $customData['groom_origin'] ?? null;
        $brideOrigin = $invitationCustom['bride_origin'] ?? $customData['bride_origin'] ?? null;
        $ceremonyDetails = $invitationCustom['ceremony_details'] ?? $customData['ceremony_details'] ?? null;
        $dressCode = $invitationCustom['dress_code'] ?? $customData['dress_code'] ?? null;
        $blessingVerse = $invitationCustom['blessing_verse'] ?? $customData['blessing_verse'] ?? '“Bwana ndiye aliyefanya jambo hili, nalo ni la ajabu machoni petu.” — Zaburi 118:23';

        // Host names resolution
        $hostName = $customData['host_name'] ?? $customData['host_fullname'] ?? $host?->full_name ?? $groomParents;

        // Guest info resolution (real database values)
        $guestName = $guest?->full_name ?: '';
        $guestCode = $guest?->invitation_code ?: ($guest?->id ? 'G-' . str_pad((string) $guest->id, 4, '0', STR_PAD_LEFT) : '');
        $guestTable = $guest?->table_number ?: ($invitationCustom['guest_table'] ?? $customData['guest_table'] ?? 'VIP');
        $guestType = $guest?->guest_type ?: 'single';
        $plusOneCount = (int) ($invitation->plus_one_count ?? 0);

        if ($guest && empty($guest->pin_code) && !$isPreview && $event?->id) {
            $guest->pin_code = Guest::generatePinCode($event->id);
            $guest->saveQuietly();
        }

        // Verification & QR Info
        $verificationUrl = ($invitation->slug && \Illuminate\Support\Facades\Route::has('invitation.show'))
            ? route('invitation.show', ['slug' => $invitation->slug])
            : url('/invitation/' . ($invitation->slug ?: $guestCode));
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
            'bride_name' => $brideFullName,
            'groom_name' => $groomFullName,
            'couple_names' => $coupleNames,
            'groom_parents' => $groomParents,
            'bride_parents' => $brideParents,
            'groom_origin' => $groomOrigin,
            'bride_origin' => $brideOrigin,
            'ceremony_details' => $ceremonyDetails,
            'dress_code' => $dressCode,
            'blessing_verse' => $blessingVerse,
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
            'guest_table' => $guest?->table_number ?: $guestTable,
            'table_number' => $guest?->table_number ?: $guestTable,
            'pin_code' => $guest?->pin_code ?: $invitation->sms_code,
            'pax' => $guest?->pax ?: ($plusOneCount > 0 ? $plusOneCount + 1 : 1),
            'pax_label' => ($guest?->pax ?? 1) > 1 ? 'Watu ' . ($guest?->pax ?? 2) . ' (Double Entry)' : 'Mtu 1 (Single Entry)',
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
            'phone' => $customData['contact_person_phone'] ?? $customData['contact_phone'] ?? $event?->treasurer_phone ?? $host?->phone ?? '',
            'rsvp_phone' => $customData['contact_person_phone'] ?? $customData['contact_phone'] ?? $event?->treasurer_phone ?? $host?->phone ?? '',
            'whatsapp' => $customData['whatsapp_phone'] ?? $host?->phone ?? '',
            'email' => $customData['contact_email'] ?? $host?->email ?? '',
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
            'qr_code_path' => 'qrcodes/wedding-invitation-1-Wu05dc0X.svg',
        ]);

        $dummyEvent = new Event([
            'id' => 9999,
            'title' => 'Michael & Jessica',
            'description' => 'Tunapenda kuwakaribisha kwa moyo mkunjufu kwenye sherehe yetu ya harusi. Uwepo wako utapamba na kukamilisha furaha ya siku hii adhimu.',
            'category' => $template->category ?: 'wedding',
            'date' => '2026-11-23',
            'time' => '1:00',
            'venue' => 'Serena Hotel (Diamond Ballroom), Dar es Salaam',
            'venue_address' => 'Ohio Street, City Centre, Dar es Salaam',
            'custom_data' => [
                'bride_name' => 'Jessica',
                'groom_name' => 'Michael',
                'couple_names' => 'Michael & Jessica',
                'groom_parents' => 'FAMILIA YA BWANA & BIBI JOHN KIMARO',
                'bride_parents' => 'FAMILIA YA BWANA & BIBI DAUDI MSUYA',
                'ceremony_details' => 'Kanisa la Mt. Petro, Oysterbay (Saa 9:00 Alasiri)',
                'dress_code' => 'Emerald Green, Gold & White',
                'host_name' => 'FAMILIA YA BWANA & BIBI JOHN KIMARO',
            ]
        ]);

        $dummyGuest = new Guest([
            'id' => 9999,
            'full_name' => 'MHE. JUMA MWINYI NA MWENZIO',
            'guest_type' => 'vip',
            'invitation_code' => 'ROYAL-7890',
            'pin_code' => '4821',
            'table_number' => 'VIP 1',
            'pax' => 2,
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
