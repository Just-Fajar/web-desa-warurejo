<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ubah kolom isi di pengaduan_balasan menjadi nullable
     * Agar admin bisa kirim hanya lampiran tanpa teks
     */
    public function up(): void
    {
        Schema::table('pengaduan_balasan', function (Blueprint $table) {
            $table->text('isi')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pengaduan_balasan', function (Blueprint $table) {
            $table->text('isi')->nullable(false)->change();
        });
    }
};
