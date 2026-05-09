<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;
use App\Models\PotensiDesa;
use App\Models\Galeri;
use App\Models\GaleriImage;
use App\Models\Publikasi;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    /**
     * Seeder utama untuk membuat data dummy LENGKAP dengan gambar nyata.
     * 
     */
    public function run(): void
    {
        $admin = Admin::first();
        if (!$admin) {
            $this->command->error('❌ Admin tidak ditemukan! Jalankan AdminSeeder terlebih dahulu.');
            return;
        }

        $this->command->info('🚀 Memulai pembuatan data dummy lengkap dengan gambar...');
        $this->command->newLine();

        // Pastikan folder storage ada
        $folders = ['berita', 'potensi', 'galeri', 'publikasi', 'publikasi/thumbnails'];
        foreach ($folders as $folder) {
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder);
            }
        }

        // 1. Seed Berita
        $this->seedBerita($admin);
        $this->command->newLine();

        // 2. Seed Potensi
        $this->seedPotensi();
        $this->command->newLine();

        // 3. Seed Galeri
        $this->seedGaleri($admin);
        $this->command->newLine();

        // 4. Seed Publikasi
        $this->seedPublikasi();
        $this->command->newLine();

        // Summary
        $this->command->info('🎉 Semua data dummy berhasil dibuat!');
        $this->command->table(
            ['Model', 'Jumlah'],
            [
                ['Berita', Berita::count()],
                ['Potensi Desa', PotensiDesa::count()],
                ['Galeri', Galeri::count()],
                ['Publikasi', Publikasi::count()],
            ]
        );
    }

    /**
     * Download gambar dari picsum.photos dan simpan ke storage
     */
    private function downloadImage(string $folder, string $filename, int $width = 800, int $height = 600, ?int $seed = null): ?string
    {
        try {
            $seedParam = $seed ?? rand(1, 1000);
            $url = "https://picsum.photos/seed/{$seedParam}/{$width}/{$height}";

            $response = Http::timeout(30)->get($url);

            if ($response->successful()) {
                $path = "{$folder}/{$filename}";
                Storage::disk('public')->put($path, $response->body());
                return $path;
            }
        } catch (\Exception $e) {
            $this->command->warn("⚠ Gagal download gambar: {$e->getMessage()}");
        }

        // Fallback: buat placeholder image menggunakan GD
        return $this->createPlaceholderImage($folder, $filename, $width, $height);
    }

    /**
     * Buat placeholder image menggunakan GD library jika download gagal
     */
    private function createPlaceholderImage(string $folder, string $filename, int $width, int $height): ?string
    {
        if (!function_exists('imagecreatetruecolor')) {
            $this->command->warn("⚠ GD library tidak tersedia, gambar tidak dibuat.");
            return null;
        }

        $image = imagecreatetruecolor($width, $height);

        // Warna background random yang menarik
        $colors = [
            [46, 125, 50],    // Green
            [21, 101, 192],   // Blue
            [230, 81, 0],     // Orange
            [136, 14, 79],    // Pink
            [74, 20, 140],    // Purple
            [0, 121, 107],    // Teal
            [62, 39, 35],     // Brown
            [33, 33, 33],     // Dark Grey
        ];

        $colorIdx = array_rand($colors);
        $bgColor = imagecolorallocate($image, $colors[$colorIdx][0], $colors[$colorIdx][1], $colors[$colorIdx][2]);
        imagefill($image, 0, 0, $bgColor);

        // Tambahkan teks
        $textColor = imagecolorallocate($image, 255, 255, 255);
        $text = pathinfo($filename, PATHINFO_FILENAME);
        $textLen = strlen($text);
        $fontSize = 4; // Built-in font
        $fontWidth = imagefontwidth($fontSize);
        $fontHeight = imagefontheight($fontSize);

        $x = ($width - ($textLen * $fontWidth)) / 2;
        $y = ($height - $fontHeight) / 2;

        imagestring($image, $fontSize, max(10, $x), $y, $text, $textColor);

        // Simpan
        $path = "{$folder}/{$filename}";
        ob_start();
        imagejpeg($image, null, 85);
        $imageData = ob_get_clean();
        imagedestroy($image);

        Storage::disk('public')->put($path, $imageData);
        return $path;
    }

    /**
     * Create a dummy PDF file
     */
    private function createDummyPdf(string $folder, string $filename, string $title): string
    {
        // Simple PDF structure
        $content = "%PDF-1.4\n";
        $content .= "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $content .= "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";
        $content .= "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>\nendobj\n";

        $text = "BT /F1 16 Tf 72 720 Td ({$title}) Tj ET\n";
        $text .= "BT /F1 12 Tf 72 680 Td (Desa Warurejo - Dokumen Resmi) Tj ET\n";
        $text .= "BT /F1 10 Tf 72 640 Td (Dokumen ini adalah contoh/dummy untuk keperluan development.) Tj ET\n";

        $content .= "4 0 obj\n<< /Length " . strlen($text) . " >>\nstream\n{$text}endstream\nendobj\n";
        $content .= "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";
        $content .= "xref\n0 6\n";
        $content .= "trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n0\n%%EOF";

        $path = "{$folder}/{$filename}";
        Storage::disk('public')->put($path, $content);
        return $path;
    }

    // ==========================================
    // BERITA SEEDER - 20 berita
    // ==========================================
    private function seedBerita(Admin $admin): void
    {
        $this->command->info('📰 Membuat 20 berita dummy dengan gambar...');

        $beritaData = [
            ['judul' => 'Musyawarah Desa Membahas Program Kerja Tahun 2025', 'days_ago' => 2],
            ['judul' => 'Pembangunan Jalan Desa Tahap II Telah Dimulai', 'days_ago' => 5],
            ['judul' => 'Posyandu Balita Rutin Dilaksanakan Setiap Bulan', 'days_ago' => 7],
            ['judul' => 'Pelatihan UMKM untuk Meningkatkan Ekonomi Warga', 'days_ago' => 10],
            ['judul' => 'Gotong Royong Membersihkan Selokan Desa', 'days_ago' => 12],
            ['judul' => 'Penyaluran Bantuan Langsung Tunai kepada Warga Kurang Mampu', 'days_ago' => 15],
            ['judul' => 'Festival Budaya Desa Meriahkan HUT RI ke-80', 'days_ago' => 18],
            ['judul' => 'Vaksinasi COVID-19 Booster untuk Lansia', 'days_ago' => 22],
            ['judul' => 'Lomba Desa Tingkat Kecamatan Raih Juara Harapan', 'days_ago' => 25],
            ['judul' => 'Pembentukan Kelompok Tani Muda untuk Generasi Milenial', 'days_ago' => 28],
            ['judul' => 'Program Bantuan Benih Padi Unggulan untuk Petani', 'days_ago' => 31],
            ['judul' => 'Sosialisasi Pencegahan Stunting di Posyandu', 'days_ago' => 35],
            ['judul' => 'Pembangunan Gedung PAUD Baru Resmi Dimulai', 'days_ago' => 38],
            ['judul' => 'Peringatan Hari Kemerdekaan dengan Berbagai Lomba', 'days_ago' => 42],
            ['judul' => 'Penyemprotan Disinfektan di Fasilitas Umum', 'days_ago' => 45],
            ['judul' => 'Pelatihan Digital Marketing untuk Pelaku UMKM', 'days_ago' => 50],
            ['judul' => 'Donor Darah Rutin Bekerjasama dengan PMI', 'days_ago' => 55],
            ['judul' => 'Renovasi Balai Desa untuk Pelayanan Lebih Baik', 'days_ago' => 60],
            ['judul' => 'Bantuan Bibit Tanaman untuk Program Penghijauan', 'days_ago' => 65],
            ['judul' => 'Launching Website Profil Desa Warurejo', 'days_ago' => 70],
        ];

        foreach ($beritaData as $index => $data) {
            $status = $index < 16 ? 'published' : 'draft'; // 16 published, 4 draft
            $publishedAt = $status === 'published'
                ? Carbon::now()->subDays($data['days_ago'])
                : null;

            // Download gambar
            $imagePath = $this->downloadImage(
                'berita',
                'berita-' . ($index + 1) . '.jpg',
                800,
                600,
                100 + $index  // Unique seed per image
            );

            $konten = $this->generateBeritaKonten($data['judul']);
            $ringkasan = $this->generateBeritaRingkasan($data['judul']);

            Berita::create([
                'admin_id' => $admin->id,
                'judul' => $data['judul'],
                'slug' => Str::slug($data['judul']) . '-' . Str::random(5),
                'ringkasan' => $ringkasan,
                'konten' => $konten,
                'gambar_utama' => $imagePath,
                'status' => $status,
                'views' => rand(15, 750),
                'published_at' => $publishedAt,
                'created_at' => $publishedAt ?? Carbon::now(),
                'updated_at' => $publishedAt ?? Carbon::now(),
            ]);

            $statusIcon = $status === 'published' ? '✅' : '📝';
            $this->command->info("  {$statusIcon} Berita " . ($index + 1) . ': ' . $data['judul']);
        }

        $this->command->info('✅ 20 berita berhasil dibuat!');
    }

    private function generateBeritaRingkasan(string $judul): string
    {
        $templates = [
            "Kegiatan {$judul} dilaksanakan dengan antusias oleh seluruh warga Desa Warurejo. Program ini bertujuan meningkatkan kesejahteraan masyarakat dan pembangunan desa berkelanjutan.",
            "Pemerintah Desa Warurejo mengadakan {$judul} sebagai upaya pemberdayaan masyarakat. Kegiatan ini mendapat sambutan positif dari berbagai kalangan.",
            "Dalam rangka {$judul}, pemerintah desa mengundang seluruh elemen masyarakat untuk berpartisipasi aktif demi kemajuan bersama.",
            "Pelaksanaan {$judul} di Desa Warurejo berjalan lancar dan sesuai rencana. Antusiasme warga sangat tinggi mendukung program ini.",
            "{$judul} merupakan salah satu program prioritas Desa Warurejo tahun ini yang melibatkan berbagai pihak termasuk tokoh masyarakat.",
        ];
        return $templates[array_rand($templates)];
    }

    private function generateBeritaKonten(string $judul): string
    {
        $pembuka = [
            "<p><strong>Desa Warurejo</strong> - Pelaksanaan kegiatan {$judul} telah berjalan dengan sukses dan mendapatkan respons positif dari masyarakat. Kegiatan yang diinisiasi oleh pemerintah desa ini merupakan wujud komitmen dalam membangun desa yang lebih maju dan sejahtera.</p>",
            "<p><strong>Desa Warurejo</strong> - Dalam upaya mewujudkan pembangunan yang berkelanjutan, {$judul} telah resmi dilaksanakan. Program ini menjadi bukti nyata dedikasi pemerintah desa dalam melayani masyarakat.</p>",
        ];

        $isi = [
            "<p>Kegiatan ini dihadiri oleh Kepala Desa beserta perangkat desa, tokoh masyarakat, serta perwakilan dari berbagai organisasi kemasyarakatan. Dalam sambutannya, Kepala Desa menyampaikan pentingnya partisipasi aktif seluruh warga dalam pembangunan desa.</p>",
            "<p>Menurut keterangan Sekretaris Desa, program ini merupakan bagian dari upaya pemerintah desa dalam meningkatkan kualitas pelayanan kepada masyarakat. \"Kami berkomitmen untuk terus berinovasi dan memberikan yang terbaik bagi warga desa,\" ujarnya.</p>",
            "<p>Para peserta terlihat sangat antusias mengikuti kegiatan dari awal hingga akhir. Berbagai pertanyaan dan masukan disampaikan untuk perbaikan program ke depannya. Hal ini menunjukkan tingginya kepedulian masyarakat terhadap pembangunan desa.</p>",
            "<p>Pemerintah desa mengalokasikan anggaran khusus untuk mendukung terlaksananya program ini. Dana berasal dari APBDes yang telah disetujui melalui musyawarah desa. Transparansi penggunaan anggaran menjadi prioritas utama.</p>",
            "<p>Kegiatan dilaksanakan di Balai Desa Warurejo dengan menerapkan protokol kesehatan yang ketat. Panitia menyediakan hand sanitizer, masker, dan mengatur jarak tempat duduk untuk kenyamanan peserta.</p>",
            "<p>Beberapa warga menyampaikan apresiasi tinggi terhadap inisiatif pemerintah desa. Mereka berharap program serupa dapat terus dilaksanakan secara berkala untuk kemajuan bersama.</p>",
            "<p>Tim dokumentasi desa mengabadikan seluruh rangkaian kegiatan untuk arsip dan publikasi di website resmi desa. Transparansi informasi menjadi komitmen pemerintah desa.</p>",
        ];

        $penutup = [
            "<p>Kegiatan ditutup dengan doa bersama dan foto bersama seluruh peserta. Semoga program ini memberikan manfaat maksimal bagi kemajuan Desa Warurejo.</p>",
            "<p>Pemerintah Desa mengucapkan terima kasih kepada seluruh pihak yang mendukung terlaksananya kegiatan ini. Mari bersama-sama membangun desa yang lebih baik.</p>",
            "<p>Dengan selesainya kegiatan ini, diharapkan dapat membawa dampak positif bagi masyarakat. Pemerintah desa akan terus melakukan monitoring dan evaluasi untuk perbaikan di masa mendatang.</p>",
        ];

        // Rakit konten
        $parts = [];
        $parts[] = $pembuka[array_rand($pembuka)];

        // Ambil 3-5 paragraf isi secara random
        $selectedIsi = array_rand($isi, rand(3, min(5, count($isi))));
        if (!is_array($selectedIsi)) $selectedIsi = [$selectedIsi];
        foreach ($selectedIsi as $idx) {
            $parts[] = $isi[$idx];
        }

        $parts[] = $penutup[array_rand($penutup)];

        return implode("\n\n", $parts);
    }

    // ==========================================
    // POTENSI DESA SEEDER - 20 potensi
    // ==========================================
    private function seedPotensi(): void
    {
        $this->command->info('🌾 Membuat 20 potensi desa dummy dengan gambar...');

        $potensiData = [
            // Pertanian (4)
            [
                'nama' => 'Pertanian Padi Organik',
                'kategori' => 'pertanian',
                'lokasi' => 'Dusun Selatan, RT 01-05',
                'kontak' => '081234567890',
                'whatsapp' => '081234567890',
                'deskripsi' => '<p><strong>Pertanian padi organik</strong> menjadi salah satu unggulan Desa Warurejo dengan luas lahan mencapai 150 hektar. Petani setempat telah menerapkan sistem pertanian organik yang ramah lingkungan tanpa pestisida kimia.</p><p>Hasil panen padi organik memiliki kualitas premium dan diminati oleh konsumen yang peduli kesehatan. Pemerintah desa terus memberikan pendampingan dan pelatihan untuk meningkatkan produktivitas.</p><p>Kerjasama dengan dinas pertanian dan lembaga sertifikasi organik terus diperkuat untuk membuka akses pasar yang lebih luas.</p>'
            ],
            [
                'nama' => 'Perkebunan Kopi Arabika',
                'kategori' => 'pertanian',
                'lokasi' => 'Dusun Utara, RT 08-10',
                'kontak' => '081234567891',
                'whatsapp' => '081234567891',
                'deskripsi' => '<p>Perkebunan <strong>kopi arabika</strong> berada di dataran tinggi dengan ketinggian ideal untuk menghasilkan biji kopi berkualitas. Aroma dan cita rasa khas menjadikan kopi Warurejo mulai dikenal di pasar nasional.</p><p>Kelompok tani kopi telah memiliki mesin pengolahan modern untuk proses pasca panen. Branding dan kemasan terus diperbaiki untuk meningkatkan nilai jual.</p><p>Wisata agro kopi juga mulai dikembangkan untuk menarik wisatawan yang ingin belajar tentang proses produksi kopi.</p>'
            ],
            [
                'nama' => 'Budidaya Sayuran Hidroponik',
                'kategori' => 'pertanian',
                'lokasi' => 'Dusun Tengah, RT 06-07',
                'kontak' => '081234567892',
                'whatsapp' => '081234567892',
                'deskripsi' => '<p><strong>Hidroponik</strong> menjadi inovasi pertanian modern yang dikembangkan di Desa Warurejo. Sistem tanam tanpa tanah ini menghasilkan sayuran berkualitas tinggi dengan produktivitas optimal.</p><p>Berbagai jenis sayuran seperti selada, kangkung, dan bayam dibudidayakan menggunakan metode NFT dan DFT.</p><p>Hasil panen dipasarkan ke restoran, hotel, dan supermarket. Pelatihan hidroponik juga rutin diadakan untuk masyarakat.</p>'
            ],
            [
                'nama' => 'Perkebunan Buah-buahan Tropis',
                'kategori' => 'pertanian',
                'lokasi' => 'Dusun Selatan, RT 03-04',
                'kontak' => '081234567820',
                'whatsapp' => '081234567820',
                'deskripsi' => '<p><strong>Perkebunan buah-buahan tropis</strong> seperti mangga, durian, rambutan, dan manggis menjadi sumber penghasilan tambahan bagi warga desa. Berbagai varietas unggul ditanam di lahan seluas 80 hektar.</p><p>Sistem pertanian terpadu diterapkan dengan kombinasi tanaman buah dan tanaman sela untuk memaksimalkan produktivitas lahan.</p><p>Hasil panen dipasarkan ke pasar tradisional, supermarket, dan pengepul dengan kualitas yang terjamin.</p>'
            ],

            // Peternakan (3)
            [
                'nama' => 'Peternakan Ayam Kampung',
                'kategori' => 'peternakan',
                'lokasi' => 'Dusun Barat, RT 11-12',
                'kontak' => '081234567893',
                'whatsapp' => '081234567893',
                'deskripsi' => '<p>Peternakan <strong>ayam kampung</strong> dikelola secara modern dengan sistem kandang battery dan postal. Populasi ayam mencapai 10.000 ekor dengan pemeliharaan higienis.</p><p>Produk meliputi daging ayam kampung dan telur ayam kampung yang memiliki kandungan gizi tinggi. Permintaan pasar terus meningkat.</p><p>Peternak mendapat pendampingan dari dinas peternakan terkait manajemen kesehatan ternak dan pemasaran.</p>'
            ],
            [
                'nama' => 'Peternakan Sapi Potong',
                'kategori' => 'peternakan',
                'lokasi' => 'Dusun Barat, RT 13',
                'kontak' => '081234567821',
                'whatsapp' => '081234567821',
                'deskripsi' => '<p><strong>Peternakan sapi potong</strong> dengan populasi lebih dari 500 ekor menjadi salah satu pilar ekonomi desa. Sapi-sapi dipelihara dengan pakan berkualitas dan manajemen kandang modern.</p><p>Program inseminasi buatan (IB) telah dilaksanakan untuk meningkatkan kualitas genetik ternak. Hasil ternak dipasarkan ke berbagai daerah.</p><p>Kelompok peternak aktif mengikuti pelatihan dan pameran ternak tingkat kabupaten dan provinsi.</p>'
            ],
            [
                'nama' => 'Peternakan Kambing Etawa',
                'kategori' => 'peternakan',
                'lokasi' => 'Dusun Timur, RT 14',
                'kontak' => '081234567822',
                'whatsapp' => '081234567822',
                'deskripsi' => '<p><strong>Kambing Etawa</strong> dibudidayakan untuk produksi susu kambing yang kaya protein. Populasi kambing mencapai 300 ekor dengan produksi susu harian yang stabil.</p><p>Susu kambing diolah menjadi berbagai produk seperti susu pasteurisasi, yoghurt, dan sabun susu kambing. Produk dikemas modern dan dipasarkan secara online.</p><p>Wisata edukasi peternakan juga dikembangkan untuk menarik pengunjung yang ingin belajar tentang budidaya kambing.</p>'
            ],

            // Perikanan (2)
            [
                'nama' => 'Budidaya Ikan Air Tawar',
                'kategori' => 'perikanan',
                'lokasi' => 'Dusun Timur, RT 13-15',
                'kontak' => '081234567894',
                'whatsapp' => '081234567894',
                'deskripsi' => '<p><strong>Budidaya ikan air tawar</strong> seperti lele, nila, dan gurame menjadi usaha menjanjikan. Kolam terpal dan kolam tanah tersebar di berbagai dusun dengan total luas 20 hektar.</p><p>Sistem budidaya intensif dengan pakan berkualitas menghasilkan ikan berukuran konsumsi dalam waktu singkat.</p><p>Pemasaran dilakukan ke pasar tradisional, rumah makan, dan pengepul dengan omzet terus meningkat.</p>'
            ],
            [
                'nama' => 'Budidaya Udang Vaname',
                'kategori' => 'perikanan',
                'lokasi' => 'Dusun Timur, RT 16',
                'kontak' => '081234567823',
                'whatsapp' => '081234567823',
                'deskripsi' => '<p><strong>Budidaya udang vaname</strong> dikembangkan dengan teknologi bioflok untuk efisiensi lahan dan air. Tambak-tambak udang dikelola secara intensif dengan monitoring kualitas air secara berkala.</p><p>Hasil panen udang vaname memenuhi standar ekspor dengan ukuran yang seragam. Produksi rata-rata mencapai 5 ton per siklus panen.</p><p>Kerjasama dengan eksportir dan industri pengolahan seafood memberikan jaminan pasar yang stabil.</p>'
            ],

            // UMKM (4)
            [
                'nama' => 'Produksi Makanan Ringan Tradisional',
                'kategori' => 'umkm',
                'lokasi' => 'Dusun Tengah, RT 06',
                'kontak' => '081234567896',
                'whatsapp' => '081234567896',
                'deskripsi' => '<p><strong>Makanan ringan tradisional</strong> seperti keripik singkong, keripik tempe, dan rempeyek menjadi produk unggulan UMKM desa. Cita rasa autentik dengan resep turun temurun menjadi daya tarik.</p><p>Proses produksi dilakukan secara higienis dengan kemasan menarik dan berlabel halal. Pemasaran melalui marketplace online dan reseller.</p><p>Omzet bulanan mencapai puluhan juta rupiah dengan tenaga kerja yang terus bertambah.</p>'
            ],
            [
                'nama' => 'Konveksi Pakaian',
                'kategori' => 'umkm',
                'lokasi' => 'Dusun Utara, RT 09',
                'kontak' => '081234567897',
                'whatsapp' => '081234567897',
                'deskripsi' => '<p>Usaha <strong>konveksi pakaian</strong> melayani pembuatan seragam sekolah, seragam kantor, kaos sablon, dan pakaian custom. Mesin jahit modern dan tenaga kerja terampil siap mengerjakan pesanan besar.</p><p>Kualitas jahitan dan ketepatan waktu menjadi komitmen utama. Harga kompetitif dengan kualitas premium.</p><p>Kerjasama dengan sekolah, perusahaan, dan instansi pemerintah terus dibina.</p>'
            ],
            [
                'nama' => 'Produksi Jamu Tradisional',
                'kategori' => 'umkm',
                'lokasi' => 'Dusun Barat, RT 11',
                'kontak' => '081234567898',
                'whatsapp' => '081234567898',
                'deskripsi' => '<p><strong>Jamu tradisional</strong> dengan bahan alami pilihan diproduksi sesuai standar kesehatan. Berbagai varian seperti kunyit asam, beras kencur, dan temulawak tersedia dalam bentuk serbuk dan siap minum.</p><p>Legalitas PIRT dan sertifikasi halal telah dikantongi. Branding modern membuat jamu tradisional diminati generasi muda.</p><p>Distribusi ke toko herbal, apotek, dan platform e-commerce.</p>'
            ],
            [
                'nama' => 'Industri Batik Tulis Warurejo',
                'kategori' => 'umkm',
                'lokasi' => 'Dusun Tengah, RT 07',
                'kontak' => '081234567824',
                'whatsapp' => '081234567824',
                'deskripsi' => '<p><strong>Batik Tulis Warurejo</strong> merupakan kebanggaan desa dengan motif khas yang terinspirasi dari kearifan lokal. Pengrajin batik telah melestarikan tradisi ini selama puluhan tahun.</p><p>Workshop batik tulis terbuka untuk wisatawan dan pelajar yang ingin belajar membatik. Produk dijual mulai dari kain, pakaian jadi, hingga aksesoris.</p><p>Batik Warurejo telah mendapat pengakuan dari Dinas Perindustrian dan dipromosikan di berbagai pameran batik nasional.</p>'
            ],

            // Lainnya (2) - formerly Kerajinan
            [
                'nama' => 'Kerajinan Bambu',
                'kategori' => 'lainnya',
                'lokasi' => 'Dusun Selatan, RT 02',
                'kontak' => '081234567895',
                'whatsapp' => '081234567895',
                'deskripsi' => '<p><strong>Kerajinan bambu</strong> menjadi warisan turun temurun yang terus dilestarikan. Produk meliputi furniture, anyaman, dan souvenir dengan desain modern yang tetap mempertahankan ciri khas tradisional.</p><p>Pengrajin telah mendapat pelatihan desain produk dan manajemen usaha. Produk dipasarkan online dan offline.</p><p>Showroom kerajinan bambu juga dibuka untuk memudahkan konsumen.</p>'
            ],
            [
                'nama' => 'Kerajinan Gerabah dan Tembikar',
                'kategori' => 'lainnya',
                'lokasi' => 'Dusun Barat, RT 12',
                'kontak' => '081234567825',
                'whatsapp' => '081234567825',
                'deskripsi' => '<p><strong>Kerajinan gerabah dan tembikar</strong> menjadi warisan budaya yang dikembangkan dengan sentuhan modern. Produk meliputi pot bunga, vas, dan hiasan interior dengan desain kontemporer.</p><p>Para pengrajin gerabah memanfaatkan tanah liat lokal berkualitas tinggi yang diolah secara tradisional. Proses pembakaran menggunakan tungku tradisional dan modern.</p><p>Produk gerabah Warurejo telah diekspor ke beberapa negara ASEAN dan mendapat apresiasi internasional.</p>'
            ],

            // Wisata (3)
            [
                'nama' => 'Air Terjun Sumber Sari',
                'kategori' => 'wisata',
                'lokasi' => 'Dusun Utara, RT 10',
                'kontak' => '081234567800',
                'whatsapp' => '081234567800',
                'deskripsi' => '<p><strong>Air Terjun Sumber Sari</strong> menawarkan keindahan air terjun setinggi 25 meter. Suasana sejuk dengan pepohonan rindang menjadikan tempat ini ideal untuk refreshing bersama keluarga.</p><p>Fasilitas meliputi area parkir, gazebo, warung makan, dan toilet. Jalur trekking yang aman memudahkan pengunjung mencapai lokasi.</p><p>Spot foto instagramable juga disediakan untuk kepuasan pengunjung.</p>'
            ],
            [
                'nama' => 'Kampung Wisata Budaya',
                'kategori' => 'wisata',
                'lokasi' => 'Dusun Tengah, RT 06-07',
                'kontak' => '081234567801',
                'whatsapp' => '081234567801',
                'deskripsi' => '<p><strong>Kampung Wisata Budaya</strong> menawarkan pengalaman wisata edukatif tentang kehidupan dan budaya masyarakat desa. Wisatawan dapat belajar membatik, membuat kerajinan tangan, dan memasak makanan tradisional.</p><p>Paket homestay tersedia bagi wisatawan yang ingin merasakan kehidupan desa langsung.</p><p>Event budaya seperti pertunjukan wayang kulit dan tari tradisional rutin digelar.</p>'
            ],
            [
                'nama' => 'Agrowisata Kebun Buah',
                'kategori' => 'wisata',
                'lokasi' => 'Dusun Selatan, RT 03-04',
                'kontak' => '081234567802',
                'whatsapp' => '081234567802',
                'deskripsi' => '<p><strong>Agrowisata Kebun Buah</strong> menghadirkan konsep wisata petik buah langsung dari pohonnya. Berbagai buah tropis seperti durian, mangga, rambutan tersedia sesuai musim.</p><p>Pengunjung dapat berjalan-jalan di kebun sambil menikmati udara segar. Harga terjangkau dengan sistem bayar per kilogram.</p><p>Area bermain anak, kolam renang alami, dan resto melengkapi fasilitas agrowisata.</p>'
            ],

            // Lainnya (2)
            [
                'nama' => 'Pengolahan Hasil Panen',
                'kategori' => 'lainnya',
                'lokasi' => 'Dusun Selatan, RT 05',
                'kontak' => '081234567803',
                'whatsapp' => '081234567803',
                'deskripsi' => '<p>Unit <strong>pengolahan hasil panen</strong> dilengkapi mesin modern untuk mengolah hasil pertanian menjadi produk bernilai tambah. Proses meliputi sortir, grading, pengemasan, hingga labeling.</p><p>Kerjasama dengan kelompok tani memastikan pasokan bahan baku kontinu.</p><p>Pelatihan pengelolaan pasca panen rutin diadakan untuk meningkatkan skill SDM.</p>'
            ],
            [
                'nama' => 'Produksi Pupuk Organik',
                'kategori' => 'lainnya',
                'lokasi' => 'Dusun Barat, RT 12',
                'kontak' => '081234567804',
                'whatsapp' => '081234567804',
                'deskripsi' => '<p>Pabrik <strong>pupuk organik</strong> mengolah limbah ternak dan sampah organik menjadi pupuk berkualitas. Proses produksi menggunakan teknologi fermentasi untuk menghasilkan pupuk dengan kandungan hara lengkap.</p><p>Produk dikemas dalam berbagai ukuran 5 kg hingga 50 kg. Sertifikasi organik menjamin kualitas produk.</p><p>Pemasaran ke toko pertanian, petani organik, dan perkebunan dengan harga bersaing.</p>'
            ],
        ];

        foreach ($potensiData as $index => $data) {
            // Download gambar
            $imagePath = $this->downloadImage(
                'potensi',
                'potensi-' . ($index + 1) . '.jpg',
                800,
                600,
                200 + $index  // Unique seed
            );

            PotensiDesa::create([
                'nama' => $data['nama'],
                'slug' => Str::slug($data['nama']),
                'kategori' => $data['kategori'],
                'deskripsi' => $data['deskripsi'],
                'gambar' => $imagePath,
                'lokasi' => $data['lokasi'],
                'whatsapp' => $data['whatsapp'] ?? '81234567890',
                'nama_pengelola' => 'Pengelola ' . $data['nama'],
                'info_utama' => $data['lokasi'],
                'status' => 'published',
                'published_at' => now(),
                'urutan' => $index + 1,
                'views' => rand(5, 300),
            ]);

            $kategoriLabel = ucfirst($data['kategori']);
            $this->command->info("  🌾 [{$kategoriLabel}] Potensi " . ($index + 1) . ': ' . $data['nama']);
        }

        $this->command->info('✅ 20 potensi desa berhasil dibuat!');
    }

    // ==========================================
    // GALERI SEEDER - 30 galeri
    // ==========================================
    private function seedGaleri(Admin $admin): void
    {
        $this->command->info('📸 Membuat 40 galeri dummy dengan gambar...');

        $galeriData = [
            // Kegiatan (15)
            ['judul' => 'Musyawarah Desa Pembahasan APBDes 2025', 'deskripsi' => 'Musyawarah desa dihadiri seluruh perangkat desa, BPD, dan tokoh masyarakat untuk membahas rencana anggaran.', 'kategori' => 'kegiatan'],
            ['judul' => 'Gotong Royong Membersihkan Lingkungan', 'deskripsi' => 'Warga bersama-sama bergotong royong membersihkan selokan dan lingkungan desa untuk cegah banjir.', 'kategori' => 'kegiatan'],
            ['judul' => 'Pelaksanaan Posyandu Balita', 'deskripsi' => 'Kegiatan posyandu rutin untuk memantau tumbuh kembang balita dan pemberian imunisasi.', 'kategori' => 'kegiatan'],
            ['judul' => 'Pelatihan UMKM Digital Marketing', 'deskripsi' => 'Pelaku UMKM desa mendapat pelatihan digital marketing untuk meningkatkan penjualan online.', 'kategori' => 'kegiatan'],
            ['judul' => 'Senam Sehat Rutin PKK', 'deskripsi' => 'Ibu-ibu PKK melaksanakan senam sehat bersama setiap Jumat pagi di lapangan desa.', 'kategori' => 'kegiatan'],
            ['judul' => 'Rapat Koordinasi RT/RW', 'deskripsi' => 'Koordinasi rutin antara Kepala Desa dengan RT/RW membahas program kerja.', 'kategori' => 'kegiatan'],
            ['judul' => 'Vaksinasi COVID-19 Booster', 'deskripsi' => 'Vaksinasi booster untuk lansia dan masyarakat umum bekerjasama dengan Puskesmas.', 'kategori' => 'kegiatan'],
            ['judul' => 'Donor Darah Rutin PMI', 'deskripsi' => 'Kegiatan donor darah bekerjasama dengan PMI diikuti puluhan warga.', 'kategori' => 'kegiatan'],
            ['judul' => 'Pelatihan Pertanian Organik', 'deskripsi' => 'Petani mengikuti pelatihan teknik bertani organik dari narasumber dinas pertanian.', 'kategori' => 'kegiatan'],
            ['judul' => 'Sosialisasi Pencegahan Stunting', 'deskripsi' => 'Tim penyuluh memberikan edukasi pencegahan stunting pada ibu hamil dan balita.', 'kategori' => 'kegiatan'],
            ['judul' => 'Peringatan HUT RI ke-80', 'deskripsi' => 'Perayaan kemerdekaan RI dengan berbagai lomba dan pawai budaya yang meriah.', 'kategori' => 'kegiatan'],
            ['judul' => 'Turnamen Bola Voli Antar RT', 'deskripsi' => 'Turnamen bola voli antar RT dalam rangka memeriahkan hari kemerdekaan.', 'kategori' => 'kegiatan'],
            ['judul' => 'Pesta Rakyat Akhir Tahun', 'deskripsi' => 'Perayaan akhir tahun dengan hiburan dan doorprize untuk warga.', 'kategori' => 'kegiatan'],
            ['judul' => 'Lomba Desa Tingkat Kecamatan', 'deskripsi' => 'Tim desa meraih juara harapan pada lomba desa.', 'kategori' => 'kegiatan'],
            ['judul' => 'Pelatihan Kader Posyandu', 'deskripsi' => 'Kader posyandu mengikuti pelatihan untuk meningkatkan kualitas pelayanan.', 'kategori' => 'kegiatan'],

            // Pembangunan (10)
            ['judul' => 'Pembangunan Jalan Desa Tahap II', 'deskripsi' => 'Pengaspalan jalan desa sepanjang 2 kilometer untuk akses transportasi lebih baik.', 'kategori' => 'pembangunan'],
            ['judul' => 'Renovasi Balai Desa', 'deskripsi' => 'Renovasi total balai desa untuk meningkatkan kualitas pelayanan.', 'kategori' => 'pembangunan'],
            ['judul' => 'Pembangunan Gedung PAUD Baru', 'deskripsi' => 'Konstruksi gedung PAUD baru dengan fasilitas lengkap untuk pendidikan anak usia dini.', 'kategori' => 'pembangunan'],
            ['judul' => 'Pemasangan Lampu Jalan Tenaga Surya', 'deskripsi' => '50 unit lampu jalan tenaga surya di berbagai titik strategis.', 'kategori' => 'pembangunan'],
            ['judul' => 'Pembangunan Saluran Irigasi', 'deskripsi' => 'Saluran irigasi baru untuk mengairi lahan pertanian seluas 100 hektar.', 'kategori' => 'pembangunan'],
            ['judul' => 'Pembangunan Taman Desa', 'deskripsi' => 'Taman desa dengan fasilitas bermain anak dan gazebo untuk warga bersantai.', 'kategori' => 'pembangunan'],
            ['judul' => 'Normalisasi Sungai Desa', 'deskripsi' => 'Normalisasi dan pembersihan sungai untuk mencegah banjir di musim hujan.', 'kategori' => 'pembangunan'],
            ['judul' => 'Pembangunan Lapangan Futsal', 'deskripsi' => 'Lapangan futsal dengan rumput sintetis untuk hobi olahraga pemuda desa.', 'kategori' => 'pembangunan'],
            ['judul' => 'Pengecatan Gapura Desa', 'deskripsi' => 'Perawatan gapura desa dengan pengecatan ulang dan penambahan ornamen.', 'kategori' => 'pembangunan'],
            ['judul' => 'Pembangunan Pos Kamling', 'deskripsi' => 'Pembangunan pos kamling di setiap RT untuk keamanan lingkungan.', 'kategori' => 'pembangunan'],

            // Budaya (5)
            ['judul' => 'Festival Budaya Desa', 'deskripsi' => 'Festival budaya menampilkan seni tari tradisional, wayang kulit, dan kuliner khas.', 'kategori' => 'budaya'],
            ['judul' => 'Pawai Budaya Nusantara', 'deskripsi' => 'Pawai budaya menampilkan kostum tradisional dari berbagai daerah.', 'kategori' => 'budaya'],
            ['judul' => 'Malam Kesenian Desa', 'deskripsi' => 'Pertunjukan seni malam dengan penampilan tari tradisional dan musik.', 'kategori' => 'budaya'],
            ['judul' => 'Pelatihan Tari Tradisional Anak', 'deskripsi' => 'Anak-anak desa belajar tari tradisional untuk melestarikan budaya.', 'kategori' => 'budaya'],
            ['judul' => 'Pertunjukan Wayang Kulit', 'deskripsi' => 'Pertunjukan wayang kulit semalam suntuk dengan dalang kondang.', 'kategori' => 'budaya'],

            // Keagamaan (5)
            ['judul' => 'Peringatan Isra Mi\'raj Nabi Muhammad SAW', 'deskripsi' => 'Peringatan Isra Mi\'raj diisi dengan ceramah agama dan doa bersama di Masjid Baitul Amin.', 'kategori' => 'keagamaan'],
            ['judul' => 'Pengajian Akbar Maulid Nabi', 'deskripsi' => 'Perayaan Maulid Nabi dengan pengajian akbar yang dihadiri warga dari berbagai dusun.', 'kategori' => 'keagamaan'],
            ['judul' => 'Kegiatan Sholat Idul Fitri di Lapangan Desa', 'deskripsi' => 'Ribuan warga melaksanakan sholat Idul Fitri berjamaah di lapangan desa dengan penuh khidmat.', 'kategori' => 'keagamaan'],
            ['judul' => 'Penyembelihan Hewan Qurban Idul Adha', 'deskripsi' => 'Penyembelihan dan pembagian daging qurban kepada seluruh warga desa yang berhak menerima.', 'kategori' => 'keagamaan'],
            ['judul' => 'Santunan Anak Yatim Bulan Ramadhan', 'deskripsi' => 'Kegiatan santunan anak yatim dan dhuafa menjelang Hari Raya Idul Fitri oleh panitia desa.', 'kategori' => 'keagamaan'],

            // Sosial (5)
            ['judul' => 'Bakti Sosial Bersih Desa', 'deskripsi' => 'Warga bergotong royong membersihkan lingkungan desa termasuk sungai, jalan, dan fasilitas umum.', 'kategori' => 'sosial'],
            ['judul' => 'Pembagian Sembako untuk Warga Kurang Mampu', 'deskripsi' => 'Pemerintah desa mendistribusikan paket sembako kepada keluarga prasejahtera di setiap RT.', 'kategori' => 'sosial'],
            ['judul' => 'Jalan Sehat Bersama Warga', 'deskripsi' => 'Kegiatan jalan sehat bersama seluruh warga dalam rangka mempererat tali silaturahmi.', 'kategori' => 'sosial'],
            ['judul' => 'Kerja Bakti Perbaikan Rumah Warga', 'deskripsi' => 'Gotong royong perbaikan rumah warga tidak mampu yang rusak akibat cuaca buruk.', 'kategori' => 'sosial'],
            ['judul' => 'Penggalangan Dana Korban Bencana', 'deskripsi' => 'Warga desa menggalang donasi untuk membantu korban bencana alam di daerah lain.', 'kategori' => 'sosial'],
        ];

        foreach ($galeriData as $index => $data) {
            $tanggal = Carbon::now()->subDays(rand(1, 180));

            // Download gambar utama
            $imagePath = $this->downloadImage(
                'galeri',
                'galeri-' . ($index + 1) . '.jpg',
                800,
                600,
                300 + $index
            );

            $galeri = Galeri::create([
                'admin_id' => $admin->id,
                'judul' => $data['judul'],
                'deskripsi' => $data['deskripsi'],
                'gambar' => $imagePath,
                'kategori' => $data['kategori'],
                'tanggal' => $tanggal,
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(5, 200),
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            // Tambah 2-4 gambar tambahan per galeri (untuk galeri_images)
            $extraImages = rand(2, 4);
            for ($i = 0; $i < $extraImages; $i++) {
                $extraImagePath = $this->downloadImage(
                    'galeri',
                    'galeri-' . ($index + 1) . '-extra-' . ($i + 1) . '.jpg',
                    800,
                    600,
                    400 + ($index * 5) + $i
                );

                if ($extraImagePath) {
                    GaleriImage::create([
                        'galeri_id' => $galeri->id,
                        'image_path' => $extraImagePath,
                        'urutan' => $i + 1,
                    ]);
                }
            }

            $this->command->info("  📸 Galeri " . ($index + 1) . ': ' . $data['judul'] . " (+{$extraImages} foto)");
        }

        $this->command->info('✅ 40 galeri berhasil dibuat!');
    }

    // ==========================================
    // PUBLIKASI SEEDER - 10 publikasi
    // ==========================================
    private function seedPublikasi(): void
    {
        $this->command->info('📄 Membuat 10 publikasi dummy...');

        $publikasiData = [
            // APBDes (4)
            [
                'judul' => 'Anggaran Pendapatan dan Belanja Desa (APBDes) Tahun 2025',
                'kategori' => 'APBDes',
                'tahun' => 2025,
                'days_ago' => 5,
                'deskripsi' => 'Dokumen APBDes Desa Warurejo Tahun Anggaran 2025 yang mencakup rencana pendapatan dan belanja desa untuk pembangunan dan pelayanan masyarakat.'
            ],
            [
                'judul' => 'Anggaran Pendapatan dan Belanja Desa (APBDes) Tahun 2024',
                'kategori' => 'APBDes',
                'tahun' => 2024,
                'days_ago' => 180,
                'deskripsi' => 'Dokumen APBDes Desa Warurejo Tahun Anggaran 2024 beserta lampiran rincian anggaran.'
            ],
            [
                'judul' => 'Laporan Realisasi APBDes Semester 1 Tahun 2025',
                'kategori' => 'APBDes',
                'tahun' => 2025,
                'days_ago' => 15,
                'deskripsi' => 'Laporan realisasi pelaksanaan APBDes semester pertama tahun 2025 beserta capaian program.'
            ],
            [
                'judul' => 'Laporan Realisasi APBDes Tahun 2024',
                'kategori' => 'APBDes',
                'tahun' => 2024,
                'days_ago' => 100,
                'deskripsi' => 'Laporan realisasi pelaksanaan APBDes tahun 2024 secara lengkap beserta pertanggungjawaban penggunaan dana.'
            ],

            // RPJMDes (3)
            [
                'judul' => 'Rencana Pembangunan Jangka Menengah Desa (RPJMDes) 2024-2029',
                'kategori' => 'RPJMDes',
                'tahun' => 2024,
                'days_ago' => 90,
                'deskripsi' => 'Dokumen perencanaan pembangunan jangka menengah Desa Warurejo periode 2024-2029 yang memuat visi, misi, dan program pembangunan 5 tahun.'
            ],
            [
                'judul' => 'Evaluasi RPJMDes 2019-2024',
                'kategori' => 'RPJMDes',
                'tahun' => 2024,
                'days_ago' => 120,
                'deskripsi' => 'Laporan evaluasi pelaksanaan RPJMDes periode 2019-2024 beserta rekomendasi untuk periode selanjutnya.'
            ],
            [
                'judul' => 'Perubahan RPJMDes 2024-2029',
                'kategori' => 'RPJMDes',
                'tahun' => 2025,
                'days_ago' => 30,
                'deskripsi' => 'Dokumen perubahan RPJMDes mengakomodasi perkembangan kebijakan dan kebutuhan masyarakat terkini.'
            ],

            // RKPDes (3)
            [
                'judul' => 'Rencana Kerja Pemerintah Desa (RKPDes) Tahun 2025',
                'kategori' => 'RKPDes',
                'tahun' => 2025,
                'days_ago' => 10,
                'deskripsi' => 'RKPDes tahun 2025 memuat rencana kegiatan pembangunan dan pemberdayaan masyarakat yang akan dilaksanakan.'
            ],
            [
                'judul' => 'Rencana Kerja Pemerintah Desa (RKPDes) Tahun 2024',
                'kategori' => 'RKPDes',
                'tahun' => 2024,
                'days_ago' => 365,
                'deskripsi' => 'RKPDes tahun 2024 Desa Warurejo beserta lampiran kegiatan dan anggaran.'
            ],
            [
                'judul' => 'Laporan Pelaksanaan RKPDes Tahun 2024',
                'kategori' => 'RKPDes',
                'tahun' => 2024,
                'days_ago' => 60,
                'deskripsi' => 'Laporan pelaksanaan dan capaian program RKPDes tahun 2024 beserta dokumentasi kegiatan.'
            ],
        ];

        foreach ($publikasiData as $index => $data) {
            $tanggalPublikasi = Carbon::now()->subDays($data['days_ago']);

            // Buat dummy PDF
            $pdfPath = $this->createDummyPdf(
                'publikasi',
                Str::slug($data['judul']) . '.pdf',
                $data['judul']
            );

            // Buat thumbnail untuk publikasi
            $thumbnailPath = $this->downloadImage(
                'publikasi/thumbnails',
                'thumb-' . ($index + 1) . '.jpg',
                400,
                300,
                500 + $index
            );

            Publikasi::create([
                'judul' => $data['judul'],
                'kategori' => $data['kategori'],
                'tahun' => $data['tahun'],
                'deskripsi' => $data['deskripsi'],
                'file_dokumen' => $pdfPath,
                'thumbnail' => $thumbnailPath,
                'tanggal_publikasi' => $tanggalPublikasi,
                'status' => 'published',
                'jumlah_download' => rand(5, 100),
                'views' => rand(10, 200),
            ]);

            $this->command->info("  📄 Publikasi " . ($index + 1) . ': ' . $data['judul']);
        }

        $this->command->info('✅ 10 publikasi berhasil dibuat!');
    }
}
