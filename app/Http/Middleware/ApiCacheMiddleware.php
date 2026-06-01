<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiCacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only cache GET/HEAD requests
        if (!$request->isMethod('GET') && !$request->isMethod('HEAD')) {
            return $next($request);
        }

        $response = $next($request);

        // Don't ETag/Cache non-200 responses
        if ($response->getStatusCode() !== 200) {
            return $response;
        }

        $content = $response->getContent();
        if ($content === false || $content === null) {
            return $response;
        }

        $etag = md5($content);

        $response->headers->set('ETag', '"' . $etag . '"');
        $response->headers->set('Cache-Control', 'public, max-age=60, must-revalidate');

        $ifNoneMatch = $request->header('If-None-Match');
        if ($ifNoneMatch) {
            // Trim quotes if any
            $ifNoneMatch = trim($ifNoneMatch, '"');
            if ($ifNoneMatch === $etag) {
                // Return 304 Not Modified
                $notModifiedResponse = response()->make('', 304);
                $notModifiedResponse->headers->set('ETag', '"' . $etag . '"');
                $notModifiedResponse->headers->set('Cache-Control', 'public, max-age=60, must-revalidate');
                return $notModifiedResponse;
            }
        }

        return $response;
    }
}
