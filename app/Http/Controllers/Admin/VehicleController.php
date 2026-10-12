<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VehicleController extends Controller
{
    /**
     * Display a listing of fleet vehicles.
     */
    public function index(Request $request)
    {
        $query = Vehicle::query();

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        $vehicles = $query->orderBy('is_featured', 'desc')->orderBy('sort_order', 'asc')->paginate(12)->withQueryString();

        $stats = [
            'total' => Vehicle::count(),
            'available' => Vehicle::where('is_available', true)->count(),
            'luxury' => Vehicle::where('category', 'luxury_bride_groom')->count(),
            'coasters' => Vehicle::where('category', 'guest_coaster_bus')->count(),
        ];

        return view('admin.vehicles.index', compact('vehicles', 'stats'));
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create()
    {
        return view('admin.vehicles.create');
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:luxury_bride_groom,guest_coaster_bus,vip_suv_convoy,host_logistics',
            'brand' => 'nullable|string|max:100',
            'model_year' => 'nullable|string|max:50',
            'seating_capacity' => 'required|integer|min:1|max:100',
            'luggage_capacity' => 'nullable|string|max:100',
            'transmission' => 'required|string|max:50',
            'fuel_type' => 'required|string|max:50',
            'color' => 'nullable|string|max:50',
            'price_per_day' => 'required|numeric|min:0',
            'price_per_hour' => 'nullable|numeric|min:0',
            'has_driver' => 'nullable|boolean',
            'has_fuel_included' => 'nullable|boolean',
            'has_ribbon_decoration' => 'nullable|boolean',
            'features_text' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'image_url' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:2000',
            'location' => 'nullable|string|max:255',
            'is_available' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $features = [];
        if (!empty($validated['features_text'])) {
            $features = array_filter(array_map('trim', explode("\n", $validated['features_text'])));
        }

        $imageUrl = $validated['image_url'] ?? null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('uploads/vehicles', 'public');
            $imageUrl = 'storage/' . $path;
        }

        $vehicle = Vehicle::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(5),
            'category' => $validated['category'],
            'brand' => $validated['brand'],
            'model_year' => $validated['model_year'],
            'seating_capacity' => $validated['seating_capacity'],
            'luggage_capacity' => $validated['luggage_capacity'] ?? '2-4 Bags',
            'transmission' => $validated['transmission'],
            'fuel_type' => $validated['fuel_type'],
            'color' => $validated['color'] ?? 'White',
            'price_per_day' => $validated['price_per_day'],
            'price_per_hour' => $validated['price_per_hour'] ?? null,
            'has_driver' => $request->boolean('has_driver', true),
            'has_fuel_included' => $request->boolean('has_fuel_included', true),
            'has_ribbon_decoration' => $request->boolean('has_ribbon_decoration', true),
            'features' => $features,
            'image_url' => $imageUrl,
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'] ?? 'Dar es Salaam, Tanzania',
            'is_available' => $request->boolean('is_available', true),
            'is_featured' => $request->boolean('is_featured', false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle "' . $vehicle->name . '" created successfully.');
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:luxury_bride_groom,guest_coaster_bus,vip_suv_convoy,host_logistics',
            'brand' => 'nullable|string|max:100',
            'model_year' => 'nullable|string|max:50',
            'seating_capacity' => 'required|integer|min:1|max:100',
            'luggage_capacity' => 'nullable|string|max:100',
            'transmission' => 'required|string|max:50',
            'fuel_type' => 'required|string|max:50',
            'color' => 'nullable|string|max:50',
            'price_per_day' => 'required|numeric|min:0',
            'price_per_hour' => 'nullable|numeric|min:0',
            'has_driver' => 'nullable|boolean',
            'has_fuel_included' => 'nullable|boolean',
            'has_ribbon_decoration' => 'nullable|boolean',
            'features_text' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'image_url' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:2000',
            'location' => 'nullable|string|max:255',
            'is_available' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $features = [];
        if (!empty($validated['features_text'])) {
            $features = array_filter(array_map('trim', explode("\n", $validated['features_text'])));
        }

        $imageUrl = $validated['image_url'] ?? $vehicle->image_url;
        if ($request->hasFile('image_file')) {
            // Delete old uploaded image if exists in storage
            if ($vehicle->image_url && str_starts_with($vehicle->image_url, 'storage/')) {
                $oldPath = str_replace('storage/', '', $vehicle->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image_file')->store('uploads/vehicles', 'public');
            $imageUrl = 'storage/' . $path;
        }

        $vehicle->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'brand' => $validated['brand'],
            'model_year' => $validated['model_year'],
            'seating_capacity' => $validated['seating_capacity'],
            'luggage_capacity' => $validated['luggage_capacity'] ?? $vehicle->luggage_capacity,
            'transmission' => $validated['transmission'],
            'fuel_type' => $validated['fuel_type'],
            'color' => $validated['color'],
            'price_per_day' => $validated['price_per_day'],
            'price_per_hour' => $validated['price_per_hour'] ?? null,
            'has_driver' => $request->boolean('has_driver'),
            'has_fuel_included' => $request->boolean('has_fuel_included'),
            'has_ribbon_decoration' => $request->boolean('has_ribbon_decoration'),
            'features' => $features,
            'image_url' => $imageUrl,
            'description' => $validated['description'],
            'location' => $validated['location'] ?? $vehicle->location,
            'is_available' => $request->boolean('is_available'),
            'is_featured' => $request->boolean('is_featured'),
            'sort_order' => $validated['sort_order'] ?? $vehicle->sort_order,
        ]);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle "' . $vehicle->name . '" updated successfully.');
    }

    /**
     * Toggle availability status.
     */
    public function toggleActive(Vehicle $vehicle)
    {
        $vehicle->is_available = !$vehicle->is_available;
        $vehicle->save();

        return back()->with('success', 'Vehicle status updated to ' . ($vehicle->is_available ? 'Available' : 'Unavailable'));
    }

    /**
     * Remove the specified vehicle from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $name = $vehicle->name;

        // Delete associated uploaded image if stored locally
        if ($vehicle->image_url && str_starts_with($vehicle->image_url, 'storage/')) {
            $oldPath = str_replace('storage/', '', $vehicle->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle "' . $name . '" has been deleted.');
    }
}
