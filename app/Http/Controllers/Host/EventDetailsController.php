<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventDetailsController extends Controller
{
    /**
     * Display the event details.
     */
    public function index()
    {
        $host = Auth::guard('host')->user();
        
        return view('host.event-details.index', compact('host'));
    }
}
