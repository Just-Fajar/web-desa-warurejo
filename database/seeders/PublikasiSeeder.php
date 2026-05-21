<?php

namespace Database\Seeders;

use App\Models\Publikasi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublikasiSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['publikasi', 'publikasi/thumbnails'] as $f) {
            if (! Storage::disk('public')->exists($f)) {
                Storage::disk('public')->makeDirectory($f);
            }
        }
        $this->command->info('📄 Membuat 30 publikasi dummy...');
        $now = Carbon::now();

        // [judul, kategori, tahun, deskripsi, days, status]
        $items = [
            // APBDES (10) - 7 pub, 1 draft, 2 sched
            ['APBDes Tahun 2026', 'APBDes', 2026, 'Dokumen APBDes tahun 2026 yang telah disahkan melalui Peraturan Desa.', 120, 'published'],
            ['Laporan Realisasi APBDes Semester I 2025', 'APBDes', 2025, 'Laporan realisasi anggaran semester pertama tahun 2025.', 300, 'published'],
            ['APBDes Perubahan Tahun 2025', 'APBDes', 2025, 'Dokumen APBDes perubahan tahun 2025 hasil musyawarah desa.', 260, 'published'],
            ['Laporan Realisasi APBDes Semester II 2025', 'APBDes', 2025, 'Laporan realisasi anggaran semester kedua tahun 2025.', 100, 'published'],
            ['Rincian Anggaran Bidang Pembangunan 2026', 'APBDes', 2026, 'Rincian alokasi anggaran bidang pembangunan infrastruktur.', 90, 'published'],
            ['Rincian Anggaran Bidang Pemberdayaan 2026', 'APBDes', 2026, 'Rincian alokasi anggaran bidang pemberdayaan masyarakat.', 85, 'published'],
            ['Informasi Publik Keuangan Desa 2025', 'APBDes', 2025, 'Ringkasan informasi publik pengelolaan keuangan desa 2025.', 130, 'published'],
            ['Draft APBDes Tahun 2027', 'APBDes', 2027, 'Draft awal rancangan APBDes tahun 2027 dalam pembahasan.', 5, 'draft'],
            ['Laporan Realisasi APBDes Sem I 2026', 'APBDes', 2026, 'Laporan realisasi anggaran semester pertama 2026.', 60, 'scheduled'],
            ['Pertanggungjawaban APBDes 2025', 'APBDes', 2025, 'Laporan pertanggungjawaban pelaksanaan APBDes 2025.', 14, 'scheduled'],

            // RPJMDES (10) - 7 pub, 2 draft, 1 sched
            ['RPJMDes Tahun 2025-2030', 'RPJMDes', 2025, 'Rencana Pembangunan Jangka Menengah Desa periode 2025-2030.', 480, 'published'],
            ['Peraturan Desa tentang RPJMDes 2025-2030', 'RPJMDes', 2025, 'Peraturan desa menetapkan RPJMDes 2025-2030 secara resmi.', 470, 'published'],
            ['Lampiran Matrik Program RPJMDes', 'RPJMDes', 2025, 'Matrik program kegiatan prioritas RPJMDes per bidang.', 460, 'published'],
            ['Evaluasi Tahunan RPJMDes 2025', 'RPJMDes', 2025, 'Evaluasi capaian kinerja pelaksanaan RPJMDes tahun 2025.', 125, 'published'],
            ['Data Profil Desa untuk RPJMDes', 'RPJMDes', 2025, 'Data profil desa sebagai dasar penyusunan RPJMDes.', 495, 'published'],
            ['Laporan Capaian RPJMDes 2026', 'RPJMDes', 2026, 'Laporan capaian target RPJMDes tahun kedua 2026.', 55, 'published'],
            ['Berita Acara Musrenbangdes RPJMDes', 'RPJMDes', 2025, 'Berita acara musyawarah perencanaan pembangunan desa.', 485, 'published'],
            ['Draft Perubahan RPJMDes', 'RPJMDes', 2026, 'Rancangan perubahan RPJMDes disesuaikan kondisi terkini.', 3, 'draft'],
            ['Kajian Evaluasi Tengah RPJMDes', 'RPJMDes', 2026, 'Kajian evaluasi tengah periode pelaksanaan RPJMDes.', 4, 'draft'],
            ['Monitoring RPJMDes Semester I 2026', 'RPJMDes', 2026, 'Laporan monitoring dan evaluasi RPJMDes dijadwalkan terbit.', 45, 'scheduled'],

            // RKPDES (10) - 6 pub, 2 draft, 2 sched
            ['RKPDes Tahun 2026', 'RKPDes', 2026, 'Rencana Kerja Pemerintah Desa 2026 penjabaran RPJMDes.', 160, 'published'],
            ['Peraturan Desa tentang RKPDes 2026', 'RKPDes', 2026, 'Peraturan desa menetapkan RKPDes tahun 2026 secara formal.', 155, 'published'],
            ['Daftar Usulan RKPDes 2026', 'RKPDes', 2026, 'Daftar usulan kegiatan masyarakat diakomodir dalam RKPDes.', 170, 'published'],
            ['Berita Acara Musdes RKPDes 2026', 'RKPDes', 2026, 'Berita acara musyawarah desa penyusunan RKPDes 2026.', 175, 'published'],
            ['RKPDes Tahun 2025', 'RKPDes', 2025, 'Rencana Kerja Pemerintah Desa tahun 2025 selesai dilaksanakan.', 520, 'published'],
            ['Evaluasi Pelaksanaan RKPDes 2025', 'RKPDes', 2025, 'Laporan evaluasi pelaksanaan RKPDes tahun 2025.', 75, 'published'],
            ['Draft RKPDes Tahun 2027', 'RKPDes', 2027, 'Rancangan awal RKPDes 2027 dalam tahap koordinasi.', 3, 'draft'],
            ['Usulan Masyarakat RKPDes 2027', 'RKPDes', 2027, 'Kompilasi usulan kegiatan masyarakat untuk RKPDes 2027.', 5, 'draft'],
            ['RKPDes Perubahan 2026', 'RKPDes', 2026, 'RKPDes perubahan 2026 akan ditetapkan melalui musdes.', 90, 'scheduled'],
            ['Jadwal Musdes Penyusunan RKPDes 2027', 'RKPDes', 2027, 'Jadwal pelaksanaan musyawarah desa penyusunan RKPDes 2027.', 120, 'scheduled'],
        ];

        foreach ($items as $i => $item) {
            [$judul, $kat, $thn, $desk, $days, $status] = $item;
            $pa = match ($status) {
                'published' => $now->copy()->subDays($days),
                'scheduled' => $now->copy()->addDays($days),
                default => null,
            };
            $tglPub = $pa ? $pa->format('Y-m-d') : $now->format('Y-m-d');

            // Create dummy PDF
            $pdfPath = 'publikasi/'.Str::slug($judul).'.pdf';
            Storage::disk('public')->put($pdfPath, $this->dummyPdf($judul));

            // Thumbnail
            $thumb = $this->dl('publikasi/thumbnails', "thumb-{$i}.jpg", 400, 300, 500 + $i);

            Publikasi::create([
                'judul' => $judul, 'kategori' => $kat, 'tahun' => $thn,
                'deskripsi' => $desk, 'file_dokumen' => $pdfPath,
                'thumbnail' => $thumb, 'tanggal_publikasi' => $tglPub,
                'status' => $status, 'published_at' => $pa,
                'jumlah_download' => $status === 'published' ? rand(10, 200) : 0,
                'views' => $status === 'published' ? rand(20, 300) : 0,
            ]);
            $icon = match ($status) {
                'published' => '✅', 'draft' => '📝', 'scheduled' => '⏰'
            };
            $this->command->info("  {$icon} [{$kat}] {$judul}");
        }
        $this->command->info('✅ 30 publikasi: 10 APBDes + 10 RPJMDes + 10 RKPDes');
    }

    private function dummyPdf(string $title): string
    {
        $c = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $c .= "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";
        $c .= "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] >>\nendobj\n";
        $c .= "trailer\n<< /Size 4 /Root 1 0 R >>\nstartxref\n0\n%%EOF";

        return $c;
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
