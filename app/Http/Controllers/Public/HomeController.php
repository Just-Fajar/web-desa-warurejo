<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ProfilDesa;
use App\Services\BeritaService;
use App\Services\PotensiDesaService;
use App\Services\VisitorStatisticsService;
use App\Repositories\GaleriRepository;
use App\Helpers\SEOHelper;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    protected $beritaService;
    protected $potensiService;
    protected $galeriRepository;
    protected $visitorService;

    /**
     * Constructor - Inject services untuk homepage
     * Controller untuk handle halaman homepage public
     */
    public function __construct(
        BeritaService $beritaService,
        PotensiDesaService $potensiService,
        GaleriRepository $galeriRepository,
        VisitorStatisticsService $visitorService
    ) {
        $this->beritaService = $beritaService;
        $this->potensiService = $potensiService;
        $this->galeriRepository = $galeriRepository;
        $this->visitorService = $visitorService;
    }

    /**
     * Tampilkan halaman homepage dengan cache strategy
     * Cache duration:
     * - Profil desa: 1 hari (jarang berubah)
     * - Berita: 1 jam (sering update)
     * - Potensi: 6 jam
     * - Galeri: 3 jam
     * - Total counts: 1-6 jam
     * - Visitor stats: real-time (no cache)
     * - SEO data: 1 hari
     * 
     * Route: GET /
     */
    public function index()
    {
        // Cache profil desa for 1 day (86400 seconds)
        $profil = Cache::remember('profil_desa', 86400, function () {
            return ProfilDesa::first() ?? new ProfilDesa([
                'nama_desa' => 'Desa Warurejo',
                'kecamatan' => 'Balerejo',
                'kabupaten' => 'Madiun',
                'provinsi' => 'Jawa Timur',
                'jumlah_penduduk' => 0,
                'luas_wilayah' => 0
            ]);
        });

        // Cache latest berita for 1 hour (3600 seconds)
        $latest_berita = Cache::remember('home.latest_berita', 3600, function () {
            return $this->beritaService->getLatestBerita(3);
        });

        // Cache active potensi for 6 hours (21600 seconds)
        $potensi = Cache::remember('home.potensi', 21600, function () {
            return $this->potensiService->getActivePotensi();
        });

        // Cache latest galeri for 3 hours (10800 seconds)
        $galeri = Cache::remember('home.galeri', 10800, function () {
            return $this->galeriRepository->getLatest(6);
        });

        // Total counts for statistics section
        $totalBerita = Cache::remember('home.total_berita', 3600, function () {
            return \App\Models\Berita::published()->count();
        });

        $totalPotensi = Cache::remember('home.total_potensi', 21600, function () {
            return \App\Models\PotensiDesa::active()->count();
        });

        $totalGaleri = Cache::remember('home.total_galeri', 10800, function () {
            return \App\Models\Galeri::published()->count();
        });

        // Don't cache visitor stats (needs to be real-time)
        $totalVisitors = $this->visitorService->getTotalVisitors();

        // Cache SEO data for 1 day
        $seoData = Cache::remember('home.seo_data', 86400, function () use ($profil) {
            return SEOHelper::generateMetaTags([
                'title' => $profil->nama_desa . ' - ' . $profil->kecamatan . ', ' . $profil->kabupaten,
                'description' => "Website resmi {$profil->nama_desa}, {$profil->kecamatan}, {$profil->kabupaten}. Informasi berita terkini, potensi desa, galeri kegiatan, dan profil desa.",
                'keywords' => "desa warurejo, {$profil->kecamatan}, {$profil->kabupaten}, profil desa, berita desa, potensi desa, galeri",
                'image' => $profil->logo ? asset('storage/' . $profil->logo) : asset('images/logo.png'),
                'type' => 'website'
            ]);
        });
        
        $structuredData = SEOHelper::getOrganizationSchema();

        // Kirim data ke view
        return view('public.home', compact(
            'profil',
            'latest_berita',
            'potensi',
            'galeri',
            'totalBerita',
            'totalPotensi',
            'totalGaleri',
            'totalVisitors',
            'seoData',
            'structuredData'
        ));
    }
}
