<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Berita;
use App\Models\PotensiDesa;
use App\Models\Galeri;
use App\Models\Publikasi;
use App\Models\ProfilDesa;
use App\Models\DailyVisitorStat;
use Carbon\Carbon;

class StatistikController extends Controller
{
    /**
     * @OA\Get(
     *     path="/statistik/summary",
     *     summary="Dapatkan ringkasan statistik konten, demografi, dan pengunjung",
     *     description="Mengembalikan statistik jumlah berita, potensi, galeri, publikasi, data demografi (luas wilayah, penduduk, KK), serta analitik kunjungan harian & akumulatif.",
     *     tags={"Statistik"},
     *     @OA\Response(
     *         response=200,
     *         description="Ringkasan statistik berhasil dimuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Request successful"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     *
     * Get dashboard / public stats summary.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function summary()
    {
        $profil = ProfilDesa::getInstance();

        // 1. Content Stats
        $beritaCount = Berita::where('status', 'published')->count();
        $beritaViews = (int) Berita::where('status', 'published')->sum('views');

        $potensiCount = PotensiDesa::published()->count();
        $potensiViews = (int) PotensiDesa::published()->sum('views');

        $galeriCount = Galeri::published()->count();
        $galeriViews = (int) Galeri::published()->sum('views');

        $publikasiCount = Publikasi::published()->count();
        $publikasiViews = (int) Publikasi::published()->sum('views');
        $publikasiDownloads = (int) Publikasi::published()->sum('jumlah_download');

        // 2. Demographic Stats
        $demographics = [
            'luas_wilayah' => (float) $profil->luas_wilayah,
            'jumlah_penduduk' => (int) $profil->jumlah_penduduk,
            'jumlah_kk' => (int) $profil->jumlah_kk,
        ];

        // 3. Visitor Stats
        $totalVisitors = (int) DailyVisitorStat::sum('unique_visitors');
        $totalPageViews = (int) DailyVisitorStat::sum('page_views');

        $todayStats = DailyVisitorStat::todayStats();
        $todayUnique = (int) $todayStats->unique_visitors;
        $todayViews = (int) $todayStats->page_views;

        // Last 30 days visitor history
        $history = DailyVisitorStat::betweenDates(
            Carbon::now()->subDays(30)->toDateString(),
            Carbon::now()->toDateString()
        )->get()->map(function ($stat) {
            $dateStr = $stat->date;
            if ($dateStr instanceof Carbon) {
                $dateStr = $dateStr->toDateString();
            }
            return [
                'date' => $dateStr,
                'unique_visitors' => (int) $stat->unique_visitors,
                'page_views' => (int) $stat->page_views,
            ];
        });

        return ApiResponse::success([
            'contents' => [
                'berita' => [
                    'count' => $beritaCount,
                    'views' => $beritaViews,
                ],
                'potensi' => [
                    'count' => $potensiCount,
                    'views' => $potensiViews,
                ],
                'galeri' => [
                    'count' => $galeriCount,
                    'views' => $galeriViews,
                ],
                'publikasi' => [
                    'count' => $publikasiCount,
                    'views' => $publikasiViews,
                    'downloads' => $publikasiDownloads,
                ],
            ],
            'demographics' => $demographics,
            'visitors' => [
                'total_unique' => $totalVisitors,
                'total_views' => $totalPageViews,
                'today_unique' => $todayUnique,
                'today_views' => $todayViews,
                'last_30_days' => $history,
            ],
        ]);
    }
}
