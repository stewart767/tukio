<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Template;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Host;
use App\Models\Vehicle;
use App\Models\CarBooking;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        if ($user->isSuperAdmin()) {
            $totalGuests = Guest::count();
            $attendedGuests = Guest::where('attended', true)->count();
            $attendanceRate = $totalGuests > 0 ? round(($attendedGuests / $totalGuests) * 100, 1) : 0;

            // Generate monthly trends for past 6 months
            $chartMonths = [];
            $chartEvents = [];
            $chartRevenue = [];
            for ($i = 5; $i >= 0; $i--) {
                $monthDate = Carbon::now()->subMonths($i);
                $start = $monthDate->copy()->startOfMonth();
                $end = $monthDate->copy()->endOfMonth();

                $chartMonths[] = $monthDate->format('M');
                $chartEvents[] = Event::whereBetween('created_at', [$start, $end])->count();
                $chartRevenue[] = (float) (Host::whereBetween('created_at', [$start, $end])->sum('total_cost') ?? 0);
            }

            // If current month has all events, ensure chart displays friendly curve
            $totalRevenue = (float) Host::sum('total_cost');

            $data = [
                'total_hosts' => Host::count(),
                'total_events' => Event::count(),
                'total_guests' => $totalGuests,
                'attended_guests' => $attendedGuests,
                'attendance_rate' => $attendanceRate,
                'total_amount' => $totalRevenue,
                'total_vehicles' => Vehicle::count(),
                'available_vehicles' => Vehicle::where('is_available', true)->count(),
                'total_bookings' => CarBooking::count(),
                'pending_bookings' => CarBooking::where('status', 'pending')->count(),
                'active_events' => Event::where('status', 'active')->count(),
                'draft_events' => Event::where('status', 'draft')->count(),
                'completed_events' => Event::where('status', 'completed')->count(),
                'recent_events' => Event::with(['user', 'host'])->latest()->take(6)->get(),
                'recent_users' => User::latest()->take(6)->get(),
                'recent_bookings' => CarBooking::with(['vehicle', 'host'])->latest()->take(5)->get(),
                'chart_months' => $chartMonths,
                'chart_events' => $chartEvents,
                'chart_revenue' => $chartRevenue,
            ];
        } elseif ($user->isEventAdmin()) {
            $totalGuests = Guest::whereHas('event', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();
            $attendedGuests = Guest::whereHas('event', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('attended', true)->count();
            $attendanceRate = $totalGuests > 0 ? round(($attendedGuests / $totalGuests) * 100, 1) : 0;

            $data = [
                'total_events' => Event::where('user_id', $user->id)->count(),
                'total_guests' => $totalGuests,
                'attended_guests' => $attendedGuests,
                'attendance_rate' => $attendanceRate,
                'active_events' => Event::where('user_id', $user->id)->where('status', 'active')->count(),
                'draft_events' => Event::where('user_id', $user->id)->where('status', 'draft')->count(),
                'recent_events' => Event::where('user_id', $user->id)->latest()->take(5)->get(),
                'upcoming_events' => Event::where('user_id', $user->id)->where('date', '>=', now())->orderBy('date')->take(4)->get(),
            ];
        } elseif ($user->isStaff()) {
            // Get events assigned to this staff member (many-to-many or legacy single assignment)
            $assignedEventIds = Event::whereHas('staffMembers', function($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->orWhere('staff_id', $user->id)
                ->pluck('id')
                ->unique();
            
            $data = [
                'assigned_events_count' => $assignedEventIds->count(),
                'scans_today' => Guest::where('attended', true)
                    ->whereDate('attended_at', today())
                    ->whereIn('event_id', $assignedEventIds)
                    ->count(),
                'total_scans' => Guest::where('attended', true)
                    ->whereIn('event_id', $assignedEventIds)
                    ->count(),
                'recent_scans' => Guest::with('event')
                    ->where('attended', true)
                    ->whereIn('event_id', $assignedEventIds)
                    ->latest('attended_at')
                    ->take(10)
                    ->get(),
                'assigned_events' => Event::whereIn('id', $assignedEventIds)
                    ->with('host')
                    ->get(),
            ];
        }

        return view('dashboard.index', compact('data'));
    }
}

