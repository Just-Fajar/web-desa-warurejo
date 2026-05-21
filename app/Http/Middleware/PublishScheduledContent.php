<?php

namespace App\Http\Middleware;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\PotensiDesa;
use App\Models\Publikasi;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Middleware Auto-Publish Scheduled Content
 *
 * Fallback mechanism untuk publish konten yang dijadwalkan
 * jika scheduler (php artisan schedule:work) tidak berjalan.
 *
 * Dijalankan setiap kali admin mengakses halaman admin,
 * tapi di-throttle max 1x per menit via cache untuk performa.
 */
class PublishScheduledContent
{
    public function handle(Request $request, Closure $next)
    {
        // Throttle: hanya jalankan sekali per menit
        if (! Cache::has('auto_publish_last_check')) {
            $this->publishDueContent();
            Cache::put('auto_publish_last_check', true, 60); // 60 detik
        }

        return $next($request);
    }

    protected function publishDueContent(): void
    {
        $totalPublished = 0;

        try {
            // 1. Berita
            $beritaCount = Berita::dueForPublishing()->count();
            if ($beritaCount > 0) {
                Berita::dueForPublishing()->update(['status' => Berita::STATUS_PUBLISHED]);
                $totalPublished += $beritaCount;
                Cache::forget('home.latest_berita');
                Cache::forget('berita.published');
                Cache::forget('home.total_berita');
            }

            // 2. Galeri
            $galeriCount = Galeri::dueForPublishing()->count();
            if ($galeriCount > 0) {
                Galeri::dueForPublishing()->update(['status' => Galeri::STATUS_PUBLISHED]);
                $totalPublished += $galeriCount;
                Cache::forget('home.galeri');
                Cache::forget('home.total_galeri');
            }

            // 3. Potensi Desa
            $potensiCount = PotensiDesa::dueForPublishing()->count();
            if ($potensiCount > 0) {
                PotensiDesa::dueForPublishing()->update(['status' => PotensiDesa::STATUS_PUBLISHED]);
                $totalPublished += $potensiCount;
                Cache::forget('home.potensi');
                Cache::forget('home.total_potensi');
            }

            // 4. Publikasi
            $publikasiCount = Publikasi::dueForPublishing()->count();
            if ($publikasiCount > 0) {
                Publikasi::dueForPublishing()->update(['status' => Publikasi::STATUS_PUBLISHED]);
                $totalPublished += $publikasiCount;
                Cache::forget('home.publikasi');
            }

            if ($totalPublished > 0) {
                Cache::forget('profil_desa');
                Cache::forget('home.seo_data');
                Log::info("Auto-publish middleware: {$totalPublished} konten berhasil dipublish secara otomatis.");
            }
        } catch (\Exception $e) {
            Log::error('Auto-publish middleware error: '.$e->getMessage());
        }
    }
}
