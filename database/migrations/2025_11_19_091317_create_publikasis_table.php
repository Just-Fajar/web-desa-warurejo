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
        Schema::create('publikasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->enum('kategori', ['APBDes', 'RPJMDes', 'RKPDes']);
            $table->year('tahun');
            $table->text('deskripsi')->nullable();
            $table->string('file_dokumen'); // Path ke file PDF
            $table->string('thumbnail')->nullable(); // Optional thumbnail
            $table->date('tanggal_publikasi');
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->integer('jumlah_download')->default(0);
            $table->timestamps();
            
            // Indexes
            $table->index('kategori');
            $table->index('tahun');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publikasis');
    }
};
