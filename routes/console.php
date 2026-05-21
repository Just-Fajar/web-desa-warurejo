<?php

use App\Services\VisitorStatisticsService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule visitor data cleanup (delete data older than 90 days)
Schedule::call(function () {
    $service = app(VisitorStatisticsService::class);
    $deleted = $service->cleanupOldData(90);
    \Log::info("Visitor cleanup: deleted {$deleted} old records");
})->dailyAt('06:00');

// Schedule daily stats aggregation (backup yesterday's data)
Schedule::call(function () {
    $service = app(VisitorStatisticsService::class);
    $service->aggregateYesterdayStats();
    \Log::info("Visitor stats: aggregated yesterday's data");
})->dailyAt('06:05');

// Auto-publish konten yang dijadwalkan (cek setiap menit)
Schedule::command('content:publish-scheduled')->everyMinute();
