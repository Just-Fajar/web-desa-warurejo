<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Galeri;
use App\Models\GaleriImage;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GaleriSeeder extends Seeder
{
    public function run(): void
    {
        if (! Storage::disk('public')->exists('galeri')) {
            Storage::disk('public')->makeDirectory('galeri');
        }
        $admin = Admin::first();
        if (! $admin) {
            $this->command->error('Admin tidak ditemukan!');

            return;
        }

        $this->command->info('📸 Membuat 30 galeri dummy...');
        $now = Carbon::now();

        // 5 per kategori × 6 = 30. Distribution per kategori: 3 pub, 1 draft, 1 sched
        $items = [
            // KEGIATAN (5)
            ['Musyawarah Desa Pembahasan APBDes 2026', 'kegiatan', 'Musyawarah desa dihadiri seluruh perangkat desa dan BPD.', 25, 'published'],
            ['Gotong Royong Membersihkan Lingkungan', 'kegiatan', 'Warga bergotong royong membersihkan selokan dan lingkungan desa.', 20, 'published'],
            ['Posyandu Balita Rutin Bulanan', 'kegiatan', 'Kegiatan posyandu rutin memantau tumbuh kembang balita.', 15, 'published'],
            ['Pelatihan Kader Posyandu', 'kegiatan', 'Kader posyandu mengikuti pelatihan meningkatkan kualitas pelayanan.', 5, 'draft'],
            ['Rapat Koordinasi RT/RW Triwulan', 'kegiatan', 'Koordinasi rutin Kepala Desa dengan RT/RW membahas program kerja.', 7, 'scheduled'],

            // PEMBANGUNAN (5)
            ['Pembangunan Jalan Desa Tahap II', 'pembangunan', 'Pengaspalan jalan desa sepanjang 2 km untuk akses transportasi.', 22, 'published'],
            ['Renovasi Balai Desa', 'pembangunan', 'Renovasi total balai desa meningkatkan kualitas pelayanan.', 18, 'published'],
            ['Pembangunan Gedung PAUD Baru', 'pembangunan', 'Konstruksi gedung PAUD baru dengan fasilitas lengkap.', 12, 'published'],
            ['Pembangunan MCK Umum Pasar Desa', 'pembangunan', 'Pembangunan fasilitas MCK umum di area pasar desa.', 3, 'draft'],
            ['Pembuatan Drainase Jalan Utama', 'pembangunan', 'Pembuatan drainase sepanjang jalan utama mencegah genangan.', 10, 'scheduled'],

            // BUDAYA (5)
            ['Festival Budaya Desa Warurejo', 'budaya', 'Festival budaya menampilkan seni tari tradisional dan wayang kulit.', 24, 'published'],
            ['Pawai Budaya Nusantara', 'budaya', 'Pawai budaya menampilkan kostum tradisional berbagai daerah.', 16, 'published'],
            ['Pertunjukan Wayang Kulit', 'budaya', 'Pertunjukan wayang kulit semalam suntuk dengan dalang kondang.', 8, 'published'],
            ['Latihan Tari Tradisional Anak', 'budaya', 'Anak-anak desa belajar tari tradisional melestarikan budaya.', 4, 'draft'],
            ['Persiapan Festival Seni Rakyat', 'budaya', 'Persiapan festival seni rakyat menampilkan kesenian lokal.', 14, 'scheduled'],

            // KEAGAMAAN (5)
            ['Peringatan Isra Miraj Nabi Muhammad SAW', 'keagamaan', 'Peringatan Isra Miraj dengan ceramah agama dan doa bersama.', 21, 'published'],
            ['Santunan Anak Yatim Bulan Ramadhan', 'keagamaan', 'Santunan untuk 50 anak yatim piatu menjelang Ramadhan.', 13, 'published'],
            ['Pengajian Akbar Bulanan', 'keagamaan', 'Pengajian akbar bulanan mengundang ustadz dari pondok pesantren.', 6, 'published'],
            ['Persiapan Perayaan Natal Bersama', 'keagamaan', 'Persiapan perayaan Natal bersama warga Kristiani di gereja desa.', 2, 'draft'],
            ['Kegiatan Tadarus Al-Quran Ramadhan', 'keagamaan', 'Jadwal tadarus Al-Quran bersama selama bulan Ramadhan.', 21, 'scheduled'],

            // SOSIAL (5)
            ['Bakti Sosial Donor Darah PMI', 'sosial', 'Bakti sosial donor darah bersama PMI Kabupaten diikuti 80 pendonor.', 19, 'published'],
            ['Pembagian Sembako Warga Kurang Mampu', 'sosial', 'Pembagian paket sembako untuk 100 KK warga kurang mampu.', 11, 'published'],
            ['Penyuluhan Bahaya Narkoba Pemuda', 'sosial', 'Penyuluhan bahaya narkoba untuk pemuda bersama BNN Kabupaten.', 4, 'published'],
            ['Simulasi Tanggap Bencana Alam', 'sosial', 'Simulasi dan pelatihan tanggap bencana untuk relawan desa.', 3, 'draft'],
            ['Kampanye Anti Bullying Sekolah', 'sosial', 'Kampanye kesadaran anti-bullying di sekolah lingkungan desa.', 30, 'scheduled'],

            // LAINNYA (5)
            ['Dokumentasi Keindahan Alam Desa', 'lainnya', 'Koleksi foto keindahan alam Desa Warurejo dari berbagai sudut.', 28, 'published'],
            ['Panorama Sawah Menghijau', 'lainnya', 'Pemandangan sawah hijau yang membentang luas di Desa Warurejo.', 17, 'published'],
            ['Suasana Pasar Tradisional Desa', 'lainnya', 'Dokumentasi suasana pasar tradisional desa yang ramai pengunjung.', 9, 'published'],
            ['Koleksi Foto Fauna Desa', 'lainnya', 'Dokumentasi berbagai fauna yang hidup di lingkungan desa.', 2, 'draft'],
            ['Foto Udara Desa Warurejo', 'lainnya', 'Rencana dokumentasi foto udara drone seluruh wilayah desa.', 15, 'scheduled'],
        ];

        foreach ($items as $i => $item) {
            [$judul, $kat, $desk, $days, $status] = $item;
            $pa = match ($status) {
                'published' => $now->copy()->subDays($days),
                'scheduled' => $now->copy()->addDays($days),
                default => null,
            };
            $tgl = $pa ?? $now->copy()->subDays($days);
            $img = $this->dl('galeri', "galeri-{$i}.jpg", 800, 600, 300 + $i);

            $galeri = Galeri::create([
                'admin_id' => $admin->id, 'judul' => $judul, 'deskripsi' => $desk,
                'gambar' => $img, 'kategori' => $kat, 'tanggal' => $tgl,
                'status' => $status, 'published_at' => $pa,
                'views' => $status === 'published' ? rand(5, 200) : 0,
                'created_at' => $tgl, 'updated_at' => $tgl,
            ]);

            // 2-3 extra images
            for ($j = 0; $j < rand(2, 3); $j++) {
                $ep = $this->dl('galeri', "galeri-{$i}-extra-{$j}.jpg", 800, 600, 400 + ($i * 4) + $j);
                if ($ep) {
                    GaleriImage::create(['galeri_id' => $galeri->id, 'image_path' => $ep, 'urutan' => $j + 1]);
                }
            }

            $icon = match ($status) {
                'published' => '✅', 'draft' => '📝', 'scheduled' => '⏰'
            };
            $this->command->info("  {$icon} [{$kat}] {$judul}");
        }
        $this->command->info('✅ 30 galeri: 6 kategori × 5 (18 pub, 6 draft, 6 sched)');
    }

    private function dl(string $f, string $fn, int $w, int $h, int $s): ?string
    {
        try {
            $r = Http::timeout(15)->get("https://picsum.photos/seed/{$s}/{$w}/{$h}");
            if ($r->successful()) {
                $p = "{$f}/{$fn}";
                Storage::disk('public')->put($p, $r->body());

                return $p;
            }
        } catch (\Exception $e) {
        }

        return "{$f}/{$fn}";
    }
}
