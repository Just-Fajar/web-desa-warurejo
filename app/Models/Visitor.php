<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
