<?php

namespace App\Http\Controllers;

use App\Models\CarBooking;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CarHireController extends Controller
{
    /**
     * Display public car hire showroom.
     */
    public function index(Request $request)
    {
        $category = $request->query('category', 'all');
        $search = $request->query('search');
        $sortBy = $request->query('sort', 'featured');

        $query = Vehicle::query()->where('is_available', true);

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        match ($sortBy) {
            'price_low' => $query->orderBy('price_per_day', 'asc'),
            'price_high' => $query->orderBy('price_per_day', 'desc'),
            'capacity' => $query->orderBy('seating_capacity', 'desc'),
            default => $query->orderBy('is_featured', 'desc')->orderBy('sort_order', 'asc'),
        };

        $vehicles = $query->paginate(9)->withQueryString();

        // Count per category
        $categoryCounts = [
            'all' => Vehicle::where('is_available', true)->count(),
            'luxury_bride_groom' => Vehicle::where('is_available', true)->where('category', 'luxury_bride_groom')->count(),
            'guest_coaster_bus' => Vehicle::where('is_available', true)->where('category', 'guest_coaster_bus')->count(),
            'vip_suv_convoy' => Vehicle::where('is_available', true)->where('category', 'vip_suv_convoy')->count(),
        ];

        // Featured fleet for highlight slider
        $featuredVehicles = Vehicle::where('is_available', true)->where('is_featured', true)->take(4)->get();

        return view('pages.car-hire', compact(
            'vehicles',
            'category',
            'search',
            'sortBy',
            'categoryCounts',
            'featuredVehicles'
        ));
    }

    /**
     * Display single vehicle detail & booking form.
     */
    public function show($slug)
    {
        $vehicle = Vehicle::where('slug', $slug)->firstOrFail();

        // Related vehicles in same category
        $relatedVehicles = Vehicle::where('category', $vehicle->category)
            ->where('id', '!=', $vehicle->id)
            ->where('is_available', true)
            ->take(3)
            ->get();

        return view('pages.car-hire-show', compact('vehicle', 'relatedVehicles'));
    }

    /**
     * Store a car hire booking / reservation inquiry.
     */
    public function book(Request $request, $slug)
    {
        $vehicle = Vehicle::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:30',
            'customer_email' => 'nullable|email|max:255',
            'event_type' => 'required|string|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'days_count' => 'required|integer|min:1|max:30',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'nullable|string|max:255',
            'pickup_time' => 'nullable|string|max:50',
            'guest_count' => 'nullable|integer|min:1',
            'need_decoration' => 'nullable|boolean',
            'need_guest_coasters' => 'nullable|boolean',
            'guest_coasters_count' => 'nullable|integer|min:0|max:10',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $days = (int) ($validated['days_count'] ?? 1);
        $basePrice = (float) $vehicle->price_per_day * $days;

        // Additional Coaster cost if requested alongside luxury car
        $extraCoasters = (int) ($request->input('guest_coasters_count', 0));
        $coasterPricePerDay = 400000; // standard coaster rate
        $coasterTotal = $request->boolean('need_guest_coasters') ? ($extraCoasters * $coasterPricePerDay * $days) : 0;

        $totalPrice = $basePrice + $coasterTotal;

        // If host is logged in, attach host_id
        $hostId = null;
        $eventId = null;
        if (auth('host')->check()) {
            $host = auth('host')->user();
            $hostId = $host->id;
            $event = $host->events()->latest()->first();
            $eventId = $event?->id;
        }

        $booking = CarBooking::create([
            'vehicle_id' => $vehicle->id,
            'host_id' => $hostId,
            'event_id' => $eventId,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'event_type' => $validated['event_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'days_count' => $days,
            'pickup_location' => $validated['pickup_location'],
            'dropoff_location' => $validated['dropoff_location'] ?? null,
            'pickup_time' => $validated['pickup_time'] ?? '08:00 AM',
            'guest_count' => $validated['guest_count'] ?? $vehicle->seating_capacity,
            'need_decoration' => $request->boolean('need_decoration', true),
            'need_guest_coasters' => $request->boolean('need_guest_coasters', false),
            'guest_coasters_count' => $extraCoasters,
            'special_requests' => $validated['special_requests'] ?? null,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        Log::info('New Car Hire Booking created', [
            'booking_id' => $booking->id,
            'ref' => $booking->booking_reference,
            'vehicle' => $vehicle->name,
            'customer' => $booking->customer_name,
            'phone' => $booking->customer_phone,
            'total' => $totalPrice,
        ]);

        return redirect()->route('car-hire.confirmed', $booking->booking_reference)
            ->with('success', 'Your car hire request has been submitted successfully! Ref: ' . $booking->booking_reference);
    }

    /**
     * Display booking confirmation voucher.
     */
    public function confirmed($reference)
    {
        $booking = CarBooking::with(['vehicle', 'host', 'event'])->where('booking_reference', $reference)->firstOrFail();

        return view('pages.car-hire-confirmed', compact('booking'));
    }
}
