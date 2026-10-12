<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEventConfigured
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = auth('host')->user();
        
        // Allow access to event configuration page
        if ($request->routeIs('host.event-config') || $request->routeIs('host.configure-event')) {
            return $next($request);
        }
        
        // If host hasn't configured their event, redirect to configuration
        if (!$host || !$host->hasConfiguredEvent()) {
            return redirect()->route('host.event-config')->with('error', 'Please configure your event before accessing other pages.');
        }

        return $next($request);
    }
}
