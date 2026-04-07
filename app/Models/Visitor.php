<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Visitor Model
 * 
 * Tracking visitor website secara anonymous (GDPR compliant)
 * IP di-hash dengan device_fingerprint untuk privacy
 * 
 * FILLABLE:
 * - ip_address: Hashed IP (SHA256)
 * - user_agent: Browser info (untuk detect device type)
 * - device_fingerprint: Unique identifier per device (hash)
 * - visit_date: Tanggal kunjungan (date)
 * - last_visit_at: Timestamp last visit (untuk detect returning)
 * - visit_count: Total visit dari device ini
 * - referer: Dari mana visitor datang (Google/Direct/Social)
 * - page_url: URL halaman yang dikunjungi
 * 
 * SCOPES:
 * - today(): Filter visitor hari ini
 * - betweenDates($start, $end): Filter range tanggal
 * 
 * STATIC METHODS:
 * - uniqueVisitorsCount($date): Hitung unique visitor by device_fingerprint
 * 
 * PRIVACY:
 * - IP address di-hash, tidak store raw IP
 * - Device fingerprint dari kombinasi IP + User Agent + random salt
 * - Tidak tracking personal information (nama, email, dll)
 * 
 * ANALYTICS USE:
 * - Dashboard visitor charts (daily/weekly/monthly)
 * - Traffic source analysis (referer)
 * - Device type statistics (user_agent parsing)
 * 
 * Created by: TrackVisitor middleware
 * Location: app/Http/Middleware/TrackVisitor.php
 */
class Visitor extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'device_fingerprint',
        'visit_date',
        'last_visit_at',
        'visit_count',
        'referer',
        'page_url',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'last_visit_at' => 'datetime',
        'visit_count' => 'integer',
    ];

    /**
     * Scope untuk query visitor hari ini
     */
    public function scopeToday($query)
    {
        return $query->where('visit_date', Carbon::today()->toDateString());
    }

    /**
     * Scope untuk query visitor dalam rentang tanggal
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('visit_date', [$startDate, $endDate]);
    }

    /**
     * Get unique visitors count
     */
    public static function uniqueVisitorsCount($date = null)
    {
        $query = static::query();
        
        if ($date) {
            $query->where('visit_date', $date);
        }
        
        return $query->distinct('device_fingerprint')->count();
    }
}
