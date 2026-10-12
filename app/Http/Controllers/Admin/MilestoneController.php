<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MilestoneController extends Controller
{
    /**
     * Display a listing of milestones.
     */
    public function index(Request $request)
    {
        $query = Milestone::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('badge', 'like', "%{$search}%")
                  ->orWhere('tagline', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $milestones = $query->orderBy('sort_order', 'asc')->paginate(12)->withQueryString();

        $stats = [
            'total' => Milestone::count(),
            'active' => Milestone::where('is_active', true)->count(),
            'inactive' => Milestone::where('is_active', false)->count(),
        ];

        return view('admin.milestones.index', compact('milestones', 'stats'));
    }

    /**
     * Show the form for creating a new milestone.
     */
    public function create()
    {
        return view('admin.milestones.create');
    }

    /**
     * Store a newly created milestone in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:milestones,slug',
            'badge' => 'nullable|string|max:100',
            'tagline' => 'nullable|string|max:150',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'tags_text' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'image_url' => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:500',
            'theme_color' => 'required|string|in:purple,blue,pink,teal,amber,violet,indigo',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        // Ensure uniqueness if slug was auto-generated
        $originalSlug = $slug;
        $counter = 1;
        while (Milestone::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $tags = [];
        if (!empty($validated['tags_text'])) {
            $rawTags = preg_split('/[\r\n,]+/', $validated['tags_text']);
            $tags = array_values(array_filter(array_map('trim', $rawTags)));
        }

        $imageUrl = $validated['image_url'] ?? null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('uploads/milestones', 'public');
            $imageUrl = 'storage/' . $path;
        }

        $milestone = Milestone::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'badge' => $validated['badge'] ?? 'Featured',
            'tagline' => $validated['tagline'] ?? null,
            'icon' => $validated['icon'] ?? '🎉',
            'description' => $validated['description'] ?? null,
            'tags' => $tags,
            'image_url' => $imageUrl,
            'button_text' => $validated['button_text'] ?? 'Explore Features',
            'button_url' => $validated['button_url'] ?? ('categories?cat=' . $slug),
            'theme_color' => $validated['theme_color'],
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.milestones.index')
            ->with('success', 'Milestone "' . $milestone->title . '" created successfully.');
    }

    /**
     * Show the form for editing the specified milestone.
     */
    public function edit(Milestone $milestone)
    {
        return view('admin.milestones.edit', compact('milestone'));
    }

    /**
     * Update the specified milestone in storage.
     */
    public function update(Request $request, Milestone $milestone)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:milestones,slug,' . $milestone->id,
            'badge' => 'nullable|string|max:100',
            'tagline' => 'nullable|string|max:150',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'tags_text' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'image_url' => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:500',
            'theme_color' => 'required|string|in:purple,blue,pink,teal,amber,violet,indigo',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $tags = [];
        if (!empty($validated['tags_text'])) {
            $rawTags = preg_split('/[\r\n,]+/', $validated['tags_text']);
            $tags = array_values(array_filter(array_map('trim', $rawTags)));
        }

        $imageUrl = $validated['image_url'] ?? $milestone->image_url;
        if ($request->hasFile('image_file')) {
            // Delete old uploaded image if stored locally
            if ($milestone->image_url && str_starts_with($milestone->image_url, 'storage/')) {
                $oldPath = str_replace('storage/', '', $milestone->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image_file')->store('uploads/milestones', 'public');
            $imageUrl = 'storage/' . $path;
        }

        $milestone->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug']),
            'badge' => $validated['badge'],
            'tagline' => $validated['tagline'],
            'icon' => $validated['icon'],
            'description' => $validated['description'],
            'tags' => $tags,
            'image_url' => $imageUrl,
            'button_text' => $validated['button_text'] ?? 'Explore Features',
            'button_url' => $validated['button_url'],
            'theme_color' => $validated['theme_color'],
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.milestones.index')
            ->with('success', 'Milestone "' . $milestone->title . '" updated successfully.');
    }

    /**
     * Remove the specified milestone from storage.
     */
    public function destroy(Milestone $milestone)
    {
        $title = $milestone->title;

        // Delete associated uploaded image if stored locally
        if ($milestone->image_url && str_starts_with($milestone->image_url, 'storage/')) {
            $oldPath = str_replace('storage/', '', $milestone->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $milestone->delete();

        return redirect()->route('admin.milestones.index')
            ->with('success', 'Milestone "' . $title . '" deleted successfully.');
    }

    /**
     * Toggle milestone published status.
     */
    public function toggleActive(Milestone $milestone)
    {
        $milestone->update(['is_active' => !$milestone->is_active]);

        $status = $milestone->is_active ? 'published' : 'hidden';
        return redirect()->back()
            ->with('success', "Milestone '{$milestone->title}' is now {$status}.");
    }
}
