<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and attach robust HTTP security headers.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Mitigate clickjacking while allowing same-origin framing (for previews & modal cards)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Cross-site scripting (XSS) filter for legacy user agents
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer policy: send origin only on cross-origin requests
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Disallow cross-domain policy files (Flash, Acrobat)
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // Permissions Policy:
        // camera=(self) is REQUIRED for Gate QR scanning via HTML5-QRCode
        // microphone is disabled, geolocation=(self) for maps, payment=(self) for Flutterwave
        $response->headers->set(
            'Permissions-Policy',
            'camera=(self), microphone=(), geolocation=(self), payment=(self), fullscreen=(self)'
        );

        // Strict-Transport-Security (HSTS) on HTTPS or production
        if ($request->isSecure() || config('app.env') === 'production' || config('app.force_https')) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // Comprehensive Content-Security-Policy (CSP)
        // Permits Tailwind CDN, AlpineJS, Chart.js, HTML5-QRCode, Google Fonts, and Flutterwave checkout
        $cspDirectives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdn.jsdelivr.net https://unpkg.com https://checkout.flutterwave.com https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net https://cdnjs.cloudflare.com",
            "img-src 'self' data: blob: https:",
            "media-src 'self' blob: data:",
            "connect-src 'self' https://*.flutterwave.com https://checkout.flutterwave.com https://apisms.beem.africa",
            "frame-src 'self' https://checkout.flutterwave.com https://www.google.com https://maps.google.com https://wa.me",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self' https://checkout.flutterwave.com",
        ];

        $response->headers->set('Content-Security-Policy', implode('; ', $cspDirectives));

        return $response;
    }
}
