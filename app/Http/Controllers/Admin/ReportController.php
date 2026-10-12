<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Host;
use App\Models\Invitation;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    /**
     * Display a consolidated view of platform performance.
     */
    public function index()
    {
        $metrics = [
            'total_events' => Event::count(),
            'upcoming_events' => Event::whereDate('date', '>=', today())->count(),
            'completed_events' => Event::where('status', 'completed')->count(),
            'active_hosts' => Host::where('is_active', true)->count(),
            'total_guests' => Guest::count(),
            'invitations_sent' => Invitation::whereNotNull('sent_at')->count(),
            'whatsapp_sent' => Invitation::whereNotNull('whatsapp_sent_at')->count(),
            'whatsapp_delivered' => Invitation::whereNotNull('whatsapp_delivered_at')->count(),
        ];

        $eventTrendCollection = Event::whereNotNull('date')
            ->where('date', '>=', now()->subMonths(5)->startOfMonth())
            ->orderBy('date')
            ->get()
            ->groupBy(function ($event) {
                return Carbon::parse($event->date)->format('Y-m');
            });

        $eventsTrend = $eventTrendCollection->map(function ($group, $yearMonth) {
            $month = Carbon::createFromFormat('Y-m', $yearMonth);

            return [
                'label' => $month->format('M Y'),
                'value' => $group->count(),
            ];
        })->values()->all();

        $topEvents = Event::with(['host:id,full_name', 'template:id,name'])
            ->withCount(['guests', 'invitations'])
            ->orderByDesc('guests_count')
            ->limit(5)
            ->get();

        $invitationAggregate = Invitation::selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN whatsapp_sent_at IS NOT NULL THEN 1 ELSE 0 END) as whatsapp_sent')
            ->selectRaw('SUM(CASE WHEN whatsapp_delivered_at IS NOT NULL THEN 1 ELSE 0 END) as whatsapp_delivered')
            ->selectRaw('SUM(CASE WHEN status = "opened" THEN 1 ELSE 0 END) as opened')
            ->first();

        $deliverySummary = [
            'total' => (int) ($invitationAggregate->total ?? 0),
            'whatsapp_sent' => (int) ($invitationAggregate->whatsapp_sent ?? 0),
            'whatsapp_delivered' => (int) ($invitationAggregate->whatsapp_delivered ?? 0),
            'opened' => (int) ($invitationAggregate->opened ?? 0),
        ];

        $deliverySummary['delivery_rate'] = $deliverySummary['total'] > 0
            ? round(($deliverySummary['whatsapp_delivered'] / $deliverySummary['total']) * 100, 1)
            : 0;

        $deliverySummary['open_rate'] = $deliverySummary['total'] > 0
            ? round(($deliverySummary['opened'] / $deliverySummary['total']) * 100, 1)
            : 0;

        $guestAggregate = Guest::selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN attended = 1 THEN 1 ELSE 0 END) as attended_total')
            ->first();

        $attendanceSummary = [
            'total' => (int) ($guestAggregate->total ?? 0),
            'attended' => (int) ($guestAggregate->attended_total ?? 0),
        ];

        $attendanceSummary['attendance_rate'] = $attendanceSummary['total'] > 0
            ? round(($attendanceSummary['attended'] / $attendanceSummary['total']) * 100, 1)
            : 0;

        $recentInvitations = Invitation::with([
            'event:id,title,date',
            'guest:id,full_name',
        ])
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.reports.index', compact(
            'metrics',
            'eventsTrend',
            'topEvents',
            'deliverySummary',
            'attendanceSummary',
            'recentInvitations'
        ));
    }
}
