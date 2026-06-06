<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Generate/Ensure CSP Nonce exists for this request
        $nonce = csp_nonce();

        // 2. Proceed with the request
        $response = $next($request);

        // Only inject nonces and set headers on Response objects
        if ($response instanceof Response) {
            // Only inject nonces to HTML responses
            $contentType = $response->headers->get('Content-Type') ?? '';
            if (str_contains(strtolower($contentType), 'text/html')) {
                $content = $response->getContent();
                if (is_string($content) && $content !== '') {
                    // Inject nonce to script tags without nonce (ignore application/ld+json script templates if they have no body, but inject standard ones)
                    $content = preg_replace_callback('/<script(?!.*?nonce=)(.*?)>/i', function ($matches) use ($nonce) {
                        // Avoid adding nonces to non-JS script types unless standard
                        if (str_contains($matches[1], 'type=') && !str_contains($matches[1], 'type="application/javascript"') && !str_contains($matches[1], 'type="module"') && !str_contains($matches[1], 'type="application/ld+json"')) {
                            return $matches[0];
                        }

                        return "<script{$matches[1]} nonce=\"{$nonce}\">";
                    }, $content);

                    // Inject nonce to style tags without nonce
                    $content = preg_replace_callback('/<style(?!.*?nonce=)(.*?)>/i', function ($matches) use ($nonce) {
                        return "<style{$matches[1]} nonce=\"{$nonce}\">";
                    }, $content);

                    // Preserve original property for testing helpers (like assertViewIs)
                    $original = null;
                    if ($response instanceof \Illuminate\Http\Response) {
                        /** @var \Illuminate\Http\Response $laravelResponse */
                        $laravelResponse = $response;
                        $original = $laravelResponse->original;
                    }

                    $response->setContent($content);

                    if ($original !== null && $response instanceof \Illuminate\Http\Response) {
                        /** @var \Illuminate\Http\Response $laravelResponse */
                        $laravelResponse = $response;
                        $laravelResponse->original = $original;
                    }
                }
            }

            // Set security headers
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
            $response->headers->set('X-Frame-Options', 'DENY');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-XSS-Protection', '0');
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
            $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

            // Setup Content Security Policy
            $scriptSrc = "script-src 'self' 'unsafe-eval' 'nonce-{$nonce}' cdn.tiny.cloud cdn.jsdelivr.net cdn.ckeditor.com npmcdn.com blob:";
            $styleSrc = "style-src 'self' 'unsafe-inline' fonts.bunny.net cdn.tiny.cloud cdn.jsdelivr.net cdnjs.cloudflare.com fonts.googleapis.com";
            $connectSrc = "connect-src 'self' cdn.tiny.cloud https://*.tiny.cloud";

            // Allow Vite development server in local environment
            if (config('app.env') === 'local') {
                $scriptSrc .= " http: https: 'unsafe-inline'";
                $styleSrc .= " http: https: 'unsafe-inline'";
                $connectSrc .= ' http: https: ws: wss:';
            }

            $csp = "default-src 'self'; " .
                "{$scriptSrc}; " .
                "{$styleSrc}; " .
                "font-src 'self' fonts.bunny.net cdnjs.cloudflare.com fonts.gstatic.com data:; " .
                "img-src 'self' data: https: http:; " .
                "frame-src 'self' maps.google.com https://www.google.com; " .
                "{$connectSrc}; " .
                "object-src 'none'; " .
                "base-uri 'self'; " .
                "form-action 'self';";

            $response->headers->set('Content-Security-Policy', $csp);
        }

        return $response;
    }
}
