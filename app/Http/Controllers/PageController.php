<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the homepage.
     */
    public function showHomepage()
    {
        // Get the homepage page from the database
        $homepage = Page::where('slug', 'homepage')->where('is_active', true)->first();
        
        // If homepage page doesn't exist, fallback to static homepage
        if (!$homepage) {
            return redirect()->route('home.static');
        }
        
        // Get featured templates for homepage
        $templates = \App\Models\Template::where('is_active', true)
            ->latest()
            ->take(4)
            ->get();
        
        // Get active milestones for "Tailored for Every Milestone"
        $milestones = \App\Models\Milestone::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Get featured fleet for homepage
        $vehicles = \App\Models\Vehicle::where('is_available', true)
            ->orderBy('is_featured', 'desc')
            ->take(3)
            ->get();

        // Get featured venues for homepage
        $venues = \App\Models\Venue::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order', 'asc')
            ->take(3)
            ->get();
        
        return view('homepage-redesigned', compact('homepage', 'templates', 'vehicles', 'milestones', 'venues'));
    }
    
    /**
     * Display the specified page.
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        return view('pages.show', compact('page'));
    }
}