<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventWebsite;
use App\Models\EventWebsiteSection;
use App\Models\EventRsvp;
use App\Models\EventRsvpCustomField;
use App\Models\Guest;
use App\Services\EventWebsiteService;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventWebsiteController extends Controller
{
    public function __construct(
        protected EventWebsiteService $websiteService,
        protected WhatsAppService $whatsAppService
    ) {}

    /**
     * Get the authenticated host's active event and website.
     */
    protected function getHostEventAndWebsite(): array
    {
        $host = Auth::guard('host')->user();
        $event = $host->events()->latest()->first();

        if (!$event) {
            abort(404, 'No configured event found for your account. Please configure your event first.');
        }

        $website = $event->ensureWebsite();
        return [$host, $event, $website];
    }

    /**
     * Main Event Website & RSVP Dashboard Hub.
     */
    public function index(Request $request)
    {
        [$host, $event, $website] = $this->getHostEventAndWebsite();

        // Calculate RSVP Metrics
        $totalRsvps = $website->rsvps()->count();
        $attendingRsvps = $website->rsvps()->where('attendance_status', 'attending')->count();
        $declinedRsvps = $website->rsvps()->where('attendance_status', 'declined')->count();
        $tentativeRsvps = $website->rsvps()->where('attendance_status', 'tentative')->count();
        $checkedInCount = $website->rsvps()->where('is_checked_in', true)->count();
        
        // Total expected attendance (including plus-ones)
        $expectedAttendance = $website->rsvps()
            ->where('attendance_status', 'attending')
            ->get()
            ->sum(fn ($rsvp) => 1 + max(0, (int) $rsvp->plus_one_count));

        $stats = [
            'total_guests_allocated' => $host->number_of_guests ?: $event->guest_count,
            'total_responses' => $totalRsvps,
            'confirmed' => $attendingRsvps,
            'declined' => $declinedRsvps,
            'tentative' => $tentativeRsvps,
            'expected_attendance' => $expectedAttendance,
            'checked_in' => $checkedInCount,
            'total_sent' => $website->rsvps()->whereNotNull('invitation_sent_at')->count(),
        ];

        // Ensure QR code for the website exists
        $websiteQrCodeUrl = $this->websiteService->ensureWebsiteQrCode($website);

        // Sections ordered
        $sections = $website->sections()->orderBy('sort_order', 'asc')->get();
        $customFields = $website->customFields()->orderBy('sort_order', 'asc')->get();
        $themes = EventWebsite::getThemePresets();

        // RSVP list query with filters
        $statusFilter = $request->get('status', 'all');
        $search = $request->get('search');

        $rsvpsQuery = $website->rsvps()->latest();

        if ($statusFilter !== 'all') {
            if ($statusFilter === 'checked_in') {
                $rsvpsQuery->where('is_checked_in', true);
            } else {
                $rsvpsQuery->where('attendance_status', $statusFilter);
            }
        }

        if ($search) {
            $rsvpsQuery->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('confirmation_code', 'like', "%{$search}%");
            });
        }

        $rsvps = $rsvpsQuery->paginate(15)->withQueryString();

        return view('host.event-website.index', compact(
            'host',
            'event',
            'website',
            'stats',
            'websiteQrCodeUrl',
            'sections',
            'customFields',
            'themes',
            'rsvps',
            'statusFilter',
            'search'
        ));
    }

    /**
     * Update general website details & theme styling.
     */
    public function updateGeneral(Request $request)
    {
        [, , $website] = $this->getHostEventAndWebsite();

        $validated = $request->validate([
            'slug' => 'required|string|max:120|unique:event_websites,slug,' . $website->id,
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:80',
            'tagline' => 'nullable|string|max:500',
            'host_names' => 'nullable|string|max:255',
            'event_date' => 'nullable|date',
            'event_time' => 'nullable|string|max:50',
            'venue_name' => 'nullable|string|max:255',
            'venue_address' => 'nullable|string|max:500',
            'google_maps_url' => 'nullable|url|max:1000',
            'theme' => 'required|string|in:' . implode(',', array_keys(EventWebsite::getThemePresets())),
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'accent_color' => 'nullable|string|max:20',
            'background_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
            'font_heading' => 'nullable|string|max:80',
            'font_body' => 'nullable|string|max:80',
            'countdown_style' => 'required|string|in:cards,flip,circular,minimalist',
            'rsvp_deadline' => 'nullable|date',
            'max_plus_ones' => 'required|integer|min:0|max:10',
            'allow_guest_updates' => 'nullable|boolean',
            'require_phone' => 'nullable|boolean',
            'require_email' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['allow_guest_updates'] = $request->boolean('allow_guest_updates');
        $validated['require_phone'] = $request->boolean('require_phone');
        $validated['require_email'] = $request->boolean('require_email');
        $validated['is_published'] = $request->boolean('is_published');

        $website->update($validated);

        // Also sync event table date/venue if updated
        if ($website->event) {
            $website->event->update([
                'title' => $validated['title'],
                'date' => $validated['event_date'] ?? $website->event->date,
                'venue' => $validated['venue_name'] ?? $website->event->venue,
                'venue_address' => $validated['venue_address'] ?? $website->event->venue_address,
            ]);
        }

        return redirect()->back()->with('success', 'Event website configuration updated successfully.');
    }

    /**
     * Upload cover media or audio files.
     */
    public function updateMedia(Request $request)
    {
        [, , $website] = $this->getHostEventAndWebsite();

        $request->validate([
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'music_file' => 'nullable|mimes:mp3,wav,m4a,ogg|max:12288',
            'cover_video_url' => 'nullable|url|max:500',
            'music_url' => 'nullable|url|max:500',
            'music_title' => 'nullable|string|max:255',
        ]);

        $updates = [];

        if ($request->hasFile('cover_image')) {
            $coverFile = $request->file('cover_image');
            $coverPath = $coverFile->store('websites/covers', 'public');
            $updates['cover_image'] = $coverPath;
        }

        if ($request->hasFile('music_file')) {
            $musicFile = $request->file('music_file');
            $musicPath = $musicFile->store('websites/audio', 'public');
            $updates['music_url'] = Storage::url($musicPath);
        } elseif ($request->filled('music_url')) {
            $updates['music_url'] = $request->input('music_url');
        }

        if ($request->has('cover_video_url')) {
            $updates['cover_video_url'] = $request->input('cover_video_url');
        }

        if ($request->has('music_title')) {
            $updates['music_title'] = $request->input('music_title');
        }

        if (!empty($updates)) {
            $website->update($updates);
        }

        return redirect()->back()->with('success', 'Media assets updated successfully.');
    }

    /**
     * Update section details, visibility or JSON items.
     */
    public function updateSection(Request $request, int $id)
    {
        [, , $website] = $this->getHostEventAndWebsite();
        $section = $website->sections()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'is_enabled' => 'nullable|boolean',
            'content' => 'nullable|array',
        ]);

        $is_enabled = $request->boolean('is_enabled');
        $content = $request->input('content', $section->content ?? []);

        // Handle section image uploads if present
        if ($request->hasFile('about_image')) {
            $aboutPath = $request->file('about_image')->store('websites/about', 'public');
            $content['image'] = Storage::url($aboutPath);
        }

        if ($request->hasFile('gallery_images')) {
            $galleryList = $content['images'] ?? [];
            foreach ($request->file('gallery_images') as $img) {
                $path = $img->store('websites/gallery', 'public');
                $galleryList[] = [
                    'url' => Storage::url($path),
                    'caption' => 'Celebration moment',
                ];
            }
            $content['images'] = $galleryList;
        }

        $section->update([
            'title' => $validated['title'] ?? $section->title,
            'subtitle' => $validated['subtitle'] ?? $section->subtitle,
            'is_enabled' => $is_enabled,
            'content' => $content,
        ]);

        return redirect()->back()->with('success', "Section '{$section->section_key}' updated successfully.");
    }

    /**
     * Reorder sections.
     */
    public function reorderSections(Request $request)
    {
        [, , $website] = $this->getHostEventAndWebsite();
        $orders = $request->input('order', []);

        foreach ($orders as $sectionId => $order) {
            $website->sections()->where('id', $sectionId)->update(['sort_order' => (int) $order]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Manage custom RSVP questions.
     */
    public function storeCustomField(Request $request)
    {
        [, , $website] = $this->getHostEventAndWebsite();

        $validated = $request->validate([
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|string|in:text,textarea,select,radio,checkbox',
            'options' => 'nullable|string',
            'is_required' => 'nullable|boolean',
        ]);

        $fieldKey = Str::slug($validated['field_label'], '_') . '_' . rand(100, 999);
        $optionsArray = [];
        if (!empty($validated['options'])) {
            $optionsArray = array_values(array_filter(array_map('trim', explode("\n", $validated['options']))));
        }

        $maxOrder = $website->customFields()->max('sort_order') ?? 0;

        $website->customFields()->create([
            'field_key' => $fieldKey,
            'field_label' => $validated['field_label'],
            'field_type' => $validated['field_type'],
            'options' => $optionsArray,
            'is_required' => $request->boolean('is_required'),
            'is_enabled' => true,
            'sort_order' => $maxOrder + 1,
        ]);

        return redirect()->back()->with('success', 'Custom RSVP field created successfully.');
    }

    /**
     * Delete custom RSVP question.
     */
    public function destroyCustomField(int $id)
    {
        [, , $website] = $this->getHostEventAndWebsite();
        $website->customFields()->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Custom RSVP field removed.');
    }

    /**
     * Store manual RSVP entry by organizer.
     */
    public function storeRsvp(Request $request)
    {
        [, $event, $website] = $this->getHostEventAndWebsite();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'attendance_status' => 'required|in:attending,declined,tentative',
            'plus_one_count' => 'nullable|integer|min:0|max:10',
            'dietary_requirements' => 'nullable|string|max:80',
            'dietary_details' => 'nullable|string|max:500',
            'arrival_departure_info' => 'nullable|string|max:500',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['phone']);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '255' . substr($cleanPhone, 1);
        } elseif (!str_starts_with($cleanPhone, '255') && strlen($cleanPhone) <= 10) {
            $cleanPhone = '255' . $cleanPhone;
        }

        // Link with Guest record if exists or create new
        $guest = Guest::where('event_id', $event->id)->where('phone', $cleanPhone)->first();
        if (!$guest) {
            $uniqueCode = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
            $guest = Guest::create([
                'event_id' => $event->id,
                'full_name' => $validated['full_name'],
                'phone' => $cleanPhone,
                'guest_type' => ($validated['plus_one_count'] ?? 0) > 0 ? 'double' : 'single',
                'status' => 'active',
                'invitation_code' => $uniqueCode,
                'qr_code' => Str::random(10),
            ]);
        }

        $rsvp = EventRsvp::create([
            'event_website_id' => $website->id,
            'event_id' => $event->id,
            'guest_id' => $guest->id,
            'full_name' => $validated['full_name'],
            'phone' => $cleanPhone,
            'email' => $validated['email'] ?? null,
            'attendance_status' => $validated['attendance_status'],
            'plus_one_count' => (int) ($validated['plus_one_count'] ?? 0),
            'dietary_requirements' => $validated['dietary_requirements'] ?? null,
            'dietary_details' => $validated['dietary_details'] ?? null,
            'arrival_departure_info' => $validated['arrival_departure_info'] ?? null,
            'special_requests' => $validated['special_requests'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Generate QR code pass
        $this->websiteService->ensureRsvpQrCode($rsvp);

        return redirect()->back()->with('success', "RSVP for {$rsvp->full_name} recorded successfully.");
    }

    /**
     * Update an existing RSVP.
     */
    public function updateRsvp(Request $request, int $id)
    {
        [, , $website] = $this->getHostEventAndWebsite();
        $rsvp = $website->rsvps()->findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'attendance_status' => 'required|in:attending,declined,tentative',
            'plus_one_count' => 'nullable|integer|min:0|max:10',
            'dietary_requirements' => 'nullable|string|max:80',
            'dietary_details' => 'nullable|string|max:500',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $rsvp->update($validated);

        if ($rsvp->guest) {
            $rsvp->guest->update([
                'full_name' => $validated['full_name'],
                'guest_type' => ($validated['plus_one_count'] ?? 0) > 0 ? 'double' : 'single',
                'status' => $validated['attendance_status'] === 'attending' ? 'attended' : 'active',
            ]);
        }

        return redirect()->back()->with('success', 'RSVP details updated successfully.');
    }

    /**
     * Delete an RSVP.
     */
    public function destroyRsvp(int $id)
    {
        [, , $website] = $this->getHostEventAndWebsite();
        $rsvp = $website->rsvps()->findOrFail($id);
        $name = $rsvp->full_name;
        $rsvp->delete();

        return redirect()->back()->with('success', "RSVP for {$name} removed.");
    }

    /**
     * Toggle Check-In status on event day.
     */
    public function toggleCheckIn(Request $request, int $id)
    {
        [, , $website] = $this->getHostEventAndWebsite();
        $rsvp = $website->rsvps()->findOrFail($id);

        $newCheckedIn = !$rsvp->is_checked_in;
        $rsvp->update([
            'is_checked_in' => $newCheckedIn,
            'checked_in_at' => $newCheckedIn ? now() : null,
        ]);

        // Sync with Guest record if attached
        if ($rsvp->guest) {
            $rsvp->guest->update([
                'attended' => $newCheckedIn,
                'attended_at' => $newCheckedIn ? now() : null,
                'status' => $newCheckedIn ? 'attended' : 'active',
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_checked_in' => $newCheckedIn,
                'checked_in_at' => $rsvp->checked_in_at?->format('H:i') ?? null,
                'message' => $newCheckedIn ? "Checked in {$rsvp->full_name}" : "Check-in undone for {$rsvp->full_name}",
            ]);
        }

        return redirect()->back()->with('success', $newCheckedIn ? "Checked in {$rsvp->full_name}!" : "Check-in cancelled for {$rsvp->full_name}.");
    }

    /**
     * Export RSVPs to Excel / CSV.
     */
    public function exportExcel()
    {
        [, , $website] = $this->getHostEventAndWebsite();
        $rsvps = $website->rsvps()->orderBy('attendance_status')->orderBy('full_name')->get();

        $filename = 'RSVP_' . Str::slug($website->title) . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($rsvps) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Confirmation Code',
                'Full Name',
                'Phone',
                'Email',
                'Status',
                'Plus Ones',
                'Total Party Size',
                'Dietary Requirements',
                'Dietary Details',
                'Arrival / Travel Notes',
                'Special Wishes / Notes',
                'Checked In',
                'Check-In Time',
                'Date Submitted',
            ]);

            foreach ($rsvps as $rsvp) {
                fputcsv($file, [
                    $rsvp->confirmation_code,
                    $rsvp->full_name,
                    $rsvp->phone,
                    $rsvp->email ?: 'N/A',
                    strtoupper($rsvp->attendance_status),
                    $rsvp->plus_one_count,
                    $rsvp->total_party_size,
                    $rsvp->dietary_requirements ?: 'None',
                    $rsvp->dietary_details ?: '',
                    $rsvp->arrival_departure_info ?: '',
                    $rsvp->special_requests ?: '',
                    $rsvp->is_checked_in ? 'YES' : 'NO',
                    $rsvp->checked_in_at ? $rsvp->checked_in_at->format('Y-m-d H:i') : '',
                    $rsvp->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export RSVPs to PDF document.
     */
    public function exportPdf()
    {
        [$host, $event, $website] = $this->getHostEventAndWebsite();
        $rsvps = $website->rsvps()->orderBy('attendance_status')->orderBy('full_name')->get();

        $attending = $rsvps->where('attendance_status', 'attending');
        $declined = $rsvps->where('attendance_status', 'declined');
        $tentative = $rsvps->where('attendance_status', 'tentative');
        $totalHeads = $attending->sum(fn ($r) => 1 + (int) $r->plus_one_count);

        $pdf = Pdf::loadView('host.event-website.pdf-roster', compact(
            'host',
            'event',
            'website',
            'rsvps',
            'attending',
            'declined',
            'tentative',
            'totalHeads'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('RSVP_Roster_' . Str::slug($website->title) . '.pdf');
    }

    /**
     * Printable Guest Roster for Event Day.
     */
    public function printRoster()
    {
        [$host, $event, $website] = $this->getHostEventAndWebsite();
        $rsvps = $website->rsvps()->orderBy('full_name')->get();

        $attending = $rsvps->where('attendance_status', 'attending');
        $totalHeads = $attending->sum(fn ($r) => 1 + (int) $r->plus_one_count);

        return view('host.event-website.print-roster', compact(
            'host',
            'event',
            'website',
            'rsvps',
            'attending',
            'totalHeads'
        ));
    }

    /**
     * Broadcast & Distribution View.
     */
    public function distribution()
    {
        [$host, $event, $website] = $this->getHostEventAndWebsite();
        $guests = $event->guests()->orderBy('full_name')->get();
        $rsvps = $website->rsvps()->get()->keyBy('phone');

        $defaultMessage = "Hello {name},\n\nYou are warmly invited to celebrate {title}!\n\nPlease visit our official event website to view celebration details, schedule, location, and confirm your attendance:\n\n{url}\n\nWe look forward to celebrating with you!";

        return view('host.event-website.distribution', compact('host', 'event', 'website', 'guests', 'rsvps', 'defaultMessage'));
    }

    /**
     * Send Broadcast invitations via WhatsApp or SMS.
     */
    public function sendBroadcast(Request $request)
    {
        [, $event, $website] = $this->getHostEventAndWebsite();

        $request->validate([
            'channel' => 'required|in:whatsapp_direct,sms,whatsapp_auto',
            'recipients' => 'required|array|min:1',
            'message_template' => 'required|string|max:1000',
        ]);

        $channel = $request->input('channel');
        $recipientIds = $request->input('recipients');
        $template = $request->input('message_template');

        $guests = Guest::where('event_id', $event->id)->whereIn('id', $recipientIds)->get();
        $sentCount = 0;
        $failedCount = 0;

        foreach ($guests as $guest) {
            $personalizedMessage = str_replace(
                ['{name}', '{title}', '{url}'],
                [$guest->full_name, $website->title, $website->public_url],
                $template
            );

            if ($channel === 'sms') {
                $result = $this->websiteService->sendSmsViaBeem($guest->phone, $personalizedMessage);
                if ($result['success'] ?? false) {
                    $sentCount++;
                } else {
                    $failedCount++;
                }
            } elseif ($channel === 'whatsapp_auto') {
                // If Meta WhatsApp is configured
                if ($this->whatsAppService->isConfigured()) {
                    $result = $this->whatsAppService->sendMessage($guest->phone, $personalizedMessage);
                    if ($result['success'] ?? false) {
                        $sentCount++;
                    } else {
                        $failedCount++;
                    }
                } else {
                    $failedCount++;
                }
            }

            // Record sent timestamp on RSVP if exists
            $rsvp = EventRsvp::where('event_website_id', $website->id)
                ->where('phone', $guest->phone)
                ->first();

            if ($rsvp) {
                $rsvp->update([
                    'invitation_sent_at' => now(),
                    'invitation_channel' => $channel,
                ]);
            }
        }

        return redirect()->back()->with('success', "Broadcast processed. Sent: {$sentCount}, Failed/Requires Configuration: {$failedCount}.");
    }
}
