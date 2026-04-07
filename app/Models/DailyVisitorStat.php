<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DailyVisitorStat extends Model
{
    protected $fillable = [
        'date',
        'unique_visitors',
        'page_views',
    ];

    protected $casts = [
        'date' => 'date',
        'unique_visitors' => 'integer',
        'page_views' => 'integer',
    ];

    /**
     * Scope untuk query stats dalam rentang tanggal
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate])
                     ->orderBy('date', 'asc');
    }

    /**
     * Get stats untuk hari ini
     */
    public static function todayStats()
    {
        return static::firstOrCreate(
            ['date' => Carbon::today()->toDateString()],
            ['unique_visitors' => 0, 'page_views' => 0]
        );
    }
}
