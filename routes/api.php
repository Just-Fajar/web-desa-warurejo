<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\PotensiController;
use App\Http\Controllers\Api\GaleriController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API Routes (No Authentication Required)
Route::prefix('v1')->group(function () {
    
    // Authentication
    Route::post('/login', [AuthController::class, 'login']);
    
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
});

// Protected API Routes (Authentication Required)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // Auth Management
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/tokens', [AuthController::class, 'tokens']);
});
