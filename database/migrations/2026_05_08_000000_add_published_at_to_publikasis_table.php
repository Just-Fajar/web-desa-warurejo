<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom published_at ke tabel publikasis
     * untuk mendukung fitur penjadwalan (scheduled publishing)
     */
    public function up(): void
    {
        Schema::table('publikasis', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable()->after('status');
            $table->index('published_at');
        });

        // Migrasi data existing: set published_at = tanggal_publikasi untuk yang sudah published
        \DB::table('publikasis')
            ->where('status', 'published')
            ->whereNull('published_at')
            ->update(['published_at' => \DB::raw('tanggal_publikasi')]);
    }

    public function down(): void
    {
        Schema::table('publikasis', function (Blueprint $table) {
            $table->dropIndex(['published_at']);
            $table->dropColumn('published_at');
        });
    }
};
