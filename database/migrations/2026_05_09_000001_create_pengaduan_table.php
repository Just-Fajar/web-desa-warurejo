<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel pengaduan untuk Forum Pengaduan Publik
     */
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelapor', 100);
            $table->string('nomor_wa', 20);
            $table->string('judul', 255);
            $table->text('isi');
            $table->string('lokasi_kejadian', 255);
            $table->string('lampiran')->nullable(); // path file JPG/PNG/PDF
            $table->enum('status', ['Menunggu', 'Diproses', 'Selesai', 'Ditolak'])->default('Menunggu');
            $table->text('alasan_penolakan')->nullable(); // diisi admin saat menolak (opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
