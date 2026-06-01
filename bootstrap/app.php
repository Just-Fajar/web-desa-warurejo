<?php

/**
 * Bootstrap Application - Laravel 11 Entry Point
 *
 * File ini adalah titik awal aplikasi Laravel
 * Configure routing, middleware, dan exception handling
 *
 * JANGAN UBAH file ini kecuali:
 * - Tambah custom middleware global
 * - Tambah route file baru
 * - Custom exception handling
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    /**
     * Route Configuration
     * - web: Routes untuk halaman public (home, berita, profil)
     * - api: Routes untuk REST API (jika ada mobile app)
     * - commands: Custom artisan commands
     * - health: Health check endpoint untuk monitoring (/up)
     */
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    /**
     * Middleware Configuration
     */
    ->withMiddleware(function (Middleware $middleware): void {
        /**
         * Middleware Aliases - Shortcut untuk protect routes
         *
         * Usage di routes/web.php:
         * - Route::middleware('admin')->group() untuk protect admin routes
         * - Route::middleware('admin.guest') untuk login page
         */
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminAuthenticate::class,
            'admin.guest' => \App\Http\Middleware\RedirectIfAdmin::class,
            'auto.publish' => \App\Http\Middleware\PublishScheduledContent::class,
            'api.cache' => \App\Http\Middleware\ApiCacheMiddleware::class,
            'api.version' => \App\Http\Middleware\ApiVersionNotice::class,
        ]);

        /**
         * TrackVisitor Middleware - OTOMATIS track semua visitor
         * Applied ke semua web routes (public pages)
         *
         * Features:
         * - Anonymous device fingerprinting
         * - Daily visitor counting (anti spam)
         * - Page views tracking
         * - Privacy-safe (IP anonymized)
         */
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisitor::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        /**
         * API Rate Limiting
         * 60 requests per minute per IP untuk prevent abuse
         * Bisa diubah sesuai kebutuhan di .env: THROTTLE_LIMIT=60
         */
        $middleware->throttleApi('60,1'); // 60 requests per minute
    })
    /**
     * Exception Handling
     * Custom error handling untuk API routes
     */
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->is('api/*')) {
                return \App\Http\Responses\ApiResponse::error(
                    $e->getMessage(),
                    422,
                    $e->errors()
                );
            }
        });

        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                return \App\Http\Responses\ApiResponse::error(
                    'Resource not found',
                    404
                );
            }
        });

        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return \App\Http\Responses\ApiResponse::error(
                    'Unauthenticated',
                    401
                );
            }
        });

        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->is('api/*')) {
                $code = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                $message = $e->getMessage() ?: 'Internal Server Error';
                
                if ($code === 500 && !config('app.debug')) {
                    $message = 'An unexpected error occurred.';
                }
                
                return \App\Http\Responses\ApiResponse::error($message, $code);
            }
        });
    })->create();
