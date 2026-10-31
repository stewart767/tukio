<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventWebsite;
use App\Models\EventRsvp;
use App\Models\Guest;
use App\Services\EventWebsiteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EventWebsitePublicController extends Controller
{
    public function __construct(
        protected EventWebsiteService $websiteService
    ) {}

    /**
     * Display the public event website.
     */
    public function show(string $slug, Request $request)
    {
        $website = EventWebsite::with(['sections' => function ($q) {
            $q->orderBy('sort_order', 'asc');
        }, 'customFields' => function ($q) {
            $q->where('is_enabled', true)->orderBy('sort_order', 'asc');
        }, 'event.host'])
            ->where('slug', $slug)
            ->first();

        if (!$website) {
            abort(404, 'Event website not found. Please verify the URL.');
        }

        $isPreview = $request->boolean('preview') || $request->has('preview') || Auth::guard('host')->check();

        if (!$website->is_published && !$isPreview) {
            abort(404, 'This event website is currently in draft mode and has not been published yet.');
        }

        $theme = $website->getActiveThemeConfig();
        $googleCalendarUrl = $this->websiteService->generateGoogleCalendarUrl($website);
        $websiteQrCodeUrl = $this->websiteService->ensureWebsiteQrCode($website);

        return view('event-websites.show', compact(
            'website',
            'theme',
            'isPreview',
            'googleCalendarUrl',
            'websiteQrCodeUrl'
        ));
    }

    /**
     * Preview route.
     */
    public function preview(string $slug, Request $request)
    {
        $request->merge(['preview' => true]);
        return $this->show($slug, $request);
    }

    /**
     * Process Guest RSVP Submission.
     */
    public function submitRsvp(Request $request, string $slug)
    {
        // 1. Anti-spam honeypot
        if ($request->filled('spambot_trap')) {
            Log::warning('RSVP bot attempt trapped for slug: ' . $slug);
            return response()->json([
                'success' => false,
                'message' => 'Your submission could not be processed.',
            ], 422);
        }

        $website = EventWebsite::with('event')->where('slug', $slug)->first();
        if (!$website) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found.',
            ], 404);
        }

        // 2. Deadline check
        if ($website->isPastDeadline()) {
            return response()->json([
                'success' => false,
                'message' => 'We are sorry, the RSVP deadline for this event has passed. Please contact the host directly for special inquiries.',
            ], 422);
        }

        // 3. Validation rules
        $rules = [
            'full_name' => 'required|string|max:255',
            'phone' => ($website->require_phone ? 'required' : 'nullable') . '|string|max:30',
            'email' => ($website->require_email ? 'required|email' : 'nullable|email') . '|max:255',
            'attendance_status' => 'required|in:attending,declined,tentative',
            'plus_one_count' => 'nullable|integer|min:0|max:' . max(0, $website->max_plus_ones),
            'accompanying_guest_names' => 'nullable|array',
            'dietary_requirements' => 'nullable|string|max:80',
            'dietary_details' => 'nullable|string|max:500',
            'arrival_departure_info' => 'nullable|string|max:500',
            'special_requests' => 'nullable|string|max:1000',
            'custom_answers' => 'nullable|array',
        ];

        // Check required custom fields
        $customFields = $website->customFields()->where('is_enabled', true)->get();
        foreach ($customFields as $field) {
            if ($field->is_required) {
                $rules['custom_answers.' . $field->field_key] = 'required';
            }
        }

        $validated = $request->validate($rules);

        // Normalize phone number to 255XXXXXXXXX format
        $cleanPhone = preg_replace('/[^0-9]/', '', (string) ($validated['phone'] ?? ''));
        if (!empty($cleanPhone)) {
            if (str_starts_with($cleanPhone, '0')) {
                $cleanPhone = '255' . substr($cleanPhone, 1);
            } elseif (!str_starts_with($cleanPhone, '255') && strlen($cleanPhone) <= 10) {
                $cleanPhone = '255' . $cleanPhone;
            }
        }

        // 4. Duplicate Check by phone
        $existingRsvp = null;
        if (!empty($cleanPhone)) {
            $existingRsvp = EventRsvp::where('event_website_id', $website->id)
                ->where('phone', $cleanPhone)
                ->first();
        }

        if ($existingRsvp && !$website->allow_guest_updates) {
            return response()->json([
                'success' => false,
                'message' => 'An RSVP has already been registered with this phone number (' . $cleanPhone . '). If you need to make changes, please contact the celebration organizer.',
            ], 422);
        }

        $isAttending = $validated['attendance_status'] === 'attending';
        $plusOnes = $isAttending ? (int) ($validated['plus_one_count'] ?? 0) : 0;

        // 5. Sync or Create Guest in Tukio360
        $guest = null;
        if (!empty($cleanPhone)) {
            $guest = Guest::where('event_id', $website->event_id)->where('phone', $cleanPhone)->first();
            if (!$guest) {
                $uniqueCode = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
                $guest = Guest::create([
                    'event_id' => $website->event_id,
                    'full_name' => $validated['full_name'],
                    'phone' => $cleanPhone,
                    'guest_type' => $plusOnes > 0 ? 'double' : 'single',
                    'status' => $isAttending ? 'attended' : 'active',
                    'invitation_code' => $uniqueCode,
                    'qr_code' => Str::random(10),
                ]);
            } else {
                $guest->update([
                    'full_name' => $validated['full_name'],
                    'guest_type' => $plusOnes > 0 ? 'double' : 'single',
                ]);
            }
        }

        // 6. Record or update RSVP
        if ($existingRsvp) {
            $existingRsvp->update([
                'full_name' => $validated['full_name'],
                'email' => $validated['email'] ?? $existingRsvp->email,
                'attendance_status' => $validated['attendance_status'],
                'plus_one_count' => $plusOnes,
                'accompanying_guest_names' => $validated['accompanying_guest_names'] ?? null,
                'dietary_requirements' => $validated['dietary_requirements'] ?? null,
                'dietary_details' => $validated['dietary_details'] ?? null,
                'arrival_departure_info' => $validated['arrival_departure_info'] ?? null,
                'special_requests' => $validated['special_requests'] ?? null,
                'custom_answers' => $validated['custom_answers'] ?? null,
                'guest_id' => $guest?->id ?? $existingRsvp->guest_id,
            ]);
            $rsvp = $existingRsvp;
            $updated = true;
        } else {
            $rsvp = EventRsvp::create([
                'event_website_id' => $website->id,
                'event_id' => $website->event_id,
                'guest_id' => $guest?->id,
                'full_name' => $validated['full_name'],
                'phone' => $cleanPhone,
                'email' => $validated['email'] ?? null,
                'attendance_status' => $validated['attendance_status'],
                'plus_one_count' => $plusOnes,
                'accompanying_guest_names' => $validated['accompanying_guest_names'] ?? null,
                'dietary_requirements' => $validated['dietary_requirements'] ?? null,
                'dietary_details' => $validated['dietary_details'] ?? null,
                'arrival_departure_info' => $validated['arrival_departure_info'] ?? null,
                'special_requests' => $validated['special_requests'] ?? null,
                'custom_answers' => $validated['custom_answers'] ?? null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            $updated = false;
        }

        // Generate QR code pass
        $qrCodeUrl = $this->websiteService->ensureRsvpQrCode($rsvp);
        $calendarUrl = $this->websiteService->generateGoogleCalendarUrl($website);

        // Friendly confirmation message
        $celebrationTitle = $website->title;
        $message = match ($rsvp->attendance_status) {
            'attending' => "Asante sana, {$rsvp->full_name}! Your attendance at {$celebrationTitle} has been confirmed. We look forward to celebrating together!",
            'declined' => "Thank you for letting us know, {$rsvp->full_name}. We will miss you at {$celebrationTitle} and appreciate your warm thoughts!",
            default => "Thank you, {$rsvp->full_name}! Your response has been recorded. Please feel free to update your status before the RSVP deadline."
        };

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_updated' => $updated,
            'rsvp' => [
                'confirmation_code' => $rsvp->confirmation_code,
                'full_name' => $rsvp->full_name,
                'phone' => $rsvp->phone,
                'attendance_status' => $rsvp->attendance_status,
                'plus_one_count' => $rsvp->plus_one_count,
                'total_party_size' => $rsvp->total_party_size,
                'dietary_requirements' => $rsvp->dietary_requirements,
                'qr_code_url' => $qrCodeUrl,
                'calendar_url' => $calendarUrl,
                'event_title' => $website->title,
                'event_date' => $website->event_date ? $website->event_date->format('l, F j, Y') : null,
                'event_time' => $website->event_time,
                'venue_name' => $website->venue_name,
                'venue_address' => $website->venue_address,
            ],
        ]);
    }
}

