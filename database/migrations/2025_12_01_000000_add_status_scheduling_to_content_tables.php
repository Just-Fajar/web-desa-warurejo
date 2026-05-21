<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. BERITA: Add 'scheduled' to status enum
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE berita MODIFY COLUMN status ENUM('draft', 'scheduled', 'published') DEFAULT 'draft'");
        }

        // 2. GALERI: Add status + published_at, migrate is_active data, drop is_active
        Schema::table('galeri', function (Blueprint $table) {
            $table->enum('status', ['draft', 'scheduled', 'published'])->default('published')->after('kategori');
            $table->timestamp('published_at')->nullable()->after('status');
        });

        // Migrate existing galeri data
        DB::table('galeri')->where('is_active', true)->update([
            'status' => 'published',
            'published_at' => DB::raw('created_at'),
        ]);
        DB::table('galeri')->where('is_active', false)->update([
            'status' => 'draft',
        ]);

        Schema::table('galeri', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropColumn('is_active');
            $table->index('status');
            $table->index('published_at');
        });

        // 3. POTENSI_DESA: Add status + published_at, migrate is_active data, drop is_active
        Schema::table('potensi_desa', function (Blueprint $table) {
            $table->enum('status', ['draft', 'scheduled', 'published'])->default('published')->after('kontak');
            $table->timestamp('published_at')->nullable()->after('status');
        });

        // Migrate existing potensi data
        DB::table('potensi_desa')->where('is_active', true)->update([
            'status' => 'published',
            'published_at' => DB::raw('created_at'),
        ]);
        DB::table('potensi_desa')->where('is_active', false)->update([
            'status' => 'draft',
        ]);

        Schema::table('potensi_desa', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropColumn('is_active');
            $table->index('status');
            $table->index('published_at');
        });

        // 4. PUBLIKASIS: Add 'scheduled' to status enum
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE publikasis MODIFY COLUMN status ENUM('draft', 'scheduled', 'published') DEFAULT 'published'");
        }
    }

    public function down(): void
    {
        // Revert BERITA
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE berita MODIFY COLUMN status ENUM('draft', 'published') DEFAULT 'draft'");
        }

        // Revert GALERI
        Schema::table('galeri', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('kategori');
        });
        DB::table('galeri')->where('status', 'published')->update(['is_active' => true]);
        DB::table('galeri')->where('status', '!=', 'published')->update(['is_active' => false]);
        Schema::table('galeri', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['published_at']);
            $table->dropColumn(['status', 'published_at']);
            $table->index('is_active');
        });

        // Revert POTENSI_DESA
        Schema::table('potensi_desa', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('kontak');
        });
        DB::table('potensi_desa')->where('status', 'published')->update(['is_active' => true]);
        DB::table('potensi_desa')->where('status', '!=', 'published')->update(['is_active' => false]);
        Schema::table('potensi_desa', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['published_at']);
            $table->dropColumn(['status', 'published_at']);
            $table->index('is_active');
        });

        // Revert PUBLIKASIS
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE publikasis MODIFY COLUMN status ENUM('draft', 'published') DEFAULT 'published'");
        }
    }
};
