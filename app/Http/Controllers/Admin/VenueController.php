<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VenueController extends Controller
{
    /**
     * Display a listing of venues.
     */
    public function index(Request $request)
    {
        $query = Venue::query();

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'featured') {
                $query->where('is_featured', true);
            }
        }

        $venues = $query->orderBy('is_featured', 'desc')->orderBy('sort_order', 'asc')->paginate(12)->withQueryString();

        $stats = [
            'total' => Venue::count(),
            'active' => Venue::where('is_active', true)->count(),
            'featured' => Venue::where('is_featured', true)->count(),
            'ballrooms' => Venue::where('category', 'like', '%Ballroom%')->count(),
            'outdoor' => Venue::where('category', 'like', '%Beach%')->orWhere('category', 'like', '%Garden%')->count(),
        ];

        return view('admin.venues.index', compact('venues', 'stats'));
    }

    /**
     * Show the form for creating a new venue.
     */
    public function create()
    {
        return view('admin.venues.create');
    }

    /**
     * Store a newly created venue in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:venues,slug',
            'category' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|string|max:100',
            'badge' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:2000',
            'amenities_text' => 'nullable|string|max:1000',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'image_url' => 'nullable|string|max:500',
            'price_range' => 'nullable|string|max:150',
            'inquiry_button_text' => 'nullable|string|max:100',
            'inquiry_subject' => 'nullable|string|max:200',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Venue::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $amenities = [];
        if (!empty($validated['amenities_text'])) {
            $raw = preg_split('/[\r\n,]+/', $validated['amenities_text']);
            $amenities = array_values(array_filter(array_map('trim', $raw)));
        }

        $imageUrl = $validated['image_url'] ?? null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('uploads/venues', 'public');
            $imageUrl = 'storage/' . $path;
        }

        $venue = Venue::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'category' => $validated['category'],
            'location' => $validated['location'],
            'capacity' => $validated['capacity'] ?? '200 – 1,000 Guests',
            'badge' => $validated['badge'] ?? ('🏛️ ' . $validated['category']),
            'description' => $validated['description'] ?? null,
            'amenities' => $amenities,
            'image_url' => $imageUrl,
            'price_range' => $validated['price_range'] ?? null,
            'inquiry_button_text' => $validated['inquiry_button_text'] ?? 'Inquire About Venue',
            'inquiry_subject' => $validated['inquiry_subject'] ?? ('Venue Inquiry - ' . $validated['name']),
            'is_featured' => $request->boolean('is_featured', true),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue "' . $venue->name . '" created successfully.');
    }

    /**
     * Show the form for editing the specified venue.
     */
    public function edit(Venue $venue)
    {
        return view('admin.venues.edit', compact('venue'));
    }

    /**
     * Update the specified venue in storage.
     */
    public function update(Request $request, Venue $venue)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:venues,slug,' . $venue->id,
            'category' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|string|max:100',
            'badge' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:2000',
            'amenities_text' => 'nullable|string|max:1000',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'image_url' => 'nullable|string|max:500',
            'price_range' => 'nullable|string|max:150',
            'inquiry_button_text' => 'nullable|string|max:100',
            'inquiry_subject' => 'nullable|string|max:200',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $amenities = [];
        if (!empty($validated['amenities_text'])) {
            $raw = preg_split('/[\r\n,]+/', $validated['amenities_text']);
            $amenities = array_values(array_filter(array_map('trim', $raw)));
        }

        $imageUrl = $validated['image_url'] ?? $venue->image_url;
        if ($request->hasFile('image_file')) {
            // Delete old uploaded image if stored locally
            if ($venue->image_url && str_starts_with($venue->image_url, 'storage/')) {
                $oldPath = str_replace('storage/', '', $venue->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image_file')->store('uploads/venues', 'public');
            $imageUrl = 'storage/' . $path;
        }

        $venue->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['slug']),
            'category' => $validated['category'],
            'location' => $validated['location'],
            'capacity' => $validated['capacity'],
            'badge' => $validated['badge'],
            'description' => $validated['description'],
            'amenities' => $amenities,
            'image_url' => $imageUrl,
            'price_range' => $validated['price_range'],
            'inquiry_button_text' => $validated['inquiry_button_text'] ?? 'Inquire About Venue',
            'inquiry_subject' => $validated['inquiry_subject'],
            'is_featured' => $request->boolean('is_featured', true),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue "' . $venue->name . '" updated successfully.');
    }

    /**
     * Remove the specified venue from storage.
     */
    public function destroy(Venue $venue)
    {
        $name = $venue->name;

        // Delete associated uploaded image if stored locally
        if ($venue->image_url && str_starts_with($venue->image_url, 'storage/')) {
            $oldPath = str_replace('storage/', '', $venue->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $venue->delete();

        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue "' . $name . '" deleted successfully.');
    }

    /**
     * Toggle venue published status.
     */
    public function toggleActive(Venue $venue)
    {
        $venue->update(['is_active' => !$venue->is_active]);

        $status = $venue->is_active ? 'published' : 'hidden';
        return redirect()->back()
            ->with('success', "Venue '{$venue->name}' is now {$status}.");
    }

    /**
     * Toggle venue featured status.
     */
    public function toggleFeatured(Venue $venue)
    {
        $venue->update(['is_featured' => !$venue->is_featured]);

        $status = $venue->is_featured ? 'featured' : 'standard';
        return redirect()->back()
            ->with('success', "Venue '{$venue->name}' is now marked as {$status}.");
    }
}
