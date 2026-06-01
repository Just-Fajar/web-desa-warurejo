<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiVersionNotice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-API-Version', '1.0.0');
        $response->headers->set('X-API-Status', 'active');
        $response->headers->set('X-API-Environment', config('app.env'));

        $clientVersion = $request->header('X-API-Version');
        if ($clientVersion && version_compare($clientVersion, '1.0.0', '<')) {
            $response->headers->set('API-Deprecated', 'true');
        }

        return $response;
    }
}
