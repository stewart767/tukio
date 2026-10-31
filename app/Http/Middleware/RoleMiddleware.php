<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        // Super Admin has universal access to all staff and administrative endpoints
        if ($userRole === 'super_admin') {
            return $next($request);
        }

        // Unpack variadic, comma-separated, or pipe-separated roles
        $allowedRoles = [];
        foreach ($roles as $roleArg) {
            foreach (explode(',', $roleArg) as $subRole) {
                foreach (explode('|', $subRole) as $finalRole) {
                    $cleaned = trim($finalRole);
                    if ($cleaned !== '') {
                        $allowedRoles[] = $cleaned;
                    }
                }
            }
        }

        if (!in_array($userRole, $allowedRoles, true)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
