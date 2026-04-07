<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\BeritaController;
use App\Http\Controllers\Public\ProfilController;
use App\Http\Controllers\Public\PotensiController;
use App\Http\Controllers\Public\GaleriController;
use App\Http\Controllers\Public\KontakController;
use App\Http\Controllers\PublikasiController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Admin\PotensiController as AdminPotensiController;
use App\Http\Controllers\Admin\GaleriController as AdminGaleriController;
use App\Http\Controllers\Admin\ProfilDesaController;
use App\Http\Controllers\Admin\PublikasiController as AdminPublikasiController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\StrukturOrganisasiController;
use App\Http\Controllers\SitemapController;

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/', [ProfilController::class, 'index'])->name('index');
    Route::get('/visi-misi', [ProfilController::class, 'visiMisi'])->name('visi-misi');
    Route::get('/sejarah', [ProfilController::class, 'sejarah'])->name('sejarah');
    Route::get('/struktur-organisasi', [ProfilController::class, 'strukturOrganisasi'])->name('struktur-organisasi');
});

Route::prefix('berita')->name('berita.')->group(function () {
    Route::get('/', [BeritaController::class, 'index'])->name('index');
    Route::get('/autocomplete', [BeritaController::class, 'autocomplete'])->name('autocomplete');
    Route::get('/{slug}', [BeritaController::class, 'show'])->name('show');
});

Route::prefix('potensi')->name('potensi.')->group(function () {
    Route::get('/', [PotensiController::class, 'index'])->name('index');
    Route::get('/{slug}', [PotensiController::class, 'show'])->name('show');
});

Route::prefix('galeri')->name('galeri.')->group(function () {
    Route::get('/', [GaleriController::class, 'index'])->name('index');
});

Route::prefix('kontak')->name('kontak.')->group(function () {
    Route::get('/', [KontakController::class, 'index'])->name('index');
});

// Publikasi Routes
Route::prefix('publikasi')->name('publikasi.')->group(function () {
    Route::get('/', [PublikasiController::class, 'index'])->name('index');
    Route::get('/{id}', [PublikasiController::class, 'show'])->name('show');
    Route::get('/{id}/download', [PublikasiController::class, 'download'])->name('download');
});

// Peta Desa
Route::get('/peta-desa', function () {
    return view('public.peta-desa');
})->name('peta-desa');

// Tribute KKN 24
Route::get('/tribute-kkn24', function () {
    return view('public.tribute-kkn24');
})->name('tribute.kkn24');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Routes (Not Authenticated)
    Route::middleware('admin.guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])
            ->middleware('throttle:5,1') // 5 attempts per minute
            ->name('login.post');
    });

    // Authenticated Routes
    Route::middleware('admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/visitor-chart', [DashboardController::class, 'getVisitorChartByYear'])->name('dashboard.visitor-chart');
        Route::get('/dashboard/content-chart', [DashboardController::class, 'getContentChartByYear'])->name('dashboard.content-chart');
        
        // Berita Management
        Route::post('berita/bulk-delete', [AdminBeritaController::class, 'bulkDelete'])->name('berita.bulk-delete');
        Route::resource('berita', AdminBeritaController::class);
        
        // Potensi Management
        Route::post('potensi/bulk-delete', [AdminPotensiController::class, 'bulkDelete'])->name('potensi.bulk-delete');
        Route::resource('potensi', AdminPotensiController::class);
        
        // Galeri Management
        Route::post('galeri/bulk-delete', [AdminGaleriController::class, 'bulkDelete'])->name('galeri.bulk-delete');
        Route::post('galeri/{galeri}/toggle-active', [AdminGaleriController::class, 'toggleActive'])->name('galeri.toggle-active');
        Route::resource('galeri', AdminGaleriController::class);
        
        // Publikasi Management
        Route::post('publikasi/bulk-delete', [AdminPublikasiController::class, 'bulkDelete'])->name('publikasi.bulk-delete');
        Route::resource('publikasi', AdminPublikasiController::class);
        
        // Struktur Organisasi Management
        Route::post('struktur-organisasi/bulk-delete', [StrukturOrganisasiController::class, 'bulkDelete'])->name('struktur-organisasi.bulk-delete');
        Route::resource('struktur-organisasi', StrukturOrganisasiController::class);
        
        // Profil Desa Management
        Route::prefix('profil-desa')->name('profil-desa.')->group(function () {
            Route::get('/', [ProfilDesaController::class, 'edit'])->name('edit');
            Route::put('/', [ProfilDesaController::class, 'update'])->name('update');
        });
        
        // Admin Profile Management
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [AdminProfileController::class, 'show'])->name('show');
            Route::get('/edit', [AdminProfileController::class, 'edit'])->name('edit');
            Route::put('/update', [AdminProfileController::class, 'update'])->name('update');
            Route::post('/photo', [AdminProfileController::class, 'updatePhoto'])->name('update-photo');
            Route::delete('/photo', [AdminProfileController::class, 'deletePhoto'])->name('delete-photo');
            Route::put('/password', [AdminProfileController::class, 'updatePassword'])->name('update-password');
        });
    });
});