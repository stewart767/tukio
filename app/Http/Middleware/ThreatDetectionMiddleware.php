<?php

namespace App\Http\Middleware;

use App\Services\Security\IntrusionPreventionService;
use App\Services\Security\ThreatDetectionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThreatDetectionMiddleware
{
    public function __construct(
        protected ThreatDetectionService $threatDetector,
        protected IntrusionPreventionService $ipsService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        // 1. Check if IP address is actively blocked by IPS
        if ($this->ipsService->isIpBlocked($ip)) {
            return response()->view('errors.blocked-ip', [
                'ip' => $ip,
                'message' => 'Upatikanaji umesitishwa: Anwani yako ya IP imezuiwa kwa muda kutokana na kukiuka sera za usalama wa mtandao.',
            ], 403);
        }

        // 2. Perform real-time heuristic threat inspection
        $detection = $this->threatDetector->inspect($request);

        if ($detection) {
            // Record incident, evaluate auto-ban strikes, and trigger immediate alerts
            $incident = $this->ipsService->recordAndContain($detection);

            // Terminate malicious request immediately
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Security policy violation detected.',
                    'incident_id' => $incident->incident_uuid,
                ], 403);
            }

            return response()->view('errors.blocked-threat', [
                'incident' => $incident,
                'message' => 'Ombi hili limezuiwa na mfumo wa ulinzi wa Tukio360 kutokana na viashiria vya mashambulizi ya mtandao.',
            ], 403);
        }

        return $next($request);
    }
}

