<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PotensiDesa;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class PotensiSeeder extends Seeder
{
    public function run(): void
    {
        if (!Storage::disk('public')->exists('potensi')) {
            Storage::disk('public')->makeDirectory('potensi');
        }
        $this->command->info('🌾 Membuat 30 potensi desa dummy...');
        $now = Carbon::now();

        // [nama, kategori, lokasi, wa, deskripsi, days, status]
        $items = [
            // PERTANIAN (5)
            ['Pertanian Padi Organik', 'pertanian', 'Dusun Selatan RT 01', '081234567001', 'Budidaya padi organik luas 150 Ha dengan teknik SRI ramah lingkungan.', 30, 'published'],
            ['Perkebunan Kopi Arabika', 'pertanian', 'Dusun Utara RT 08', '081234567002', 'Perkebunan kopi arabika dataran tinggi menghasilkan biji kopi premium.', 25, 'published'],
            ['Budidaya Sayuran Hidroponik', 'pertanian', 'Dusun Tengah RT 06', '081234567003', 'Pertanian sayur hidroponik modern menghasilkan selada dan bayam segar.', 20, 'published'],
            ['Budidaya Jamur Tiram', 'pertanian', 'Dusun Utara RT 07', '081234567004', 'Budidaya jamur tiram kumbung modern kapasitas 5.000 baglog.', 3, 'draft'],
            ['Kebun Buah Naga Organik', 'pertanian', 'Dusun Timur RT 09', '081234567005', 'Pengembangan kebun buah naga merah dan putih di lahan bekas tegalan.', 10, 'scheduled'],

            // PETERNAKAN (5)
            ['Peternakan Sapi Potong', 'peternakan', 'Dusun Barat RT 08', '081234567006', 'Peternakan sapi potong 200 ekor dengan pemeliharaan semi-intensif.', 28, 'published'],
            ['Peternakan Ayam Kampung', 'peternakan', 'Dusun Barat RT 11', '081234567007', 'Peternakan ayam kampung organik free-range populasi 3.000 ekor.', 22, 'published'],
            ['Peternakan Kambing Etawa', 'peternakan', 'Dusun Timur RT 14', '081234567008', 'Peternakan kambing etawa penghasil susu segar berkualitas.', 15, 'published'],
            ['Budidaya Lebah Madu Hutan', 'peternakan', 'Dusun Timur RT 06', '081234567009', 'Budidaya lebah madu hutan menghasilkan madu murni berkualitas.', 4, 'draft'],
            ['Peternakan Bebek Petelur', 'peternakan', 'Dusun Barat RT 10', '081234567010', 'Peternakan bebek petelur modern 800 ekor produksi 500 telur/hari.', 7, 'scheduled'],

            // PERIKANAN (5)
            ['Budidaya Lele Bioflok', 'perikanan', 'Dusun Selatan RT 06', '081234567011', 'Budidaya ikan lele sistem bioflok efisien dan ramah lingkungan.', 26, 'published'],
            ['Tambak Udang Vaname', 'perikanan', 'Dusun Barat RT 03', '081234567012', 'Tambak udang vaname semi-intensif dengan teknologi kincir air.', 18, 'published'],
            ['Kolam Ikan Nila Merah', 'perikanan', 'Dusun Utara RT 05', '081234567013', 'Budidaya ikan nila merah di kolam terpal dengan sistem bioflok.', 10, 'published'],
            ['Budidaya Ikan Gurame', 'perikanan', 'Dusun Timur RT 02', '081234567014', 'Pembesaran ikan gurame di kolam tanah dengan pakan alami.', 5, 'draft'],
            ['Budidaya Ikan Koi Hias', 'perikanan', 'Dusun Selatan RT 08', '081234567015', 'Budidaya ikan koi hias berkualitas untuk pasar kolektor.', 14, 'scheduled'],

            // UMKM (5)
            ['Batik Tulis Warurejo', 'umkm', 'Dusun Timur RT 01', '081234567016', 'Produksi batik tulis motif khas Warurejo bersertifikat HKI.', 24, 'published'],
            ['Keripik Tempe Kriuk', 'umkm', 'Dusun Barat RT 07', '081234567017', 'Produksi keripik tempe berbagai varian rasa ber-PIRT.', 17, 'published'],
            ['Konveksi Busana Muslim', 'umkm', 'Dusun Utara RT 09', '081234567018', 'Konveksi busana muslim desain modern bahan berkualitas.', 9, 'published'],
            ['Olahan Jamu Tradisional', 'umkm', 'Dusun Selatan RT 03', '081234567019', 'Produksi jamu tradisional kemasan modern berbagai varian.', 2, 'draft'],
            ['Bengkel Las Modern', 'umkm', 'Dusun Barat RT 02', '081234567020', 'Bengkel las fabrikasi besi melayani pagar, kanopi, konstruksi.', 21, 'scheduled'],

            // WISATA (5)
            ['Wisata Alam Embung Desa', 'wisata', 'Dusun Selatan RT 10', '081234567021', 'Embung desa destinasi wisata alam dengan camping ground dan pemancingan.', 27, 'published'],
            ['Agrowisata Kebun Stroberi', 'wisata', 'Dusun Timur RT 04', '081234567022', 'Agrowisata petik stroberi dengan kafe dan area foto instagramable.', 14, 'published'],
            ['Wisata Edukasi Peternakan', 'wisata', 'Dusun Utara RT 06', '081234567023', 'Wisata edukasi peternakan untuk anak sekolah dengan outbound.', 5, 'published'],
            ['Desa Wisata Kuliner Tradisional', 'wisata', 'Dusun Barat RT 01', '081234567024', 'Pengembangan desa wisata kuliner menyajikan makanan tradisional.', 3, 'draft'],
            ['Taman Bunga Desa Warurejo', 'wisata', 'Dusun Selatan RT 07', '081234567025', 'Pembangunan taman bunga 1 Ha destinasi wisata dan spot foto.', 30, 'scheduled'],

            // LAINNYA (5)
            ['Kelompok Simpan Pinjam Maju', 'lainnya', 'Balai Desa Warurejo', '081234567026', 'Kelompok simpan pinjam swadaya membantu permodalan usaha kecil.', 23, 'published'],
            ['Bank Sampah Bersih Sejahtera', 'lainnya', 'Dusun Timur RT 08', '081234567027', 'Bank sampah mengelola sampah rumah tangga menjadi produk ekonomis.', 11, 'published'],
            ['Sanggar Seni Budaya Warurejo', 'lainnya', 'Dusun Barat RT 04', '081234567028', 'Sanggar seni melestarikan kesenian tradisional untuk generasi muda.', 3, 'published'],
            ['Pos Pelayanan Teknologi Desa', 'lainnya', 'Dusun Utara RT 03', '081234567029', 'Pos pelayanan teknologi jasa perbaikan elektronik dan pelatihan komputer.', 4, 'draft'],
            ['Koperasi Wanita Mandiri', 'lainnya', 'Balai PKK Desa', '081234567030', 'Koperasi wanita memberdayakan ibu rumah tangga melalui keterampilan.', 15, 'scheduled'],
        ];

        foreach ($items as $i => $item) {
            [$nama, $kat, $lok, $wa, $desk, $days, $status] = $item;
            $pa = match($status) {
                'published' => $now->copy()->subDays($days),
                'scheduled' => $now->copy()->addDays($days),
                default => null,
            };
            $img = $this->dl('potensi', "potensi-{$i}.jpg", 800, 600, 200 + $i);

            PotensiDesa::create([
                'nama' => $nama, 'slug' => Str::slug($nama), 'kategori' => $kat,
                'deskripsi' => "<p><strong>{$nama}</strong> - {$desk}</p><p>Pemerintah Desa Warurejo terus mendukung pengembangan potensi ini melalui pendampingan dan pelatihan berkelanjutan.</p>",
                'gambar' => $img ?? '', 'lokasi' => $lok, 'whatsapp' => $wa,
                'nama_pengelola' => 'Pengelola ' . $nama, 'info_utama' => $lok,
                'link_maps' => null, 'status' => $status, 'published_at' => $pa,
                'urutan' => $i + 1, 'views' => $status === 'published' ? rand(20, 400) : 0,
            ]);
            $icon = match($status) { 'published'=>'✅', 'draft'=>'📝', 'scheduled'=>'⏰' };
            $this->command->info("  {$icon} [{$kat}] {$nama}");
        }
        $this->command->info('✅ 30 potensi: 6 kategori × 5 (18 pub, 6 draft, 6 sched)');
    }

    private function dl(string $f, string $fn, int $w, int $h, int $s): ?string
    {
        try {
            $r = Http::timeout(15)->get("https://picsum.photos/seed/{$s}/{$w}/{$h}");
            if ($r->successful()) { $p = "{$f}/{$fn}"; Storage::disk('public')->put($p, $r->body()); return $p; }
        } catch (\Exception $e) {}
        return "{$f}/{$fn}";
    }
}
