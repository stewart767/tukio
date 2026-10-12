<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\CarBooking;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class CarHireController extends Controller
{
    /**
     * Display host car hire dashboard & bookings.
     */
    public function index(Request $request)
    {
        $host = auth('host')->user();
        $event = $host->events()->latest()->first();

        $category = $request->query('category', 'all');

        $query = Vehicle::query()->where('is_available', true);
        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        $vehicles = $query->orderBy('is_featured', 'desc')->orderBy('sort_order', 'asc')->get();

        // Host's active and previous car bookings
        $myBookings = CarBooking::with('vehicle')
            ->where('host_id', $host->id)
            ->latest()
            ->get();

        return view('host.car-hire.index', compact(
            'host',
            'event',
            'vehicles',
            'category',
            'myBookings'
        ));
    }

    /**
     * Store a car hire request directly linked to host event.
     */
    public function store(Request $request)
    {
        $host = auth('host')->user();
        $event = $host->events()->latest()->first();

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date|after_or_equal:today',
            'days_count' => 'required|integer|min:1|max:30',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'nullable|string|max:255',
            'pickup_time' => 'nullable|string|max:50',
            'need_decoration' => 'nullable|boolean',
            'need_guest_coasters' => 'nullable|boolean',
            'guest_coasters_count' => 'nullable|integer|min:0|max:10',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        $days = (int) $validated['days_count'];
        $baseTotal = (float) $vehicle->price_per_day * $days;

        $extraCoasters = (int) ($request->input('guest_coasters_count', 0));
        $coasterPricePerDay = 400000;
        $coasterTotal = $request->boolean('need_guest_coasters') ? ($extraCoasters * $coasterPricePerDay * $days) : 0;

        $totalPrice = $baseTotal + $coasterTotal;

        $booking = CarBooking::create([
            'vehicle_id' => $vehicle->id,
            'host_id' => $host->id,
            'event_id' => $event?->id,
            'customer_name' => $host->full_name,
            'customer_phone' => $host->phone,
            'customer_email' => $host->email,
            'event_type' => $event?->category ?? $host->event_type ?? 'Wedding',
            'start_date' => $validated['start_date'],
            'days_count' => $days,
            'pickup_location' => $validated['pickup_location'],
            'dropoff_location' => $validated['dropoff_location'] ?? ($event?->venue ?? null),
            'pickup_time' => $validated['pickup_time'] ?? '08:00 AM',
            'guest_count' => $vehicle->seating_capacity,
            'need_decoration' => $request->boolean('need_decoration', true),
            'need_guest_coasters' => $request->boolean('need_guest_coasters', false),
            'guest_coasters_count' => $extraCoasters,
            'special_requests' => $validated['special_requests'] ?? null,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return redirect()->route('host.car-hire.show', $booking->id)
            ->with('success', 'Your vehicle reservation for ' . $vehicle->name . ' has been booked successfully! Reference: ' . $booking->booking_reference);
    }

    /**
     * Show booking details inside Host panel.
     */
    public function show($id)
    {
        $host = auth('host')->user();
        $booking = CarBooking::with(['vehicle', 'event'])
            ->where('host_id', $host->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('host.car-hire.show', compact('host', 'booking'));
    }
}
