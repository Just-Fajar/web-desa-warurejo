<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom lampiran ke tabel pengaduan_balasan
     * Untuk bukti penyelesaian dari admin (opsional)
     */
    public function up(): void
    {
        Schema::table('pengaduan_balasan', function (Blueprint $table) {
            $table->string('lampiran')->nullable()->after('isi');
        });
    }

    public function down(): void
    {
        Schema::table('pengaduan_balasan', function (Blueprint $table) {
            $table->dropColumn('lampiran');
        });
    }
};
