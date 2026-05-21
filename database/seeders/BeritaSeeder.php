<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Berita;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        if (! Storage::disk('public')->exists('berita')) {
            Storage::disk('public')->makeDirectory('berita');
        }

        $admin = Admin::first();
        if (! $admin) {
            $this->command->error('Admin tidak ditemukan!');

            return;
        }

        $this->command->info('📰 Membuat 30 berita dummy...');
        $now = Carbon::now();

        // [judul, days_ago, status] - 20 published, 5 draft, 5 scheduled
        $items = [
            ['Musyawarah Desa Membahas Program Kerja Tahun 2026', 2, 'published'],
            ['Pembangunan Jalan Desa Tahap II Telah Dimulai', 5, 'published'],
            ['Posyandu Balita Rutin Dilaksanakan Setiap Bulan', 7, 'published'],
            ['Pelatihan UMKM untuk Meningkatkan Ekonomi Warga', 10, 'published'],
            ['Gotong Royong Membersihkan Selokan Desa', 12, 'published'],
            ['Penyaluran BLT kepada Warga Kurang Mampu', 15, 'published'],
            ['Festival Budaya Desa Meriahkan HUT RI ke-81', 18, 'published'],
            ['Vaksinasi COVID-19 Booster untuk Lansia', 22, 'published'],
            ['Lomba Desa Tingkat Kecamatan Raih Juara Harapan', 25, 'published'],
            ['Pembentukan Kelompok Tani Muda Generasi Milenial', 28, 'published'],
            ['Program Bantuan Benih Padi Unggulan untuk Petani', 31, 'published'],
            ['Sosialisasi Pencegahan Stunting di Posyandu', 35, 'published'],
            ['Pembangunan Gedung PAUD Baru Resmi Dimulai', 38, 'published'],
            ['Peringatan Hari Kemerdekaan dengan Berbagai Lomba', 42, 'published'],
            ['Penyemprotan Disinfektan di Fasilitas Umum', 45, 'published'],
            ['Pelatihan Digital Marketing untuk Pelaku UMKM', 50, 'published'],
            ['Donor Darah Rutin Bekerjasama dengan PMI', 55, 'published'],
            ['Renovasi Balai Desa untuk Pelayanan Lebih Baik', 60, 'published'],
            ['Bantuan Bibit Tanaman untuk Program Penghijauan', 65, 'published'],
            ['Launching Website Profil Desa Warurejo', 70, 'published'],
            // DRAFT (5)
            ['Rencana Pembangunan Taman Bermain Anak', 3, 'draft'],
            ['Proposal Pengadaan Ambulans Desa', 5, 'draft'],
            ['Konsep Program Desa Wisata Warurejo', 2, 'draft'],
            ['Rancangan Perpustakaan Digital Desa', 4, 'draft'],
            ['Studi Kelayakan Pasar Desa Modern', 6, 'draft'],
            // SCHEDULED (5)
            ['Jadwal Kerja Bakti Massal Bulan Juni', -7, 'scheduled'],
            ['Pengumuman Penerimaan Perangkat Desa Baru', -10, 'scheduled'],
            ['Launching Program Smart Village', -14, 'scheduled'],
            ['Peringatan Hari Jadi Desa ke-150', -21, 'scheduled'],
            ['Festival Pangan Lokal Desa Warurejo', -30, 'scheduled'],
        ];

        foreach ($items as $i => $item) {
            [$judul, $days, $status] = $item;

            $publishedAt = match ($status) {
                'published' => $now->copy()->subDays($days),
                'scheduled' => $now->copy()->addDays(abs($days)),
                default => null,
            };

            $img = $this->downloadImage('berita', "berita-{$i}.jpg", 800, 600, 100 + $i);

            Berita::create([
                'admin_id' => $admin->id,
                'judul' => $judul,
                'slug' => Str::slug($judul).'-'.Str::random(5),
                'ringkasan' => $this->ringkasan($judul),
                'konten' => $this->konten($judul),
                'gambar_utama' => $img,
                'status' => $status,
                'views' => $status === 'published' ? rand(15, 750) : 0,
                'published_at' => $publishedAt,
                'created_at' => $publishedAt ?? $now->copy()->subDays($days),
                'updated_at' => $publishedAt ?? $now,
            ]);
            $icon = match ($status) {
                'published' => '✅', 'draft' => '📝', 'scheduled' => '⏰'
            };
            $this->command->info("  {$icon} [{$status}] {$judul}");
        }
        $this->command->info('✅ 30 berita: 20 published, 5 draft, 5 scheduled');
    }

    private function downloadImage(string $folder, string $fn, int $w, int $h, int $seed): ?string
    {
        try {
            $r = Http::timeout(15)->get("https://picsum.photos/seed/{$seed}/{$w}/{$h}");
            if ($r->successful()) {
                $p = "{$folder}/{$fn}";
                Storage::disk('public')->put($p, $r->body());

                return $p;
            }
        } catch (\Exception $e) {
            $this->command->warn("⚠ {$e->getMessage()}");
        }

        return "{$folder}/{$fn}";
    }

    private function ringkasan(string $j): string
    {
        $t = [
            "Kegiatan {$j} dilaksanakan dengan antusias oleh seluruh warga Desa Warurejo.",
            "Pemerintah Desa mengadakan {$j} sebagai upaya pemberdayaan masyarakat.",
            "{$j} merupakan salah satu program prioritas tahun ini.",
        ];

        return $t[array_rand($t)];
    }

    private function konten(string $j): string
    {
        $p = '<p><strong>Desa Warurejo</strong> - '.$this->ringkasan($j).' Program ini bertujuan meningkatkan kesejahteraan masyarakat.</p>';
        $p .= '<p>Kegiatan ini dihadiri oleh Kepala Desa beserta perangkat desa, tokoh masyarakat, serta perwakilan organisasi kemasyarakatan.</p>';
        $p .= '<p>Pemerintah desa mengalokasikan anggaran khusus dari APBDes untuk mendukung program ini. Transparansi penggunaan anggaran menjadi prioritas utama.</p>';
        $p .= '<p>Kegiatan ditutup dengan doa bersama. Semoga program ini memberikan manfaat maksimal bagi Desa Warurejo.</p>';

        return $p;
    }
}
