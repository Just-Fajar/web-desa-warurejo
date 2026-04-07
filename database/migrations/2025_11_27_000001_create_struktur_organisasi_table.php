<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel struktur_organisasi untuk hierarki pemerintahan desa
     * 
     * Level Hierarki (dari atas ke bawah):
     * - kepala: Kepala Desa (top level)
     * - sekretaris: Sekretaris Desa
     * - kaur: Kepala Urusan (dibawah sekretaris)
     * - staff_kaur: Staff/Staf Urusan (dibawah kaur)
     * - kasi: Kepala Seksi
     * - staff_kasi: Staff/Staf Seksi (dibawah kasi)
     * 
     * Struktur:
     * - nama: Nama lengkap anggota
     * - jabatan: Jabatan detail (contoh: "Kaur Keuangan")
     * - foto: Foto profile di storage/struktur-organisasi
     * - deskripsi: Bio/keterangan (optional)
     * - urutan: Custom sorting dalam level yang sama
     * - level: Posisi hierarki (enum)
     * - atasan_id: Foreign key ke parent (untuk nested structure)
     * - is_active: Show/hide (untuk anggota non-aktif)
     * 
     * Self-referencing Foreign Key:
     * atasan_id → struktur_organisasi.id (untuk hierarki nested)
     * onDelete('set null'): Jika atasan dihapus, bawahan jadi null
     */
    public function up(): void
    {
        Schema::create('struktur_organisasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jabatan');
            $table->string('foto')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0); // untuk sorting
            $table->enum('level', ['kepala', 'sekretaris', 'kaur', 'staff_kaur', 'kasi', 'staff_kasi'])->default('staff_kaur');
            $table->foreignId('atasan_id')->nullable()->constrained('struktur_organisasi')->onDelete('set null'); // parent ID
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('urutan');
            $table->index('level');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('struktur_organisasi');
    }
};
