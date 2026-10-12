<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::orderBy('sort_order')->get();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active' => 'sometimes|boolean',
        ];

        // Add additional fields for homepage
        if ($page->slug === 'homepage') {
            $rules['welcome_title'] = 'nullable|string|max:255';
            $rules['welcome_content'] = 'nullable|string';
            $rules['benefits_title'] = 'nullable|string|max:255';
            $rules['benefits_subtitle'] = 'nullable|string|max:255';
            $rules['benefits_content'] = 'nullable|string';
            $rules['faq_title'] = 'nullable|string|max:255';
            $rules['faq_subtitle'] = 'nullable|string|max:255';
            $rules['faq_content'] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        // Handle checkbox - if not present, set to false
        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : false;

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }
}
