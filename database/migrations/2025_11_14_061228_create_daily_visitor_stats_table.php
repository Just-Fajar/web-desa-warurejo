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
        Schema::create('daily_visitor_stats', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique(); // Tanggal statistik
            $table->integer('unique_visitors')->default(0); // Unique visitors hari ini
            $table->integer('page_views')->default(0); // Total page views
            $table->timestamps();
            
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_visitor_stats');
    }
};
