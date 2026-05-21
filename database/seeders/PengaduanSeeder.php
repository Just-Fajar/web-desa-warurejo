<?php

namespace Database\Seeders;

use App\Models\Pengaduan;
use App\Models\PengaduanBalasan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PengaduanSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📋 Membuat 30 pengaduan dummy...');
        $now = Carbon::now();

        // [nama, wa, judul, isi, lokasi, status, alasan_penolakan, days_ago]
        $items = [
            // MENUNGGU (8)
            ['Budi Santoso', '081234560001', 'Jalan Berlubang di RT 03', 'Jalan di depan RT 03 berlubang besar membahayakan pengendara motor terutama malam hari.', 'Jalan Dusun Timur RT 03', 'Menunggu', null, 2],
            ['Siti Aminah', '081234560002', 'Saluran Air Tersumbat', 'Saluran air di belakang rumah warga RT 05 tersumbat sampah menyebabkan genangan.', 'Dusun Barat RT 05', 'Menunggu', null, 3],
            ['Ahmad Fauzi', '081234560003', 'Pohon Tumbang Halangi Jalan', 'Pohon besar tumbang menghalangi jalan menuju Dusun Selatan setelah hujan angin.', 'Jalan Dusun Selatan', 'Menunggu', null, 1],
            ['Ratna Dewi', '081234560004', 'Lampu Jalan Mati', 'Beberapa lampu jalan utama desa mati belum diperbaiki selama 2 minggu.', 'Jalan Utama Desa', 'Menunggu', null, 5],
            ['Hendra Kurniawan', '081234560005', 'Sampah Menumpuk di TPS', 'TPS dekat pasar desa penuh dan sampah menumpuk hingga badan jalan.', 'TPS Pasar Desa', 'Menunggu', null, 4],
            ['Wati Susilowati', '081234560006', 'Air PDAM Keruh', 'Air PDAM ke rumah warga RT 07 berwarna keruh kecoklatan sudah 3 hari.', 'Dusun Utara RT 07', 'Menunggu', null, 3],
            ['Joko Prasetyo', '081234560007', 'Pagar Makam Rusak', 'Pagar makam desa sisi timur rusak roboh sehingga hewan masuk area pemakaman.', 'Makam Desa Warurejo', 'Menunggu', null, 6],
            ['Nur Hasanah', '081234560008', 'Fasilitas Posyandu Kurang', 'Fasilitas posyandu RT 09 minim tidak ada timbangan bayi layak.', 'Posyandu RT 09', 'Menunggu', null, 7],

            // DIPROSES (8)
            ['Bambang Widodo', '081234560009', 'Jembatan Retak Berbahaya', 'Jembatan penghubung Dusun Barat-Timur retak di tengah berbahaya kendaraan berat.', 'Jembatan Dusun Barat', 'Diproses', null, 14],
            ['Sri Wahyuni', '081234560010', 'Tanggul Sungai Longsor', 'Tanggul sungai belakang RT 02 longsor mengancam rumah warga bantaran.', 'Bantaran Sungai RT 02', 'Diproses', null, 12],
            ['Dedi Supriadi', '081234560011', 'Kerusakan Pipa Air Bersih', 'Pipa distribusi air bersih Dusun Selatan bocor besar menyebabkan jalan becek.', 'Dusun Selatan RT 04', 'Diproses', null, 10],
            ['Eko Supriyanto', '081234560012', 'Pengaspalan Jalan Gang', 'Jalan gang RT 06 masih tanah sangat licin musim hujan membahayakan anak sekolah.', 'Gang RT 06 Dusun Utara', 'Diproses', null, 15],
            ['Kartini Sari', '081234560013', 'Listrik Sering Padam', 'Listrik area RT 08 sering padam tanpa pemberitahuan mengganggu aktivitas warga.', 'Dusun Timur RT 08', 'Diproses', null, 11],
            ['Agus Riyanto', '081234560014', 'Drainase Meluap Saat Hujan', 'Drainase depan SDN Warurejo meluap saat hujan menggenangi halaman sekolah.', 'SDN Warurejo', 'Diproses', null, 9],
            ['Lastri Handayani', '081234560015', 'MCK Umum Perlu Perbaikan', 'MCK umum dekat pasar memprihatinkan pintu rusak air tidak lancar.', 'MCK Pasar Desa', 'Diproses', null, 13],
            ['Wahyu Hidayat', '081234560016', 'Plang Nama Jalan Hilang', 'Plang nama jalan persimpangan desa hilang membuat pengunjung tersesat.', 'Persimpangan Utama', 'Diproses', null, 8],

            // SELESAI (8)
            ['Surya Darma', '081234560017', 'Perbaikan Gorong-gorong', 'Gorong-gorong jalan masuk dusun timur amblas perlu diperbaiki.', 'Jalan Masuk Dusun Timur', 'Selesai', null, 45],
            ['Rina Puspita', '081234560018', 'Penerangan Jalan Dusun', 'Permintaan pemasangan lampu penerangan jalan dusun selatan yang gelap.', 'Jalan Dusun Selatan', 'Selesai', null, 40],
            ['Subagyo Hadi', '081234560019', 'Perbaikan Atap Balai RT', 'Atap balai pertemuan RT 03 bocor parah perlu segera diganti.', 'Balai RT 03', 'Selesai', null, 38],
            ['Firman Syah', '081234560020', 'Saluran Irigasi Tersumbat', 'Saluran irigasi ke persawahan tersumbat material longsoran.', 'Saluran Irigasi Dusun Barat', 'Selesai', null, 35],
            ['Yuli Astuti', '081234560021', 'Pemotongan Rumput Liar', 'Rumput liar sepanjang jalan sekolah sudah tinggi menutupi pandangan.', 'Jalan Menuju SD/SMP', 'Selesai', null, 30],
            ['Irwan Setiawan', '081234560022', 'Penambahan Tempat Sampah', 'Permintaan penambahan tempat sampah di area publik lapangan desa.', 'Lapangan Desa', 'Selesai', null, 28],
            ['Maya Kusuma', '081234560023', 'Perbaikan Trotoar Rusak', 'Trotoar depan kantor desa rusak tidak rata membahayakan pejalan kaki.', 'Kantor Desa', 'Selesai', null, 25],
            ['Totok Hariyanto', '081234560024', 'Pembersihan Saluran Got', 'Got sepanjang jalan RT 10 penuh lumpur dan sampah menimbulkan bau.', 'Jalan RT 10', 'Selesai', null, 20],

            // DITOLAK (6)
            ['Darmawan Putra', '081234560025', 'Pelebaran Jalan Pribadi', 'Meminta pemerintah desa melebarkan jalan di depan rumah pribadi saya.', 'Rumah Pribadi RT 01', 'Ditolak', 'Jalan yang dimaksud adalah akses pribadi, bukan jalan desa. Pelebaran jalan pribadi menjadi tanggung jawab pemilik lahan.', 30],
            ['Sumiati Wulan', '081234560026', 'Bangun Pos Ronda Tanah Sengketa', 'Mengusulkan pembangunan pos ronda di tanah kosong dekat RT 06.', 'Tanah Kosong RT 06', 'Ditolak', 'Tanah masih dalam sengketa kepemilikan sehingga tidak dapat digunakan untuk pembangunan fasilitas umum.', 25],
            ['Usman Hakim', '081234560027', 'Pembuatan Speedbump Berlebihan', 'Meminta 10 speedbump di jalan gang sepanjang 200 meter.', 'Gang RT 04', 'Ditolak', 'Jumlah terlalu banyak untuk panjang jalan tersebut. Dipertimbangkan 2 speedbump sesuai standar.', 22],
            ['Ani Rahayu', '081234560028', 'Tutup Akses Jalan Umum', 'Meminta penutupan jalan umum samping rumah karena mengganggu privasi.', 'Jalan Umum RT 02', 'Ditolak', 'Jalan umum desa digunakan banyak warga sehingga tidak dapat ditutup aksesnya.', 18],
            ['Sari Mulyani', '081234560029', 'Pindahkan TPS ke Desa Lain', 'Meminta TPS dekat rumah dipindahkan ke desa lain karena bau.', 'TPS RT 05', 'Ditolak', 'TPS fasilitas desa tidak dapat dipindahkan. Frekuensi pengangkutan akan ditingkatkan.', 15],
            ['Ridwan Kamil', '081234560030', 'Subsidi Pribadi untuk Usaha', 'Meminta subsidi dana desa untuk modal usaha warung makan pribadi.', 'Warung RT 09', 'Ditolak', 'Dana desa tidak untuk subsidi usaha perseorangan. Silakan daftar program UMKM melalui BUMDes.', 10],
        ];

        foreach ($items as $item) {
            $pengaduan = Pengaduan::create([
                'nama_pelapor' => $item[0], 'nomor_wa' => $item[1], 'judul' => $item[2],
                'isi' => $item[3], 'lokasi_kejadian' => $item[4], 'lampiran' => null,
                'status' => $item[5], 'alasan_penolakan' => $item[6],
                'created_at' => $now->copy()->subDays($item[7]),
                'updated_at' => $now->copy()->subDays(max(0, $item[7] - 3)),
            ]);

            // Balasan admin untuk Diproses & Selesai
            if (in_array($item[5], ['Diproses', 'Selesai'])) {
                PengaduanBalasan::create([
                    'pengaduan_id' => $pengaduan->id,
                    'isi' => 'Terima kasih atas laporan Anda. Pengaduan telah kami terima dan sedang ditindaklanjuti.',
                    'is_admin' => true,
                    'created_at' => $now->copy()->subDays($item[7] - 2),
                ]);
            }
            if ($item[5] === 'Selesai') {
                PengaduanBalasan::create([
                    'pengaduan_id' => $pengaduan->id,
                    'isi' => 'Pengaduan telah selesai ditindaklanjuti. Terima kasih atas partisipasi membangun Desa Warurejo.',
                    'is_admin' => true,
                    'created_at' => $now->copy()->subDays($item[7] - 5),
                ]);
            }

            $icon = match ($item[5]) {
                'Menunggu' => '🕐', 'Diproses' => '🔄', 'Selesai' => '✅', 'Ditolak' => '❌'
            };
            $this->command->info("  {$icon} [{$item[5]}] {$item[2]}");
        }
        $this->command->info('✅ 30 pengaduan: 8 menunggu, 8 diproses, 8 selesai, 6 ditolak');
    }
}
