<?php

namespace App\Console\Commands;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\PotensiDesa;
use App\Models\Publikasi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PublishScheduledContent extends Command
{
    protected $signature = 'content:publish-scheduled';

    protected $description = 'Publish konten yang sudah dijadwalkan dan waktunya sudah tiba';

    public function handle(): int
    {
        $totalPublished = 0;

        // 1. Publish scheduled Berita
        $berita = Berita::dueForPublishing()->get();
        if ($berita->count() > 0) {
            Berita::dueForPublishing()->update(['status' => Berita::STATUS_PUBLISHED]);
            $totalPublished += $berita->count();
            $this->info("✅ {$berita->count()} berita dipublish");
            Cache::forget('home.latest_berita');
            Cache::forget('berita.published');
            Cache::forget('home.total_berita');
        }

        // 2. Publish scheduled Galeri
        $galeri = Galeri::dueForPublishing()->get();
        if ($galeri->count() > 0) {
            Galeri::dueForPublishing()->update(['status' => Galeri::STATUS_PUBLISHED]);
            $totalPublished += $galeri->count();
            $this->info("✅ {$galeri->count()} galeri dipublish");
            Cache::forget('home.galeri');
            Cache::forget('home.total_galeri');
        }

        // 3. Publish scheduled Potensi
        $potensi = PotensiDesa::dueForPublishing()->get();
        if ($potensi->count() > 0) {
            PotensiDesa::dueForPublishing()->update(['status' => PotensiDesa::STATUS_PUBLISHED]);
            $totalPublished += $potensi->count();
            $this->info("✅ {$potensi->count()} potensi dipublish");
            Cache::forget('home.potensi');
            Cache::forget('home.total_potensi');
        }

        // 4. Publish scheduled Publikasi
        $publikasi = Publikasi::dueForPublishing()->get();
        if ($publikasi->count() > 0) {
            Publikasi::dueForPublishing()->update(['status' => Publikasi::STATUS_PUBLISHED]);
            $totalPublished += $publikasi->count();
            $this->info("✅ {$publikasi->count()} publikasi dipublish");
            Cache::forget('home.publikasi');
        }

        if ($totalPublished > 0) {
            Cache::forget('profil_desa');
            Cache::forget('home.seo_data');
            Log::info("Auto-publish: {$totalPublished} konten berhasil dipublish secara otomatis.");
            $this->info("Total: {$totalPublished} konten berhasil dipublish.");
        } else {
            $this->line('Tidak ada konten yang perlu dipublish saat ini.');
        }

        return self::SUCCESS;
    }
}
