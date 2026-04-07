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
     * Custom error handling bisa ditambahkan disini
     */
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
