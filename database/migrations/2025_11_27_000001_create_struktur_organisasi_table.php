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
