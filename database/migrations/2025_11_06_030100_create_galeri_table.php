<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel galeri untuk dokumentasi foto/video kegiatan desa
     * 
     * Kategori Galeri:
     * - kegiatan: Acara desa, gotong royong, rapat
     * - infrastruktur: Pembangunan jalan, gedung, fasilitas
     * - budaya: Upacara adat, seni budaya, festival
     * - umum: Foto-foto umum lainnya
     * 
     * Struktur:
     * - admin_id: Yang upload galeri
     * - judul: Judul/caption galeri
     * - deskripsi: Keterangan detail (optional)
     * - gambar: Featured image (1 gambar utama)
     * - kategori: Jenis galeri (enum)
     * - tanggal: Tanggal foto/video diambil
     * - is_active: Show/hide di public
     * 
     * Note: Untuk multiple images, lihat tabel galeri_images
     * (1 galeri bisa punya banyak foto dalam album)
     */
    public function up(): void
    {
        Schema::create('galeri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('gambar');
            $table->enum('kategori', ['kegiatan', 'infrastruktur', 'budaya', 'umum'])->default('umum');
            $table->date('tanggal')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('kategori');
            $table->index('is_active');
            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeri');
    }
};
