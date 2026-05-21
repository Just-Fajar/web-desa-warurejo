<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GaleriController as AdminGaleriController;
use App\Http\Controllers\Admin\PengaduanController as AdminPengaduanController;
use App\Http\Controllers\Admin\PotensiController as AdminPotensiController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\PublikasiController as AdminPublikasiController;
use App\Http\Controllers\Admin\StrukturOrganisasiController;
use App\Http\Controllers\Public\BeritaController;
use App\Http\Controllers\Public\GaleriController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\KontakController;
use App\Http\Controllers\Public\PengaduanController;
use App\Http\Controllers\Public\PotensiController;
use App\Http\Controllers\Public\ProfilController;
use App\Http\Controllers\PublikasiController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

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

// Pengaduan Publik
Route::prefix('pengaduan')->name('pengaduan.')->group(function () {
    Route::get('/', [PengaduanController::class, 'index'])->name('index');
    Route::get('/{id}', [PengaduanController::class, 'show'])->name('show');
    Route::post('/', [PengaduanController::class, 'store'])->name('store');
});

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
    Route::middleware(['admin', 'auto.publish'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Auto-publish endpoint for real-time polling (AJAX)
        Route::post('/auto-publish', function () {
            $totalPublished = 0;

            try {
                $models = [
                    \App\Models\Berita::class => ['home.latest_berita', 'berita.published', 'home.total_berita'],
                    \App\Models\Galeri::class => ['home.galeri', 'home.total_galeri'],
                    \App\Models\PotensiDesa::class => ['home.potensi', 'home.total_potensi'],
                    \App\Models\Publikasi::class => ['home.publikasi'],
                ];

                foreach ($models as $model => $cacheKeys) {
                    $count = $model::dueForPublishing()->count();
                    if ($count > 0) {
                        $model::dueForPublishing()->update(['status' => 'published']);
                        $totalPublished += $count;
                        foreach ($cacheKeys as $key) {
                            \Illuminate\Support\Facades\Cache::forget($key);
                        }
                    }
                }

                if ($totalPublished > 0) {
                    \Illuminate\Support\Facades\Cache::forget('profil_desa');
                    \Illuminate\Support\Facades\Cache::forget('home.seo_data');
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Auto-publish error: '.$e->getMessage());
            }

            return response()->json([
                'published' => $totalPublished,
                'timestamp' => now()->format('H:i:s'),
            ]);
        })->name('auto-publish');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/visitor-chart', [DashboardController::class, 'getVisitorChartByYear'])->name('dashboard.visitor-chart');
        Route::get('/dashboard/content-chart', [DashboardController::class, 'getContentChartByYear'])->name('dashboard.content-chart');
        Route::get('/dashboard/visitor-period', [DashboardController::class, 'getVisitorsByPeriod'])->name('dashboard.visitor-period');

        // Berita Management
        Route::post('berita/bulk-delete', [AdminBeritaController::class, 'bulkDelete'])->name('berita.bulk-delete');
        Route::resource('berita', AdminBeritaController::class);

        // Potensi Management
        Route::post('potensi/bulk-delete', [AdminPotensiController::class, 'bulkDelete'])->name('potensi.bulk-delete');
        Route::delete('potensi/foto/{id}', [AdminPotensiController::class, 'deleteFoto'])->name('potensi.foto.delete');
        Route::resource('potensi', AdminPotensiController::class);

        // Galeri Management
        Route::post('galeri/bulk-delete', [AdminGaleriController::class, 'bulkDelete'])->name('galeri.bulk-delete');
        Route::resource('galeri', AdminGaleriController::class);

        // Publikasi Management
        Route::post('publikasi/bulk-delete', [AdminPublikasiController::class, 'bulkDelete'])->name('publikasi.bulk-delete');
        Route::resource('publikasi', AdminPublikasiController::class);

        // Struktur Organisasi Management
        Route::post('struktur-organisasi/bulk-delete', [StrukturOrganisasiController::class, 'bulkDelete'])->name('struktur-organisasi.bulk-delete');
        Route::resource('struktur-organisasi', StrukturOrganisasiController::class);

        // Pengaduan Management
        Route::post('pengaduan/bulk-delete', [AdminPengaduanController::class, 'bulkDelete'])->name('pengaduan.bulk-delete');
        Route::get('pengaduan', [AdminPengaduanController::class, 'index'])->name('pengaduan.index');
        Route::get('pengaduan/{id}', [AdminPengaduanController::class, 'show'])->name('pengaduan.show');
        Route::post('pengaduan/{id}/balas', [AdminPengaduanController::class, 'storeBalasan'])->name('pengaduan.balas');
        Route::delete('pengaduan/{id}', [AdminPengaduanController::class, 'destroy'])->name('pengaduan.destroy');

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
