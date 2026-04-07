<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if running on SQLite (testing environment)
        if (DB::connection()->getDriverName() === 'sqlite') {
            // SQLite doesn't support MODIFY, use recreate strategy
            Schema::table('galeri', function (Blueprint $table) {
                // Already has kategori as string from original migration
                // No modification needed for SQLite
            });
        } else {
            // MySQL/MariaDB: Ubah tipe kolom kategori dari enum ke string
            DB::statement("ALTER TABLE `galeri` MODIFY `kategori` VARCHAR(50) NOT NULL DEFAULT 'kegiatan'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if running on SQLite (testing environment)
        if (DB::connection()->getDriverName() === 'sqlite') {
            // SQLite doesn't support enum anyway
            Schema::table('galeri', function (Blueprint $table) {
                // No rollback needed for SQLite
            });
        } else {
            // MySQL/MariaDB: Kembalikan ke enum asli
            DB::statement("ALTER TABLE `galeri` MODIFY `kategori` ENUM('kegiatan', 'infrastruktur', 'budaya', 'umum') NOT NULL DEFAULT 'umum'");
        }
    }
};
