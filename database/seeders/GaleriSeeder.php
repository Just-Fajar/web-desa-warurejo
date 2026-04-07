<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Galeri;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class GaleriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan folder storage ada
        if (!Storage::disk('public')->exists('galeri')) {
            Storage::disk('public')->makeDirectory('galeri');
        }

        // Get admin untuk foreign key
        $admin = Admin::first();
        
        if (!$admin) {
            $this->command->error('Admin tidak ditemukan. Jalankan AdminSeeder terlebih dahulu.');
            return;
        }

        $this->command->info('Membuat 30 galeri foto dummy...');

        $galeriData = [
            // Kegiatan (15 foto)
            ['judul' => 'Musyawarah Desa Pembahasan APBDes 2025', 'deskripsi' => 'Musyawarah desa dihadiri seluruh perangkat desa, BPD, dan tokoh masyarakat untuk membahas rencana anggaran tahun depan.', 'kategori' => 'kegiatan', 'gambar' => 'galeri/musyawarah-desa.jpg'],
            ['judul' => 'Gotong Royong Membersihkan Lingkungan', 'deskripsi' => 'Warga desa bersama-sama bergotong royong membersihkan selokan dan lingkungan desa untuk cegah banjir.', 'kategori' => 'kegiatan', 'gambar' => 'galeri/gotong-royong.jpg'],
            ['judul' => 'Pelaksanaan Posyandu Balita', 'deskripsi' => 'Kegiatan posyandu rutin dilaksanakan setiap bulan untuk memantau tumbuh kembang balita dan pemberian imunisasi.', 'kategori' => 'kegiatan', 'gambar' => 'galeri/posyandu-balita.jpg'],
            ['judul' => 'Pelatihan UMKM Digital Marketing', 'deskripsi' => 'Pelaku UMKM desa mendapat pelatihan digital marketing untuk meningkatkan penjualan online.', 'kategori' => 'kegiatan', 'gambar' => 'galeri/pelatihan-umkm.jpg'],
            ['judul' => 'Senam Sehat Rutin PKK', 'deskripsi' => 'Ibu-ibu PKK melaksanakan senam sehat bersama setiap Jumat pagi di lapangan desa.', 'kategori' => 'kegiatan', 'gambar' => 'galeri/senam-sehat.jpg'],
            ['judul' => 'Rapat Koordinasi RT/RW', 'deskripsi' => 'Koordinasi rutin antara Kepala Desa dengan RT/RW membahas program kerja dan permasalahan di masing-masing wilayah.', 'kategori' => 'kegiatan', 'gambar' => 'galeri/rapat-rt-rw.jpg'],
            ['judul' => 'Vaksinasi COVID-19 Booster', 'deskripsi' => 'Pelaksanaan vaksinasi booster untuk lansia dan masyarakat umum bekerjasama dengan Puskesmas.', 'kategori' => 'kegiatan', 'gambar' => 'galeri/vaksinasi-covid.jpg'],
            ['judul' => 'Donor Darah Rutin PMI', 'deskripsi' => 'Kegiatan donor darah bekerjasama dengan PMI diikuti puluhan warga dengan antusias.', 'kategori' => 'kegiatan', 'gambar' => 'galeri/donor-darah.jpg'],
            ['judul' => 'Pelatihan Pertanian Organik', 'deskripsi' => 'Petani mengikuti pelatihan teknik bertani organik tanpa pestisida kimia dari narasumber dinas pertanian.', 'kategori' => 'kegiatan', 'gambar' => 'galeri/pelatihan-pertanian.jpg'],
            ['judul' => 'Sosialisasi Pencegahan Stunting', 'deskripsi' => 'Tim penyuluh kesehatan memberikan edukasi tentang pencegahan stunting pada ibu hamil dan balita.', 'kategori' => 'kegiatan', 'gambar' => 'galeri/sosialisasi-stunting.jpg'],
            ['judul' => 'Peringatan HUT RI ke-80', 'deskripsi' => 'Perayaan kemerdekaan RI dengan berbagai lomba dan pawai budaya yang meriah.', 'kategori' => 'kegiatan', 'gambar' => 'galeri/hut-ri.jpg'],
            ['judul' => 'Turnamen Bola Voli Antar RT', 'deskripsi' => 'Turnamen bola voli antar RT dalam rangka memeriahkan hari kemerdekaan.', 'kategori' => 'kegiatan', 'gambar' => 'galeri/turnamen-voli.jpg'],
            ['judul' => 'Pesta Rakyat Akhir Tahun', 'deskripsi' => 'Perayaan akhir tahun dengan hiburan musik dangdut dan doorprize untuk warga.', 'kategori' => 'kegiatan', 'gambar' => 'galeri/pesta-rakyat.jpg'],
            ['judul' => 'Lomba Desa Tingkat Kecamatan', 'deskripsi' => 'Tim desa meraih juara harapan pada lomba desa dengan penilaian administrasi dan pembangunan.', 'kategori' => 'kegiatan', 'gambar' => 'galeri/lomba-desa.jpg'],
            ['judul' => 'Pelatihan Kader Posyandu', 'deskripsi' => 'Kader posyandu mengikuti pelatihan untuk meningkatkan kualitas pelayanan kesehatan masyarakat.', 'kategori' => 'kegiatan', 'gambar' => 'galeri/pelatihan-kader.jpg'],

            // Infrastruktur (10 foto)
            ['judul' => 'Pembangunan Jalan Desa Tahap II', 'deskripsi' => 'Progres pembangunan jalan desa dengan pengaspalan sepanjang 2 kilometer untuk akses transportasi lebih baik.', 'kategori' => 'infrastruktur', 'gambar' => 'galeri/pembangunan-jalan.jpg'],
            ['judul' => 'Renovasi Balai Desa', 'deskripsi' => 'Renovasi total balai desa untuk meningkatkan kualitas pelayanan kepada masyarakat.', 'kategori' => 'infrastruktur', 'gambar' => 'galeri/renovasi-balai.jpg'],
            ['judul' => 'Pembangunan Gedung PAUD Baru', 'deskripsi' => 'Konstruksi gedung PAUD baru dengan fasilitas lengkap untuk pendidikan anak usia dini.', 'kategori' => 'infrastruktur', 'gambar' => 'galeri/pembangunan-paud.jpg'],
            ['judul' => 'Pemasangan Lampu Jalan Tenaga Surya', 'deskripsi' => 'Pemasangan 50 unit lampu jalan tenaga surya di berbagai titik strategis untuk penerangan malam hari.', 'kategori' => 'infrastruktur', 'gambar' => 'galeri/lampu-jalan.jpg'],
            ['judul' => 'Pembangunan Saluran Irigasi', 'deskripsi' => 'Pembangunan saluran irigasi baru untuk mengairi lahan pertanian seluas 100 hektar.', 'kategori' => 'infrastruktur', 'gambar' => 'galeri/saluran-irigasi.jpg'],
            ['judul' => 'Pembangunan Taman Desa', 'deskripsi' => 'Taman desa dengan fasilitas bermain anak dan gazebo untuk warga bersantai di sore hari.', 'kategori' => 'infrastruktur', 'gambar' => 'galeri/taman-desa.jpg'],
            ['judul' => 'Normalisasi Sungai Desa', 'deskripsi' => 'Kegiatan normalisasi dan pembersihan sungai untuk mencegah banjir di musim hujan.', 'kategori' => 'infrastruktur', 'gambar' => 'galeri/normalisasi-sungai.jpg'],
            ['judul' => 'Pembangunan Lapangan Futsal', 'deskripsi' => 'Lapangan futsal dengan rumput sintetis untuk menampung hobi olahraga pemuda desa.', 'kategori' => 'infrastruktur', 'gambar' => 'galeri/lapangan-futsal.jpg'],
            ['judul' => 'Pengecatan Gapura Desa', 'deskripsi' => 'Perawatan gapura desa dengan pengecatan ulang dan penambahan ornamen untuk memperindah tampilan.', 'kategori' => 'infrastruktur', 'gambar' => 'galeri/gapura-desa.jpg'],
            ['judul' => 'Pembangunan Pos Kamling', 'deskripsi' => 'Pembangunan pos kamling di setiap RT untuk meningkatkan keamanan lingkungan.', 'kategori' => 'infrastruktur', 'gambar' => 'galeri/pos-kamling.jpg'],

            // Budaya (5 foto)
            ['judul' => 'Festival Budaya Desa', 'deskripsi' => 'Festival budaya menampilkan seni tari tradisional, wayang kulit, dan kuliner khas daerah.', 'kategori' => 'budaya', 'gambar' => 'galeri/festival-budaya.jpg'],
            ['judul' => 'Pawai Budaya Nusantara', 'deskripsi' => 'Pawai budaya menampilkan kostum tradisional dari berbagai daerah di Indonesia.', 'kategori' => 'budaya', 'gambar' => 'galeri/pawai-budaya.jpg'],
            ['judul' => 'Malam Kesenian Desa', 'deskripsi' => 'Pertunjukan seni malam dengan penampilan penari tradisional dan musik gambang kromong.', 'kategori' => 'budaya', 'gambar' => 'galeri/malam-kesenian.jpg'],
            ['judul' => 'Pelatihan Tari Tradisional Anak', 'deskripsi' => 'Anak-anak desa belajar tari tradisional untuk melestarikan budaya lokal.', 'kategori' => 'budaya', 'gambar' => 'galeri/pelatihan-tari.jpg'],
            ['judul' => 'Pertunjukan Wayang Kulit', 'deskripsi' => 'Pertunjukan wayang kulit semalam suntuk dengan dalang kondang untuk hiburan warga.', 'kategori' => 'budaya', 'gambar' => 'galeri/wayang-kulit.jpg'],
        ];

        foreach ($galeriData as $index => $data) {
            $tanggal = Carbon::now()->subDays(rand(1, 180));

            Galeri::create([
                'admin_id' => $admin->id,
                'judul' => $data['judul'],
                'deskripsi' => $data['deskripsi'],
                'gambar' => $data['gambar'],
                'kategori' => $data['kategori'],
                'tanggal' => $tanggal,
                'is_active' => true,
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            $this->command->info('📸 Galeri ' . ($index + 1) . ': ' . $data['judul']);
        }

        $this->command->info('✅ Berhasil membuat 30 galeri foto dummy!');
    }
}
