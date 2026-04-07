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
        // Add views to galeri table
        Schema::table('galeri', function (Blueprint $table) {
            $table->integer('views')->default(0)->after('is_active');
        });

        // Add views to potensi_desa table
        Schema::table('potensi_desa', function (Blueprint $table) {
            $table->integer('views')->default(0)->after('urutan');
        });

        // Add views to publikasis table
        Schema::table('publikasis', function (Blueprint $table) {
            $table->integer('views')->default(0)->after('jumlah_download');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galeri', function (Blueprint $table) {
            $table->dropColumn('views');
        });

        Schema::table('potensi_desa', function (Blueprint $table) {
            $table->dropColumn('views');
        });

        Schema::table('publikasis', function (Blueprint $table) {
            $table->dropColumn('views');
        });
    }
};
