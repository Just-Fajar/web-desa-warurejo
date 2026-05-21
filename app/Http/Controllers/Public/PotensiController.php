<?php

namespace App\Http\Controllers\Public;

use App\Helpers\SEOHelper;
use App\Http\Controllers\Controller;
use App\Models\PotensiDesa;
use App\Services\PotensiDesaService;
use Illuminate\Http\Request;

class PotensiController extends Controller
{
    protected $potensiService;

    /**
     * Constructor - Inject PotensiDesaService
     * Controller untuk handle halaman potensi desa public
     */
    public function __construct(PotensiDesaService $potensiService)
    {
        $this->potensiService = $potensiService;
    }

    /**
     * Tampilkan list semua potensi dengan filter
     * Filter: kategori (chip), search, urutkan (terbaru/terlama/terpopuler)
     * Include SEO meta tags
     *
     * Route: GET /potensi
     */
    public function index(Request $request)
    {
        $kategori = $request->get('kategori');
        $search = $request->get('search');
        $urutkan = $request->get('urutkan', 'terbaru');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');

        // Build query with filters
        if ($kategori || $search || $urutkan !== 'terbaru' || $date_from || $date_to) {
            $potensi = $this->potensiService->searchWithFilters([
                'kategori' => $kategori,
                'search' => $search,
                'urutkan' => $urutkan,
                'date_from' => $date_from,
                'date_to' => $date_to,
            ]);
        } else {
            $potensi = $this->potensiService->getActivePotensi();
        }

        // Daftar kategori untuk chip filter
        $kategoriList = PotensiDesa::getKategoriList();
        $kategoriBadgeColors = PotensiDesa::getKategoriBadgeColors();

        // SEO Data
        $title = $kategori ? 'Potensi Desa - '.ucfirst($kategori) : 'Potensi Desa';
        $seoData = SEOHelper::generateMetaTags([
            'title' => $title.' - Desa Warurejo',
            'description' => 'Potensi dan kekayaan Desa Warurejo. Temukan berbagai potensi wisata, pertanian, ekonomi, dan lainnya.',
            'keywords' => 'potensi desa warurejo, wisata desa, ekonomi desa, pertanian desa',
            'type' => 'website',
        ]);

        return view('public.potensi.index', compact(
            'potensi', 'kategori', 'seoData', 'date_from', 'date_to',
            'kategoriList', 'kategoriBadgeColors'
        ));
    }

    /**
     * Tampilkan detail potensi by slug
     * - Load related potensi (3 item same category)
     * - Load foto galeri
     * - Generate SEO meta tags dengan Open Graph
     * - Generate structured data (Place schema)
     * - Generate breadcrumb schema
     * - Increment views counter
     * - Throw 404 jika tidak ditemukan
     *
     * Route: GET /potensi/{slug}
     */
    public function show($slug)
    {
        try {
            $potensi = $this->potensiService->getPotensiBySlug($slug);

            // Increment views
            $potensi->incrementViews();

            // Get related potensi
            $relatedPotensi = $this->potensiService->getRelatedPotensi($potensi, 3);

            // Badge warna untuk kategori
            $kategoriBadgeColors = PotensiDesa::getKategoriBadgeColors();

            // SEO Data
            $excerpt = strip_tags(substr($potensi->deskripsi, 0, 160));
            $seoData = SEOHelper::generateMetaTags([
                'title' => $potensi->nama.' - Potensi Desa Warurejo',
                'description' => $excerpt,
                'keywords' => "potensi desa, {$potensi->nama}, {$potensi->kategori}, desa warurejo",
                'image' => asset('storage/'.$potensi->gambar),
                'type' => 'article',
            ]);

            // Structured Data for Place
            $structuredData = SEOHelper::getPlaceSchema($potensi);

            // Breadcrumb
            $breadcrumb = SEOHelper::getBreadcrumbSchema([
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Potensi Desa', 'url' => route('potensi.index')],
                ['name' => $potensi->nama, 'url' => route('potensi.show', $potensi->slug)],
            ]);

            return view('public.potensi.show', compact(
                'potensi', 'relatedPotensi', 'seoData', 'structuredData', 'breadcrumb',
                'kategoriBadgeColors'
            ));
        } catch (\Exception $e) {
            abort(404, 'Potensi desa tidak ditemukan');
        }
    }
}
