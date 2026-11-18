<?php

namespace App\Http\Middleware;

use App\Models\SecurityAuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminInactivityTimeoutMiddleware
{
    /**
     * Inactivity threshold in seconds (30 minutes).
     */
    protected int $inactivityTimeout = 1800;

    /**
     * Handle an incoming request and enforce administrative inactivity timeout.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Apply to privileged administrative roles
            if (in_array($user->role, ['super_admin', 'event_admin'], true)) {
                $lastActivity = session('admin_last_activity');
                $currentTime = now()->timestamp;

                if ($lastActivity && ($currentTime - $lastActivity) > $this->inactivityTimeout) {
                    SecurityAuditLog::record(
                        'admin_inactivity_timeout',
                        [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'idle_seconds' => $currentTime - $lastActivity,
                        ],
                        $user->id,
                        $request->ip()
                    );

                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('admin.login')->withErrors([
                        'email' => 'Your session expired due to 30 minutes of inactivity. Please log in again.',
                    ]);
                }

                session(['admin_last_activity' => $currentTime]);
            }
        }

        return $next($request);
    }
}

