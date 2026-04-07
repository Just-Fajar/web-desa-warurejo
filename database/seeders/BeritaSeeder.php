<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan folder storage ada
        if (!Storage::disk('public')->exists('berita')) {
            Storage::disk('public')->makeDirectory('berita');
        }

        // Get admin untuk foreign key
        $admin = Admin::first();
        
        if (!$admin) {
            $this->command->error('Admin tidak ditemukan. Jalankan AdminSeeder terlebih dahulu.');
            return;
        }

        $statusOptions = ['draft', 'published'];

        // Array judul berita yang realistis
        $judulBerita = [
            'Musyawarah Desa Membahas Program Kerja Tahun 2025',
            'Pembangunan Jalan Desa Tahap II Telah Dimulai',
            'Posyandu Balita Rutin Dilaksanakan Setiap Bulan',
            'Pelatihan UMKM untuk Meningkatkan Ekonomi Warga',
            'Gotong Royong Membersihkan Selokan Desa',
            'Penyaluran Bantuan Langsung Tunai kepada Warga Kurang Mampu',
            'Festival Budaya Desa Meriahkan HUT RI ke-80',
            'Vaksinasi COVID-19 Booster untuk Lansia',
            'Lomba Desa Tingkat Kecamatan Raih Juara Harapan',
            'Pembentukan Kelompok Tani Muda untuk Generasi Milenial',
            'Launching Website Profil Desa Warurejo',
            'Pengadaan Alat Pertanian Modern untuk Petani',
            'Sosialisasi Pencegahan Stunting di Posyandu',
            'Pembangunan PAUD Baru untuk Pendidikan Anak Usia Dini',
            'Peringatan Hari Kemerdekaan dengan Berbagai Lomba',
            'Penyemprotan Disinfektan di Fasilitas Umum',
            'Pelatihan Digital Marketing untuk Pelaku UMKM',
            'Donor Darah Rutin Bekerjasama dengan PMI',
            'Renovasi Balai Desa untuk Pelayanan Lebih Baik',
            'Bantuan Bibit Tanaman untuk Program Penghijauan'
        ];

        $this->command->info('Membuat 20 berita dummy...');

        foreach ($judulBerita as $index => $judul) {
            $status = $index < 15 ? 'published' : 'draft'; // 15 published, 5 draft
            $publishedAt = $status === 'published' 
                ? Carbon::now()->subDays(rand(1, 90)) 
                : null;

            // Generate paragraf yang realistis
            $paragraphs = $this->generateParagraphs($judul, rand(3, 6));
            
            Berita::create([
                'admin_id' => $admin->id,
                'judul' => $judul,
                'slug' => Str::slug($judul) . '-' . Str::random(5),
                'ringkasan' => $this->generateRingkasan($judul),
                'konten' => $paragraphs,
                'gambar_utama' => 'berita/dummy-' . ($index + 1) . '.jpg', // Dummy path
                'status' => $status,
                'views' => rand(10, 500),
                'published_at' => $publishedAt,
                'created_at' => $publishedAt ?? Carbon::now(),
                'updated_at' => $publishedAt ?? Carbon::now(),
            ]);

            $this->command->info('✓ Berita ' . ($index + 1) . ': ' . $judul);
        }

        $this->command->info('✅ Berhasil membuat 20 berita dummy!');
    }

    /**
     * Generate ringkasan dari judul
     */
    private function generateRingkasan($judul): string
    {
        $templates = [
            "Kegiatan $judul dilaksanakan dengan antusias oleh seluruh warga desa. Program ini bertujuan untuk meningkatkan kesejahteraan masyarakat dan pembangunan desa yang berkelanjutan.",
            "Pemerintah Desa mengadakan $judul sebagai upaya pemberdayaan masyarakat. Kegiatan ini mendapat sambutan positif dari berbagai kalangan warga.",
            "Dalam rangka $judul, pemerintah desa mengundang seluruh elemen masyarakat untuk berpartisipasi aktif. Program ini diharapkan dapat memberikan manfaat jangka panjang.",
            "Pelaksanaan $judul berjalan lancar dan sesuai rencana. Antusiasme warga sangat tinggi dalam mendukung program pembangunan desa.",
            "$judul merupakan salah satu program prioritas tahun ini. Kegiatan ini melibatkan berbagai pihak termasuk tokoh masyarakat dan karang taruna.",
        ];

        return $templates[array_rand($templates)];
    }

    /**
     * Generate konten paragraf realistis
     */
    private function generateParagraphs($judul, $count): string
    {
        $paragraphs = [];

        // Paragraf pembuka
        $paragraphs[] = "<p><strong>Desa Warurejo</strong> - " . $this->generateRingkasan($judul) . "</p>";

        // Paragraf isi
        $kontenTemplates = [
            "<p>Kegiatan ini dihadiri oleh Kepala Desa beserta perangkat desa, tokoh masyarakat, serta perwakilan dari berbagai organisasi kemasyarakatan. Dalam sambutannya, Kepala Desa menyampaikan pentingnya partisipasi aktif seluruh warga dalam pembangunan desa.</p>",
            
            "<p>Menurut keterangan Sekretaris Desa, program ini merupakan bagian dari upaya pemerintah desa dalam meningkatkan kualitas pelayanan kepada masyarakat. \"Kami berkomitmen untuk terus berinovasi dan memberikan yang terbaik bagi warga desa,\" ujarnya.</p>",
            
            "<p>Para peserta terlihat sangat antusias mengikuti kegiatan dari awal hingga akhir. Berbagai pertanyaan dan masukan disampaikan untuk perbaikan program ke depannya. Hal ini menunjukkan tingginya kepedulian masyarakat terhadap pembangunan desa.</p>",
            
            "<p>Pemerintah desa mengalokasikan anggaran khusus untuk mendukung terlaksananya program ini. Dana berasal dari APBDes yang telah disetujui melalui musyawarah desa. Transparansi penggunaan anggaran menjadi prioritas utama dalam pelaksanaan program.</p>",
            
            "<p>Kegiatan dilaksanakan di Balai Desa Warurejo dengan menerapkan protokol kesehatan yang ketat. Panitia menyediakan hand sanitizer, masker, dan mengatur jarak tempat duduk untuk memastikan keamanan dan kenyamanan peserta.</p>",
            
            "<p>Beberapa warga menyampaikan apresiasi yang tinggi terhadap inisiatif pemerintah desa. Mereka berharap program serupa dapat terus dilaksanakan secara berkala untuk kemajuan bersama.</p>",
            
            "<p>Kedepannya, pemerintah desa akan terus menggali potensi lokal dan memberdayakan masyarakat. Kolaborasi dengan berbagai pihak terus diperkuat untuk mewujudkan visi desa yang mandiri dan sejahtera.</p>",
            
            "<p>Tim dokumentasi desa mengabadikan seluruh rangkaian kegiatan untuk arsip dan publikasi di website resmi desa. Transparansi informasi menjadi komitmen pemerintah desa dalam menjalankan tata kelola pemerintahan yang baik.</p>",
        ];

        // Ambil paragraf secara random
        $selectedParagraphs = array_rand($kontenTemplates, min($count - 2, count($kontenTemplates)));
        if (!is_array($selectedParagraphs)) {
            $selectedParagraphs = [$selectedParagraphs];
        }

        foreach ($selectedParagraphs as $index) {
            $paragraphs[] = $kontenTemplates[$index];
        }

        // Paragraf penutup
        $penutup = [
            "<p>Kegiatan ditutup dengan doa bersama dan foto bersama seluruh peserta. Semoga program ini dapat memberikan manfaat yang maksimal bagi kemajuan Desa Warurejo.</p>",
            "<p>Pemerintah Desa mengucapkan terima kasih kepada seluruh pihak yang telah mendukung terlaksananya kegiatan ini. Mari bersama-sama membangun desa yang lebih baik.</p>",
            "<p>Dengan selesainya kegiatan ini, diharapkan dapat membawa dampak positif bagi masyarakat. Pemerintah desa akan terus melakukan monitoring dan evaluasi untuk perbaikan di masa mendatang.</p>",
        ];

        $paragraphs[] = $penutup[array_rand($penutup)];

        return implode("\n\n", $paragraphs);
    }
}
