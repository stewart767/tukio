<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'event.configured' => \App\Http\Middleware\EnsureEventConfigured::class,
        ]);
        
        // Apply SetLocale, SecurityHeaders, ThreatDetection, and AdminInactivityTimeout middleware to all web routes
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SecurityHeadersMiddleware::class,
            \App\Http\Middleware\ThreatDetectionMiddleware::class,
            \App\Http\Middleware\AdminInactivityTimeoutMiddleware::class,
        ]);

        // Exempt Gate Scanner, RSVP, and external Webhook endpoints from CSRF token verification
        $middleware->validateCsrfTokens(except: [
            'scan',
            'staff/scan',
            'invitation/*/rsvp',
            'webhook/*',
            'webhooks/*',
            'beem/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Redirect host routes to host login when unauthenticated
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('host/*')) {
                return redirect()->route('host.login');
            }
            return redirect()->route('login');
        });
    })->create();
