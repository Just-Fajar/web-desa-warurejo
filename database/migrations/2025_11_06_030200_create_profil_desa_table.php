<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profil_desa', function (Blueprint $table) {
            $table->id();
            
            // Informasi Dasar
            $table->string('nama_desa');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('provinsi');
            $table->string('kode_pos', 10)->nullable();
            
            // Kontak
            $table->string('email')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->text('alamat_lengkap');
            
            // Visi Misi & Sejarah
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->longText('sejarah')->nullable();
            
            // Media
            $table->string('logo')->nullable();
            $table->string('gambar_kantor')->nullable();
            
            // Koordinat
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Demografis
            $table->decimal('luas_wilayah', 10, 2)->nullable()->comment('dalam hektar');
            $table->integer('jumlah_penduduk')->nullable();
            $table->integer('jumlah_kk')->nullable()->comment('Jumlah Kepala Keluarga');
            
            // Batas Wilayah
            $table->string('batas_utara')->nullable();
            $table->string('batas_selatan')->nullable();
            $table->string('batas_timur')->nullable();
            $table->string('batas_barat')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_desa');
    }
};
