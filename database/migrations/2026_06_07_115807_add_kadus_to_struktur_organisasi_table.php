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
        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->enum('level', ['kepala', 'sekretaris', 'kaur', 'staff_kaur', 'kasi', 'staff_kasi', 'kadus'])->default('staff_kaur')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->enum('level', ['kepala', 'sekretaris', 'kaur', 'staff_kaur', 'kasi', 'staff_kasi'])->default('staff_kaur')->change();
        });
    }
};
