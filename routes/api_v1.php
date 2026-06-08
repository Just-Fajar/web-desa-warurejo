<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BeritaController;
use App\Http\Controllers\Api\V1\GaleriController;
use App\Http\Controllers\Api\V1\PotensiController;
use App\Http\Controllers\Api\V1\ProfilDesaController;
use App\Http\Controllers\Api\V1\StatistikController;
use App\Http\Controllers\Api\V1\PublikasiController;
use Illuminate\Support\Facades\Route;

// Public API Routes (No Authentication Required)
Route::group([
    'middleware' => ['api.version', 'throttle:60,1']
], function () {
    
    // Authentication (login)
    Route::post('/login', [AuthController::class, 'login']);

    // Cachable Public Read Endpoints
    Route::group([
        'middleware' => ['api.cache']
    ], function () {
        
        // Berita Routes
        Route::prefix('berita')->name('api.berita.')->group(function () {
            Route::get('/', [BeritaController::class, 'index'])->name('index');
            Route::get('/latest', [BeritaController::class, 'latest'])->name('latest');
            Route::get('/popular', [BeritaController::class, 'popular'])->name('popular');
            Route::get('/{slug}', [BeritaController::class, 'show'])->name('show');
        });

        // Potensi Routes
        Route::prefix('potensi')->name('api.potensi.')->group(function () {
            Route::get('/', [PotensiController::class, 'index'])->name('index');
            Route::get('/featured', [PotensiController::class, 'featured'])->name('featured');
            Route::get('/{slug}', [PotensiController::class, 'show'])->name('show');
        });

        // Galeri Routes
        Route::prefix('galeri')->name('api.galeri.')->group(function () {
            Route::get('/', [GaleriController::class, 'index'])->name('index');
            Route::get('/latest', [GaleriController::class, 'latest'])->name('latest');
            Route::get('/categories', [GaleriController::class, 'categories'])->name('categories');
            Route::get('/{id}', [GaleriController::class, 'show'])->name('show');
        });

        // Profil Desa Routes
        Route::get('/profil', [ProfilDesaController::class, 'show'])->name('api.profil.show');

        // Statistik Routes
        Route::get('/statistik/summary', [StatistikController::class, 'summary'])->name('api.statistik.summary');

        // Publikasi Routes
        Route::prefix('publikasi')->name('api.publikasi.')->group(function () {
            Route::get('/', [PublikasiController::class, 'index'])->name('index');
            Route::get('/categories', [PublikasiController::class, 'categories'])->name('categories');
            Route::get('/years', [PublikasiController::class, 'years'])->name('years');
            Route::get('/{id}', [PublikasiController::class, 'show'])->name('show');
        });
    });

    // Non-cachable Public Download Route
    Route::get('/publikasi/{id}/download', [PublikasiController::class, 'download'])->name('api.publikasi.download');
});

// Protected API Routes (Authentication Required)
Route::group([
    'middleware' => ['auth:sanctum', 'api.version', 'throttle:60,1']
], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::post('/logout-all', [AuthController::class, 'logoutAll'])->name('api.auth.logout_all');
    Route::get('/me', [AuthController::class, 'me'])->name('api.auth.me');
    Route::get('/tokens', [AuthController::class, 'tokens'])->name('api.auth.tokens');
});
