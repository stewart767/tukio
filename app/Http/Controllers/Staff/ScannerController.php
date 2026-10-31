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

    public function index()
    {
        $staff = auth()->user();
        
        // Get events assigned via many-to-many relationship
        $eventsFromManyToMany = Event::where('status', 'active')
            ->whereHas('staffMembers', function($query) use ($staff) {
                $query->where('users.id', $staff->id);
            })
            ->with('host')
            ->get();
        
        // Get events assigned via legacy single assignment
        $eventsFromLegacy = Event::where('status', 'active')
            ->where('staff_id', $staff->id)
            ->with('host')
            ->get();
        
        // Merge and remove duplicates
        $assignedEvents = $eventsFromManyToMany->merge($eventsFromLegacy)->unique('id');
        
        return view('staff.scanner', compact('assignedEvents'));
    }

    public function scan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $staff = auth()->user();
        
        $scanValue = trim($request->qr_code);
        $scannedSlug = $this->invitationQrService->extractSlugFromScanValue($scanValue);

        $invitation = null;
        if ($scannedSlug) {
            $invitation = Invitation::with(['guest.event'])->where('slug', $scannedSlug)->first();
        }

        $guest = $invitation?->guest;
        if (!$guest) {
            $guest = Guest::where('invitation_code', $scanValue)
                ->with('event')
                ->first();
        }

        if (!$guest) {
            $rsvp = \App\Models\EventRsvp::with(['guest.event', 'event'])->where('confirmation_code', $scanValue)->first();
            if ($rsvp) {
                $guest = $rsvp->guest;
            }
        } else {
            $guest->loadMissing('event');
        }

        if (!$guest) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid invitation code. Guest not found.'
            ], 404);
        }

        // Check if staff is assigned to this event (many-to-many or legacy single assignment)
        $isAssigned = $guest->event->staffMembers()->where('users.id', $staff->id)->exists() 
                   || $guest->event->staff_id === $staff->id;
        
        if (!$isAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'You are not assigned to this event. Only assigned staff can mark attendance.'
            ], 403);
        }

        if ($guest->attended) {
            return response()->json([
                'success' => false,
                'message' => 'Guest has already been checked in.',
                'guest' => [
                    'name' => $guest->full_name,
                    'event' => $guest->event->title,
                    'attended_at' => $guest->attended_at->format('M d, Y H:i')
                ]
            ], 400);
        }

        // Mark guest as attended
        $guest->update([
            'attended' => true,
            'attended_at' => now(),
            'status' => 'attended'
        ]);

        if ($guest->rsvp) {
            $guest->rsvp->update([
                'is_checked_in' => true,
                'checked_in_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Guest checked in successfully!',
            'guest' => [
                'name' => $guest->full_name,
                'event' => $guest->event->title,
                'attended_at' => $guest->attended_at->format('M d, Y H:i')
            ]
        ]);
    }

    public function attendance()
    {
        $staff = auth()->user();
        
        // Get event IDs where staff is assigned (many-to-many or legacy)
        $assignedEventIds = Event::whereHas('staffMembers', function($query) use ($staff) {
                $query->where('users.id', $staff->id);
            })
            ->orWhere('staff_id', $staff->id)
            ->pluck('id')
            ->unique();
        
        // Only show attendance for events assigned to this staff member
        $todayScans = Guest::where('attended', true)
            ->whereDate('attended_at', today())
            ->whereIn('event_id', $assignedEventIds)
            ->with('event')
            ->latest('attended_at')
            ->paginate(20);

        return view('staff.attendance', compact('todayScans'));
    }
}
