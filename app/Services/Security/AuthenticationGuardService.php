<?php

namespace App\Services\Security;

use App\Models\SecurityAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticationGuardService
{
    /**
     * Max failed attempts allowed before temporary lockout.
     */
    protected int $maxAttempts = 5;

    /**
     * Cooldown duration in seconds (15 minutes).
     */
    protected int $decaySeconds = 900;

    public function __construct(
        protected IntrusionPreventionService $ipsService
    ) {}

    /**
     * Verify whether the identifier/IP is currently locked out.
     *
     * @throws ValidationException
     */
    public function ensureNotRateLimited(Request $request, string $identifier): void
    {
        $key = $this->throttleKey($request, $identifier);

        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => [
                    "Too many failed login attempts. For security reasons, access is locked. Please try again in {$seconds} seconds.",
                ],
            ]);
        }
    }

    /**
     * Record a failed authentication attempt.
     */
    public function recordFailedAttempt(Request $request, string $identifier, string $guard = 'web'): void
    {
        $key = $this->throttleKey($request, $identifier);
        RateLimiter::hit($key, $this->decaySeconds);

        $attempts = RateLimiter::attempts($key);
        $ip = $request->ip();

        // Progressive throttling: introduce artificial delay after 2 failed attempts
        if ($attempts >= 3) {
            usleep(min($attempts * 200000, 1000000)); // 0.6s to 1.0s delay
        }

        // Record audit event
        SecurityAuditLog::record(
            'auth_failed_attempt',
            [
                'identifier' => Str::mask($identifier, '*', 3, -3),
                'guard' => $guard,
                'attempt_count' => $attempts,
                'max_attempts' => $this->maxAttempts,
                'user_agent' => $request->userAgent(),
            ],
            null,
            $ip
        );

        // If threshold reached, report brute-force incident to IPS
        if ($attempts >= $this->maxAttempts) {
            SecurityAuditLog::record(
                'auth_account_locked',
                [
                    'identifier' => Str::mask($identifier, '*', 3, -3),
                    'guard' => $guard,
                    'lockout_seconds' => $this->decaySeconds,
                ],
                null,
                $ip
            );

            // Escalate to Intrusion Prevention engine
            $this->ipsService->recordAndContain([
                'source_ip' => $ip,
                'request_url' => $request->fullUrl(),
                'request_method' => $request->method(),
                'user_agent' => $request->userAgent(),
                'threat_type' => 'brute_force',
                'severity' => 'high',
                'confidence_score' => 90,
                'detected_rule' => "Authentication Brute-Force: {$attempts} failed attempts for {$identifier}",
                'payload_sample' => "Target: {$identifier} | Guard: {$guard}",
            ]);
        }
    }

    /**
     * Clear failed attempts on successful authentication.
     */
    public function clearFailedAttempts(Request $request, string $identifier, ?int $userId = null): void
    {
        $key = $this->throttleKey($request, $identifier);
        RateLimiter::clear($key);

        SecurityAuditLog::record(
            'auth_successful_login',
            [
                'identifier' => Str::mask($identifier, '*', 3, -3),
                'session_id' => Str::limit($request->session()->getId(), 8),
            ],
            $userId,
            $request->ip()
        );
    }

    /**
     * Generate rate limit cache key.
     */
    protected function throttleKey(Request $request, string $identifier): string
    {
        return 'login:auth:' . sha1($request->ip() . '|' . Str::lower($identifier));
    }
}

