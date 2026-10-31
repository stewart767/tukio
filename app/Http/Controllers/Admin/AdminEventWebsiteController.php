<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventWebsite;
use App\Models\EventRsvp;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminEventWebsiteController extends Controller
{
    /**
     * Display a listing of all event websites across all hosts.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status'); // published, draft

        $query = EventWebsite::with(['event.host'])
            ->withCount([
                'rsvps as total_rsvps',
                'rsvps as confirmed_count' => function ($q) {
                    $q->where('attendance_status', 'attending');
                },
                'rsvps as checked_in_count' => function ($q) {
                    $q->where('is_checked_in', true);
                },
            ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('host_names', 'like', "%{$search}%")
                  ->orWhere('venue_name', 'like', "%{$search}%");
            });
        }

        if ($status === 'published') {
            $query->where('is_published', true);
        } elseif ($status === 'draft') {
            $query->where('is_published', false);
        }

        $websites = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total_websites' => EventWebsite::count(),
            'published_websites' => EventWebsite::where('is_published', true)->count(),
            'total_rsvps' => EventRsvp::count(),
            'total_attending' => EventRsvp::where('attendance_status', 'attending')->count(),
            'total_checked_in' => EventRsvp::where('is_checked_in', true)->count(),
        ];

        return view('admin.event-websites.index', compact('websites', 'stats', 'search', 'status'));
    }

    /**
     * Display all RSVPs across the system or for a specific website.
     */
    public function allRsvps(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $websiteId = $request->input('website_id');

        $query = EventRsvp::with('website.event.host');

        if ($websiteId) {
            $query->where('event_website_id', $websiteId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('confirmation_code', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['attending', 'declined', 'tentative'])) {
            $query->where('attendance_status', $status);
        } elseif ($status === 'checked_in') {
            $query->where('is_checked_in', true);
        }

        $rsvps = $query->latest()->paginate(20)->withQueryString();

        $websitesList = EventWebsite::select('id', 'title', 'slug')->orderBy('title')->get();

        $stats = [
            'total_rsvps' => EventRsvp::count(),
            'attending' => EventRsvp::where('attendance_status', 'attending')->count(),
            'declined' => EventRsvp::where('attendance_status', 'declined')->count(),
            'tentative' => EventRsvp::where('attendance_status', 'tentative')->count(),
            'checked_in' => EventRsvp::where('is_checked_in', true)->count(),
        ];

        return view('admin.event-websites.rsvps', compact('rsvps', 'websitesList', 'stats', 'search', 'status', 'websiteId'));
    }

    /**
     * Export RSVPs to CSV for admin.
     */
    public function exportRsvps(Request $request): StreamedResponse
    {
        $websiteId = $request->input('website_id');
        $query = EventRsvp::with('website');

        if ($websiteId) {
            $query->where('event_website_id', $websiteId);
        }

        $rsvps = $query->latest()->get();

        $filename = 'admin_rsvps_export_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($rsvps) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            fputcsv($handle, [
                'Confirmation Pass',
                'Event Website',
                'Guest Full Name',
                'Phone',
                'Email',
                'Attendance Status',
                'Accompanying (+1s)',
                'Total Party Size',
                'Dietary Requirements',
                'Special Wishes / Notes',
                'Door Check-In',
                'Registered At',
            ]);

            foreach ($rsvps as $rsvp) {
                fputcsv($handle, [
                    $rsvp->confirmation_code,
                    $rsvp->website ? $rsvp->website->title : 'N/A',
                    $rsvp->full_name,
                    $rsvp->phone,
                    $rsvp->email ?: '',
                    ucfirst($rsvp->attendance_status),
                    $rsvp->plus_one_count,
                    $rsvp->total_party_size,
                    $rsvp->dietary_requirements ?: '',
                    $rsvp->special_requests ?: '',
                    $rsvp->is_checked_in ? 'Yes (' . $rsvp->checked_in_at?->format('Y-m-d H:i') . ')' : 'No',
                    $rsvp->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }
}

