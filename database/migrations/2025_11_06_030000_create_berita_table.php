<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel berita untuk menyimpan artikel/berita desa
     * 
     * Struktur:
     * - admin_id: Foreign key ke tabel admins (author)
     * - judul: Judul berita
     * - slug: URL-friendly version dari judul (unique)
     * - ringkasan: Excerpt/preview berita (optional)
     * - konten: Full content berita (support HTML dari TinyMCE)
     * - gambar_utama: Path gambar featured di storage/berita
     * - status: 'draft' atau 'published'
     * - views: Counter jumlah pembaca
     * - published_at: Timestamp kapan dipublish (untuk scheduled post)
     * 
     * Indexes untuk performance:
     * - status: Query berita published/draft
     * - published_at: Sort by tanggal publish
     * - created_at: Sort by tanggal buat
     */
    public function up(): void
    {
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('ringkasan')->nullable();
            $table->longText('konten');
            $table->string('gambar_utama')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->integer('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('published_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
