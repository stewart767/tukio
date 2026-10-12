<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of active templates for public viewing.
     */
    public function index()
    {
        $templates = Template::where('is_active', true)
            ->latest()
            ->paginate(12);
        
        return view('templates.index', compact('templates'));
    }

    /**
     * Display the specified template.
     */
    public function show(Template $template)
    {
        // Only show active templates to public, unless user is superadmin
        if (!$template->is_active && !(auth()->check() && auth()->user()->isSuperAdmin())) {
            abort(404);
        }
        
        return view('templates.show', compact('template'));
    }
}
