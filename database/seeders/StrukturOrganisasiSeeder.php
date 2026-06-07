<?php

namespace Database\Seeders;

use App\Models\StrukturOrganisasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StrukturOrganisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👥 Memulai seeding Struktur Organisasi...');

        // Ensure storage directory exists
        if (!Storage::disk('public')->exists('struktur-organisasi')) {
            Storage::disk('public')->makeDirectory('struktur-organisasi');
        }

        // Clean up existing records and files
        $existing = StrukturOrganisasi::all();
        foreach ($existing as $item) {
            if ($item->foto && Storage::disk('public')->exists($item->foto)) {
                Storage::disk('public')->delete($item->foto);
            }
        }
        StrukturOrganisasi::truncate();

        // 1. Kepala Desa
        $this->command->info('  Creating: Kepala Desa');
        $kepala = StrukturOrganisasi::create([
            'nama' => 'H. Ahmad Fauzi, S.IP',
            'jabatan' => 'Kepala Desa',
            'deskripsi' => 'Bertanggung jawab memimpin penyelenggaraan pemerintahan desa berdasarkan kebijakan yang ditetapkan bersama Badan Permusyawaratan Desa (BPD).',
            'level' => StrukturOrganisasi::LEVEL_KEPALA,
            'urutan' => 1,
            'is_active' => true,
            'foto' => $this->downloadFoto('kepala.jpg', 121),
            'atasan_id' => null,
            'periode_jabatan' => '2020 - 2028',
            'whatsapp' => '081234567890',
        ]);

        // 2. Sekretaris Desa
        $this->command->info('  Creating: Sekretaris Desa');
        $sekretaris = StrukturOrganisasi::create([
            'nama' => 'Siti Aminah, S.E.',
            'jabatan' => 'Sekretaris Desa',
            'deskripsi' => 'Bertanggung jawab dalam koordinasi administrasi pemerintahan desa dan memberikan pelayanan administratif kepada Kepala Desa.',
            'level' => StrukturOrganisasi::LEVEL_SEKRETARIS,
            'urutan' => 1,
            'is_active' => true,
            'foto' => $this->downloadFoto('sekretaris.jpg', 242),
            'atasan_id' => $kepala->id,
            'periode_jabatan' => '2020 - 2028',
            'whatsapp' => '081234567891',
        ]);

        // 3. Kepala Urusan (Kaur)
        $this->command->info('  Creating: Para Kepala Urusan (Kaur)');
        $kaurKeuangan = StrukturOrganisasi::create([
            'nama' => 'Budi Santoso',
            'jabatan' => 'Kaur Keuangan',
            'deskripsi' => 'Mengelola administrasi keuangan desa, mengurus penerimaan, penyimpanan, penyetoran, dan pertanggungjawaban keuangan APBDes.',
            'level' => StrukturOrganisasi::LEVEL_KAUR,
            'urutan' => 1,
            'is_active' => true,
            'foto' => $this->downloadFoto('kaur_keuangan.jpg', 363),
            'atasan_id' => $sekretaris->id,
            'periode_jabatan' => '2021 - 2027',
            'whatsapp' => '081234567892',
        ]);

        $kaurPerencanaan = StrukturOrganisasi::create([
            'nama' => 'Rina Wijayanti, S.T.',
            'jabatan' => 'Kaur Perencanaan',
            'deskripsi' => 'Menyusun rencana pembangunan desa, anggaran belanja, mengoordinasikan penyusunan RPJMDes dan RKPDes, serta pemantauan program.',
            'level' => StrukturOrganisasi::LEVEL_KAUR,
            'urutan' => 2,
            'is_active' => true,
            'foto' => $this->downloadFoto('kaur_perencanaan.jpg', 484),
            'atasan_id' => $sekretaris->id,
            'periode_jabatan' => '2021 - 2027',
            'whatsapp' => '081234567893',
        ]);

        $kaurTU = StrukturOrganisasi::create([
            'nama' => 'Eko Prasetyo',
            'jabatan' => 'Kaur Tata Usaha & Umum',
            'deskripsi' => 'Mengelola surat-menyurat, pengarsipan dokumen desa, inventarisasi aset desa, dan penyediaan sarana penunjang kegiatan kantor.',
            'level' => StrukturOrganisasi::LEVEL_KAUR,
            'urutan' => 3,
            'is_active' => true,
            'foto' => $this->downloadFoto('kaur_tu.jpg', 605),
            'atasan_id' => $sekretaris->id,
            'periode_jabatan' => '2022 - 2028',
            'whatsapp' => '081234567894',
        ]);

        // 4. Kepala Seksi (Kasi)
        $this->command->info('  Creating: Para Kepala Seksi (Kasi)');
        $kasiPemerintahan = StrukturOrganisasi::create([
            'nama' => 'Agus Hermawan',
            'jabatan' => 'Kasi Pemerintahan',
            'deskripsi' => 'Mengelola administrasi kependudukan desa, menjaga ketentraman dan ketertiban umum masyarakat desa, serta membina rukun tetangga.',
            'level' => StrukturOrganisasi::LEVEL_KASI,
            'urutan' => 1,
            'is_active' => true,
            'foto' => $this->downloadFoto('kasi_pemerintahan.jpg', 726),
            'atasan_id' => $sekretaris->id,
            'periode_jabatan' => '2020 - 2026',
            'whatsapp' => '081234567895',
        ]);

        $kasiKesejahteraan = StrukturOrganisasi::create([
            'nama' => 'Sri Mulyani',
            'jabatan' => 'Kasi Kesejahteraan',
            'deskripsi' => 'Melaksanakan program pembangunan sarana prasarana fisik desa, membina bidang keagamaan, serta mengelola bantuan kesejahteraan sosial.',
            'level' => StrukturOrganisasi::LEVEL_KASI,
            'urutan' => 2,
            'is_active' => true,
            'foto' => $this->downloadFoto('kasi_kesejahteraan.jpg', 847),
            'atasan_id' => $sekretaris->id,
            'periode_jabatan' => '2020 - 2026',
            'whatsapp' => '081234567896',
        ]);

        $kasiPelayanan = StrukturOrganisasi::create([
            'nama' => 'Joko Susilo',
            'jabatan' => 'Kasi Pelayanan',
            'deskripsi' => 'Melakukan pembinaan kepemudaan, olahraga, kebudayaan desa, serta memfasilitasi program sosial kemasyarakatan dan pelayanan warga.',
            'level' => StrukturOrganisasi::LEVEL_KASI,
            'urutan' => 3,
            'is_active' => true,
            'foto' => $this->downloadFoto('kasi_pelayanan.jpg', 968),
            'atasan_id' => $sekretaris->id,
            'periode_jabatan' => '2021 - 2027',
            'whatsapp' => '081234567897',
        ]);

        // 5. Staff Kaur
        $this->command->info('  Creating: Para Staff Kaur');
        $staffKeuangan = StrukturOrganisasi::create([
            'nama' => 'Dewi Lestari',
            'jabatan' => 'Staff Kaur Keuangan',
            'deskripsi' => 'Membantu Kaur Keuangan dalam pencatatan transaksi harian dan administrasi pembukuan kas desa.',
            'level' => StrukturOrganisasi::LEVEL_STAFF_KAUR,
            'urutan' => 1,
            'is_active' => true,
            'foto' => $this->downloadFoto('staff_keuangan.jpg', 108),
            'atasan_id' => $kaurKeuangan->id,
            'periode_jabatan' => '2022 - 2028',
            'whatsapp' => '081234567898',
        ]);

        $staffTU = StrukturOrganisasi::create([
            'nama' => 'Hadi Wibowo',
            'jabatan' => 'Staff Kaur Tata Usaha & Umum',
            'deskripsi' => 'Membantu Kaur Tata Usaha & Umum dalam pelayanan operasional, kebersihan kantor, dan pengelolaan sarana prasarana.',
            'level' => StrukturOrganisasi::LEVEL_STAFF_KAUR,
            'urutan' => 2,
            'is_active' => true,
            'foto' => $this->downloadFoto('staff_tu.jpg', 229),
            'atasan_id' => $kaurTU->id,
            'periode_jabatan' => '2022 - 2028',
            'whatsapp' => '081234567899',
        ]);

        // 6. Staff Kasi
        $this->command->info('  Creating: Para Staff Kasi');
        $staffPem = StrukturOrganisasi::create([
            'nama' => 'Andi Wijaya',
            'jabatan' => 'Staff Kasi Pemerintahan',
            'deskripsi' => 'Membantu Kasi Pemerintahan dalam pengisian buku administrasi kependudukan dan registrasi kartu keluarga warga.',
            'level' => StrukturOrganisasi::LEVEL_STAFF_KASI,
            'urutan' => 1,
            'is_active' => true,
            'foto' => $this->downloadFoto('staff_pemerintahan.jpg', 350),
            'atasan_id' => $kasiPemerintahan->id,
            'periode_jabatan' => '2021 - 2027',
            'whatsapp' => '081234567900',
        ]);

        $staffKesra = StrukturOrganisasi::create([
            'nama' => 'Siti Rahma',
            'jabatan' => 'Staff Kasi Kesejahteraan',
            'deskripsi' => 'Membantu Kasi Kesejahteraan dalam pendataan program keluarga harapan, penyaluran bansos, dan pelaporan bansos.',
            'level' => StrukturOrganisasi::LEVEL_STAFF_KASI,
            'urutan' => 2,
            'is_active' => true,
            'foto' => $this->downloadFoto('staff_kesejahteraan.jpg', 471),
            'atasan_id' => $kasiKesejahteraan->id,
            'periode_jabatan' => '2021 - 2027',
            'whatsapp' => '081234567901',
        ]);

        $staffPelayanan = StrukturOrganisasi::create([
            'nama' => 'Lutfi Hakim',
            'jabatan' => 'Staff Kasi Pelayanan',
            'deskripsi' => 'Membantu Kasi Pelayanan dalam administrasi surat pengantar pernikahan, izin usaha mikro, dan layanan permohonan surat keterangan.',
            'level' => StrukturOrganisasi::LEVEL_STAFF_KASI,
            'urutan' => 3,
            'is_active' => true,
            'foto' => $this->downloadFoto('staff_pelayanan.jpg', 592),
            'atasan_id' => $kasiPelayanan->id,
            'periode_jabatan' => '2022 - 2028',
            'whatsapp' => '081234567902',
        ]);

        // 7. Kepala Dusun (Kadus)
        $this->command->info('  Creating: Para Kepala Dusun (Kadus)');
        StrukturOrganisasi::create([
            'nama' => 'Hendra Setiawan',
            'jabatan' => 'Kepala Dusun Barat',
            'deskripsi' => 'Memimpin dan mengoordinasikan penyelenggaraan kegiatan pemerintahan, pembangunan, serta kemasyarakatan di wilayah Dusun Barat.',
            'level' => StrukturOrganisasi::LEVEL_KADUS,
            'urutan' => 1,
            'is_active' => true,
            'foto' => $this->downloadFoto('kadus_barat.jpg', 613),
            'atasan_id' => $sekretaris->id,
            'periode_jabatan' => '2020 - 2028',
            'whatsapp' => '081234567903',
        ]);

        StrukturOrganisasi::create([
            'nama' => 'Yusuf Subagyo',
            'jabatan' => 'Kepala Dusun Timur',
            'deskripsi' => 'Memimpin dan mengoordinasikan penyelenggaraan kegiatan pemerintahan, pembangunan, serta kemasyarakatan di wilayah Dusun Timur.',
            'level' => StrukturOrganisasi::LEVEL_KADUS,
            'urutan' => 2,
            'is_active' => true,
            'foto' => $this->downloadFoto('kadus_timur.jpg', 734),
            'atasan_id' => $sekretaris->id,
            'periode_jabatan' => '2020 - 2028',
            'whatsapp' => '081234567904',
        ]);

        StrukturOrganisasi::create([
            'nama' => 'Fatkhur Rahman',
            'jabatan' => 'Kepala Dusun Utara',
            'deskripsi' => 'Memimpin dan mengoordinasikan penyelenggaraan kegiatan pemerintahan, pembangunan, serta kemasyarakatan di wilayah Dusun Utara.',
            'level' => StrukturOrganisasi::LEVEL_KADUS,
            'urutan' => 3,
            'is_active' => true,
            'foto' => $this->downloadFoto('kadus_utara.jpg', 855),
            'atasan_id' => $sekretaris->id,
            'periode_jabatan' => '2021 - 2029',
            'whatsapp' => '081234567905',
        ]);

        StrukturOrganisasi::create([
            'nama' => 'Slamet Riyadi',
            'jabatan' => 'Kepala Dusun Selatan',
            'deskripsi' => 'Memimpin dan mengoordinasikan penyelenggaraan kegiatan pemerintahan, pembangunan, serta kemasyarakatan di wilayah Dusun Selatan.',
            'level' => StrukturOrganisasi::LEVEL_KADUS,
            'urutan' => 4,
            'is_active' => true,
            'foto' => $this->downloadFoto('kadus_selatan.jpg', 976),
            'atasan_id' => $sekretaris->id,
            'periode_jabatan' => '2021 - 2029',
            'whatsapp' => '081234567906',
        ]);

        $this->command->info('✅ Struktur Organisasi dummy data seeded successfully!');
    }

    /**
     * Download portrait dummy photo using Picsum Photos
     */
    private function downloadFoto(string $filename, int $seedId): ?string
    {
        try {
            // Picsum Photos is used with a specific seed to ensure a consistent, valid portrait image
            $response = Http::timeout(10)->get("https://picsum.photos/seed/avatar-{$seedId}/300/300");
            if ($response->successful()) {
                $path = "struktur-organisasi/{$filename}";
                Storage::disk('public')->put($path, $response->body());
                return $path;
            }
        } catch (\Exception $e) {
            Log::warning("Gagal mengunduh foto dummy untuk {$filename}: " . $e->getMessage());
        }
        return null;
    }
}
