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
     * 
     * Features:
     * - Anonymous tracking: Tidak simpan data personal
     * - Device fingerprinting: Kombinasi IP + User-Agent + Accept-Language (di-hash SHA-256)
     * - Daily counting: 1 device = 1 unique visitor per hari (anti spam)
     * - Page tracking: Halaman apa yang dikunjungi
     * 
     * Struktur:
     * - ip_address: IP yang di-anonymize (last octet = 0), support IPv4 & IPv6
     * - user_agent: Browser info untuk analytics
     * - device_fingerprint: SHA-256 hash untuk identify unique device
     * - visit_date: Tanggal kunjungan (untuk daily stats)
     * - last_visit_at: Timestamp kunjungan terakhir
     * - visit_count: Total berapa kali device ini visit
     * - referer: Dari mana datangnya (Google, Facebook, dll)
     * - page_url: Halaman yang dikunjungi
     * 
     * Privacy Compliance:
     * - GDPR compliant (IP anonymized)
     * - No personal identifiable information (PII)
     * - Hash tidak bisa di-reverse engineer
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
