<?php

namespace App\Http\Controllers;

use App\Models\Host;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostDashboardController extends Controller
{
    /**
     * Show the host dashboard.
     */
    public function index()
    {
        $host = Auth::guard('host')->user();
        
        // If host hasn't configured their event, redirect to configuration
        if (!$host->hasConfiguredEvent()) {
            return redirect()->route('host.event-config')->with('error', 'Please configure your event before accessing the dashboard.');
        }

        $events = $host->events()->latest()->get();
        $invoice = $host->ensureInvoice();
        
        // Calculate total cost - use events total_cost if available, otherwise calculate from host
        $calculatedTotalCost = $invoice ? $invoice->total_amount : $events->sum('total_cost');
        if ($calculatedTotalCost == 0 && $host->number_of_guests > 0) {
            $calculatedTotalCost = $host->number_of_guests * 2000;
        }
        
        $data = [
            'host' => $host,
            'events' => $events,
            'invoice' => $invoice,
            'total_events' => $events->count(),
            'active_events' => $events->where('status', 'active')->count(),
            'total_guests' => $events->sum('guest_count') ?: $host->number_of_guests,
            'total_cost' => $calculatedTotalCost,
        ];

        return view('host.dashboard.index', compact('data'));
    }
}
