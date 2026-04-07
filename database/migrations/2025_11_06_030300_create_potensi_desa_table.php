<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel potensi_desa untuk showcase kekayaan dan potensi desa
     * 
     * Kategori Potensi:
     * - pertanian: Sawah, ladang, tanaman unggulan
     * - peternakan: Sapi, kambing, ayam, dll
     * - perikanan: Tambak, kolam, hasil perikanan
     * - umkm: Usaha mikro kecil menengah warga
     * - wisata: Tempat wisata, destinasi menarik
     * - kerajinan: Kerajinan tangan, produk lokal
     * - lainnya: Potensi lain yang tidak termasuk kategori
     * 
     * Struktur:
     * - nama: Nama potensi
     * - slug: URL-friendly (SEO)
     * - kategori: Jenis potensi (enum)
     * - deskripsi: Detail lengkap potensi (support HTML)
     * - gambar: Featured image di storage/potensi
     * - lokasi: Alamat/lokasi potensi (optional)
     * - kontak: Nomor HP/WA untuk kontak (optional)
     * - is_active: Show/hide di public
     * - urutan: Custom sorting untuk prioritas tampilan
     * 
     * Indexes untuk filter:
     * - kategori: Filter by kategori di public
     * - is_active: Query hanya yang aktif
     * - urutan: Sort custom order
     */
    public function up(): void
    {
        Schema::create('potensi_desa', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->enum('kategori', [
                'pertanian', 
                'peternakan', 
                'perikanan', 
                'umkm', 
                'wisata', 
                'kerajinan', 
                'lainnya'
            ])->default('lainnya');
            $table->longText('deskripsi');
            $table->string('gambar')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('kontak')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('kategori');
            $table->index('is_active');
            $table->index('urutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potensi_desa');
    }
};
