<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Perubahan kategori:
     * - Galeri: kegiatan, infrastruktur, budaya, umkm, lainnya -> kegiatan, pembangunan, budaya, keagamaan, sosial, lainnya
     * - Potensi: pertanian, peternakan, perikanan, umkm, wisata, kerajinan, lainnya -> pertanian, peternakan, perikanan, umkm, wisata, lainnya
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            // SQLite: kolom sudah VARCHAR/string, tidak perlu ALTER
            // Hanya update data yang sudah ada
            DB::table('galeri')->where('kategori', 'infrastruktur')->update(['kategori' => 'pembangunan']);
            DB::table('galeri')->where('kategori', 'umkm')->update(['kategori' => 'lainnya']);
            DB::table('potensi_desa')->where('kategori', 'kerajinan')->update(['kategori' => 'lainnya']);
        } else {
            // MySQL/MariaDB: Ubah tipe kolom dan migrasi data yang ada

            // 1. Galeri: ubah data lama ke kategori baru
            DB::table('galeri')->where('kategori', 'infrastruktur')->update(['kategori' => 'pembangunan']);
            DB::table('galeri')->where('kategori', 'umkm')->update(['kategori' => 'lainnya']);

            // 2. Galeri: ubah kolom ke VARCHAR dulu (agar aman), lalu bisa pakai string
            DB::statement("ALTER TABLE `galeri` MODIFY `kategori` VARCHAR(50) NOT NULL DEFAULT 'kegiatan'");

            // 3. Potensi: ubah data lama
            DB::table('potensi_desa')->where('kategori', 'kerajinan')->update(['kategori' => 'lainnya']);

            // 4. Potensi: ubah kolom ke VARCHAR
            DB::statement("ALTER TABLE `potensi_desa` MODIFY `kategori` VARCHAR(50) NOT NULL DEFAULT 'lainnya'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            // Kembalikan data
            DB::table('galeri')->where('kategori', 'pembangunan')->update(['kategori' => 'infrastruktur']);
            DB::table('galeri')->where('kategori', 'keagamaan')->update(['kategori' => 'lainnya']);
            DB::table('galeri')->where('kategori', 'sosial')->update(['kategori' => 'lainnya']);

            // Kembalikan kolom galeri ke enum asli
            DB::statement("ALTER TABLE `galeri` MODIFY `kategori` VARCHAR(50) NOT NULL DEFAULT 'kegiatan'");

            // Kembalikan kolom potensi ke enum asli
            DB::statement("ALTER TABLE `potensi_desa` MODIFY `kategori` VARCHAR(50) NOT NULL DEFAULT 'lainnya'");
        }
    }
};
