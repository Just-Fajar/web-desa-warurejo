<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Overhaul tabel potensi_desa:
     * - Hapus kolom: kontak
     * - Tambah kolom: info_utama, nama_pengelola, link_maps
     * - Ubah: gambar & whatsapp jadi NOT NULL
     * - Update enum kategori: hapus 'kerajinan'
     */
    public function up(): void
    {
        // 1. Update enum kategori — hapus 'kerajinan'
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE potensi_desa MODIFY COLUMN kategori ENUM('pertanian','peternakan','perikanan','umkm','wisata','lainnya') DEFAULT 'lainnya'");
        }

        // 2. Tambah kolom baru
        Schema::table('potensi_desa', function (Blueprint $table) {
            $table->string('info_utama')->nullable()->after('lokasi');
            $table->string('nama_pengelola')->default('')->after('info_utama');
            $table->string('link_maps')->nullable()->after('whatsapp');
        });

        // 3. Set default values for existing data before making columns required
        DB::table('potensi_desa')->whereNull('gambar')->orWhere('gambar', '')->update(['gambar' => '']);
        DB::table('potensi_desa')->whereNull('whatsapp')->orWhere('whatsapp', '')->update(['whatsapp' => '']);

        // 4. Ubah gambar & whatsapp jadi NOT NULL
        Schema::table('potensi_desa', function (Blueprint $table) {
            $table->string('gambar')->nullable(false)->default('')->change();
            $table->string('whatsapp', 20)->nullable(false)->default('')->change();
        });

        // 5. Hapus kolom kontak
        Schema::table('potensi_desa', function (Blueprint $table) {
            $table->dropColumn('kontak');
        });
    }

    public function down(): void
    {
        // Restore kontak
        Schema::table('potensi_desa', function (Blueprint $table) {
            $table->string('kontak')->nullable()->after('lokasi');
        });

        // Revert gambar & whatsapp to nullable
        Schema::table('potensi_desa', function (Blueprint $table) {
            $table->string('gambar')->nullable()->change();
            $table->string('whatsapp', 20)->nullable()->change();
        });

        // Drop new columns
        Schema::table('potensi_desa', function (Blueprint $table) {
            $table->dropColumn(['info_utama', 'nama_pengelola', 'link_maps']);
        });

        // Restore kategori enum with kerajinan
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE potensi_desa MODIFY COLUMN kategori ENUM('pertanian','peternakan','perikanan','umkm','wisata','kerajinan','lainnya') DEFAULT 'lainnya'");
        }
    }
};
