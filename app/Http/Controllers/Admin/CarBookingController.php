<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarBooking;
use Illuminate\Http\Request;

class CarBookingController extends Controller
{
    /**
     * Display listing of all car hire bookings/inquiries.
     */
    public function index(Request $request)
    {
        $query = CarBooking::with(['vehicle', 'host', 'event']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => CarBooking::count(),
            'pending' => CarBooking::where('status', 'pending')->count(),
            'confirmed' => CarBooking::where('status', 'confirmed')->count(),
            'completed' => CarBooking::where('status', 'completed')->count(),
        ];

        return view('admin.car-bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Display full booking details with status management.
     */
    public function show(CarBooking $booking)
    {
        $booking->load(['vehicle', 'host', 'event']);

        return view('admin.car-bookings.show', compact('booking'));
    }

    /**
     * Update booking status and driver assignment.
     */
    public function updateStatus(Request $request, CarBooking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'driver_name' => 'nullable|string|max:255',
            'driver_phone' => 'nullable|string|max:50',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $booking->update($validated);

        return back()->with('success', 'Booking status for ' . $booking->booking_reference . ' updated successfully.');
    }

    /**
     * Delete booking record.
     */
    public function destroy(CarBooking $booking)
    {
        $ref = $booking->booking_reference;
        $booking->delete();

        return redirect()->route('admin.car-bookings.index')
            ->with('success', 'Booking ' . $ref . ' has been deleted.');
    }
}
