<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Event;
use App\Models\Invitation;
use App\Services\InvitationQrService;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function __construct(protected InvitationQrService $invitationQrService)
    {
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Super Admin can scan for ALL active events
        if ($user && $user->role === 'super_admin') {
            $assignedEvents = Event::where('status', 'active')
                ->with(['host', 'guests'])
                ->latest()
                ->get();
        } 
        // 2. Event Admin can scan for their own events
        elseif ($user->role === 'event_admin') {
            $assignedEvents = Event::where('status', 'active')
                ->where('user_id', $user->id)
                ->with(['host', 'guests'])
                ->latest()
                ->get();
        } 
        // 3. Staff can scan for assigned events (many-to-many or single assignment)
        else {
            $eventsFromManyToMany = Event::where('status', 'active')
                ->whereHas('staffMembers', function($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->with(['host', 'guests'])
                ->get();

            $eventsFromLegacy = Event::where('status', 'active')
                ->where('staff_id', $user->id)
                ->with(['host', 'guests'])
                ->get();

            $assignedEvents = $eventsFromManyToMany->merge($eventsFromLegacy)->unique('id');
        }

        // Selected active event for the scanner
        $selectedEventId = $request->input('event_id', $assignedEvents->first()?->id);
        $selectedEvent = $assignedEvents->firstWhere('id', $selectedEventId) ?? $assignedEvents->first();

        // Summary metrics for the selected event
        $stats = [
            'total_guests' => 0,
            'attended_guests' => 0,
            'total_pax' => 0,
            'checked_in_pax' => 0,
            'percent' => 0,
        ];

        if ($selectedEvent) {
            $totalGuests = $selectedEvent->guests()->count();
            $attendedGuests = $selectedEvent->guests()->where('attended', true)->count();
            $totalPax = (int) $selectedEvent->guests()->sum('pax');
            if ($totalPax < $totalGuests) {
                $totalPax = $totalGuests;
            }
            $checkedInPax = (int) $selectedEvent->guests()->sum('pax_checked_in');
            if ($checkedInPax < $attendedGuests) {
                $checkedInPax = $attendedGuests;
            }

            $stats = [
                'total_guests' => $totalGuests,
                'attended_guests' => $attendedGuests,
                'total_pax' => $totalPax,
                'checked_in_pax' => $checkedInPax,
                'percent' => $totalGuests > 0 ? round(($attendedGuests / $totalGuests) * 100) : 0,
            ];
        }

        $eventGuests = $selectedEvent ? $selectedEvent->guests()->orderBy('full_name')->get() : collect();
        $recentScans = $selectedEvent ? $selectedEvent->guests()->where('attended', true)->orderByDesc('attended_at')->take(15)->get() : collect();

        return view('staff.scanner', compact('assignedEvents', 'selectedEvent', 'stats', 'eventGuests', 'recentScans'));
    }

    public function scan(Request $request)
    {
        $request->validate([
            'qr_code' => 'nullable|string',
            'pin' => 'nullable|string',
            'code' => 'nullable|string',
            'event_id' => 'nullable|integer',
            'pax_count' => 'nullable|integer|min:1',
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Tafadhali ingia kwenye mfumo kwanza (Unauthenticated).',
            ], 401);
        }

        $scanValue = trim($request->input('code') ?: ($request->input('qr_code') ?: $request->input('pin', '')));

        if ($scanValue === '') {
            return response()->json([
                'success' => false,
                'message' => 'Tafadhali ingiza PIN ya tarakimu 4, namba ya simu, au skani QR Code.',
            ], 422);
        }

        $eventId = $request->input('event_id');
        $checkInPax = (int) ($request->input('pax_count', 1));

        $guest = null;

        // A. Check if scanValue is a URL/slug
        $scannedSlug = $this->invitationQrService->extractSlugFromScanValue($scanValue);
        if ($scannedSlug) {
            $invitation = Invitation::with(['guest.event'])->where('slug', $scannedSlug)->first();
            $guest = $invitation?->guest;
        }

        // B. Check by numeric PIN code (e.g., 4-digit PIN for kitochi)
        if (!$guest && is_numeric($scanValue) && strlen($scanValue) <= 6) {
            $pinQuery = Guest::where('pin_code', $scanValue)->with('event');
            if ($eventId) {
                $pinQuery->where('event_id', $eventId);
            }
            $guest = $pinQuery->first();
        }

        // C. Check by invitation code
        if (!$guest) {
            $codeQuery = Guest::where('invitation_code', $scanValue)->with('event');
            if ($eventId) {
                $codeQuery->where('event_id', $eventId);
            }
            $guest = $codeQuery->first();
        }

        // D. Check by phone number if numeric and 7+ digits
        if (!$guest && strlen(preg_replace('/[^0-9]/', '', $scanValue)) >= 7) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $scanValue);
            $phoneQuery = Guest::where('phone', 'like', "%{$cleanPhone}%")->with('event');
            if ($eventId) {
                $phoneQuery->where('event_id', $eventId);
            }
            $guest = $phoneQuery->latest()->first();
        }

        // E. Fallback: Check by guest name if string is 3+ characters
        if (!$guest && strlen($scanValue) >= 3) {
            $nameQuery = Guest::where('full_name', 'like', "%{$scanValue}%")->with('event');
            if ($eventId) {
                $nameQuery->where('event_id', $eventId);
            }
            $guest = $nameQuery->first();
        }

        if (!$guest) {
            return response()->json([
                'success' => false,
                'message' => 'Mwaliko haujapatikana! Hakikisha kadi, QR code au PIN ya mgeni ni sahihi.',
                'sound' => 'error'
            ], 404);
        }

        $event = $guest->event;

        // Authorization check: Super admin can scan anything; others need event access
        if ($user->role !== 'super_admin') {
            if ($user->role === 'event_admin' && $event->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Huna idhini ya kuskani wageni wa harusi hii.',
                    'sound' => 'error'
                ], 403);
            }

            if ($user->role === 'staff') {
                $isAssigned = $event->staffMembers()->where('users.id', $user->id)->exists() 
                           || $event->staff_id === $user->id;
                if (!$isAssigned) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Hujapangiwa kusimamia geti la harusi hii.',
                        'sound' => 'error'
                    ], 403);
                }
            }
        }

        // Check if already fully attended
        $totalPax = max(1, (int) ($guest->pax ?? 1));
        $currentCheckedIn = (int) ($guest->pax_checked_in ?? 0);

        if ($guest->attended && $currentCheckedIn >= $totalPax) {
            return response()->json([
                'success' => false,
                'already_checked_in' => true,
                'message' => 'ONYO: Kadi hii ILISHATUMIKA! Mgeni alishaingia ukumbini.',
                'sound' => 'warning',
                'guest' => [
                    'name' => $guest->full_name,
                    'phone' => $guest->phone,
                    'event' => $event->title,
                    'table_number' => $guest->table_number ?: 'Meza ya Kawaida',
                    'pax' => $totalPax,
                    'pax_checked_in' => $currentCheckedIn,
                    'pin_code' => $guest->pin_code,
                    'attended_at' => $guest->attended_at ? $guest->attended_at->format('d/m/Y H:i') : null,
                ]
            ], 200);
        }

        // Calculate new checked-in count
        $newCheckedIn = min($totalPax, $currentCheckedIn + max(1, $checkInPax));

        // Mark guest as attended
        $guest->update([
            'attended' => true,
            'attended_at' => $guest->attended_at ?: now(),
            'status' => 'attended',
            'pax_checked_in' => $newCheckedIn,
            'checked_in_by' => $user->id,
        ]);

        // Get updated stats for the event
        $totalGuests = $event->guests()->count();
        $attendedGuests = $event->guests()->where('attended', true)->count();
        $totalPaxSum = max($totalGuests, (int) $event->guests()->sum('pax'));
        $checkedInPaxSum = (int) $event->guests()->sum('pax_checked_in');

        return response()->json([
            'success' => true,
            'message' => "Karibu {$guest->full_name}! Uthibitisho umekamilika.",
            'sound' => 'success',
            'guest' => [
                'name' => $guest->full_name,
                'phone' => $guest->phone,
                'event' => $event->title,
                'table_number' => $guest->table_number ?: 'Meza ya Kawaida',
                'pax' => $totalPax,
                'pax_checked_in' => $newCheckedIn,
                'remaining_pax' => max(0, $totalPax - $newCheckedIn),
                'pin_code' => $guest->pin_code,
                'attended_at' => now()->format('H:i'),
            ],
            'stats' => [
                'total_guests' => $totalGuests,
                'attended_guests' => $attendedGuests,
                'total_pax' => $totalPaxSum,
                'checked_in_pax' => $checkedInPaxSum,
                'percent' => $totalGuests > 0 ? round(($attendedGuests / $totalGuests) * 100) : 0,
            ]
        ]);
    }

    public function attendance(Request $request)
    {
        $user = auth()->user();

        $query = Guest::where('attended', true)->with(['event', 'invitation'])->latest('attended_at');

        if ($user->role === 'super_admin') {
            // Can see all
            if ($request->filled('event_id')) {
                $query->where('event_id', $request->event_id);
            }
        } elseif ($user->role === 'event_admin') {
            $eventIds = Event::where('user_id', $user->id)->pluck('id');
            $query->whereIn('event_id', $eventIds);
        } else {
            $assignedEventIds = Event::whereHas('staffMembers', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                })
                ->orWhere('staff_id', $user->id)
                ->pluck('id')
                ->unique();
            $query->whereIn('event_id', $assignedEventIds);
        }

        $todayScans = $query->paginate(25);

        return view('staff.attendance', compact('todayScans'));
    }
}
