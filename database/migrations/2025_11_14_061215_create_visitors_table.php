<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel visitors untuk tracking pengunjung website (PRIVACY-SAFE)
     */
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45); // Support IPv4 & IPv6
            $table->text('user_agent');
            $table->string('device_fingerprint', 64)->index(); // SHA-256 hash
            $table->date('visit_date')->index(); // Untuk tracking harian
            $table->timestamp('last_visit_at'); // Timestamp kunjungan terakhir
            $table->integer('visit_count')->default(1); // Total kunjungan device ini
            $table->string('referer', 500)->nullable(); // Dari mana datangnya
            $table->string('page_url', 500); // Halaman yang dikunjungi
            $table->timestamps();

            // Composite index untuk query cepat
            $table->index(['device_fingerprint', 'visit_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
