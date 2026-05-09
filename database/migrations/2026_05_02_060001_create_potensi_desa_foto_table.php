<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel foto galeri untuk potensi desa.
     * Menyimpan multiple foto per potensi dengan urutan.
     */
    public function up(): void
    {
        Schema::create('potensi_desa_foto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('potensi_desa_id')
                ->constrained('potensi_desa')
                ->cascadeOnDelete();
            $table->string('foto'); // path foto di storage
            $table->integer('urutan')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('potensi_desa_id');
            $table->index('urutan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('potensi_desa_foto');
    }
};
