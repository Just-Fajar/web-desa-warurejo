<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\PotensiDesaService;
use App\Helpers\SEOHelper;
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
     * Filter: kategori, search, urutkan (terbaru/terlama/terpopuler)
     * Include SEO meta tags
     * 
     * Route: GET /potensi
     */
    public function index(Request $request)
    {
        $kategori = $request->get('kategori');
        $search = $request->get('search');
        $urutkan = $request->get('urutkan', 'terbaru'); // terbaru, terlama, terpopuler
        
        // Build query with filters
        if ($kategori || $search || $urutkan !== 'terbaru') {
            $potensi = $this->potensiService->searchWithFilters([
                'kategori' => $kategori,
                'search' => $search,
                'urutkan' => $urutkan
            ]);
        } else {
            $potensi = $this->potensiService->getActivePotensi();
        }
        
        // SEO Data
        $title = $kategori ? "Potensi Desa - {$kategori}" : 'Potensi Desa';
        $seoData = SEOHelper::generateMetaTags([
            'title' => $title . ' - Desa Warurejo',
            'description' => 'Potensi dan kekayaan Desa Warurejo. Temukan berbagai potensi wisata, pertanian, ekonomi, dan lainnya.',
            'keywords' => 'potensi desa warurejo, wisata desa, ekonomi desa, pertanian desa',
            'type' => 'website'
        ]);
        
        return view('public.potensi.index', compact('potensi', 'kategori', 'seoData'));
    }

    /**
     * Tampilkan detail potensi by slug
     * - Load related potensi (3 item same category)
     * - Generate SEO meta tags dengan Open Graph
     * - Generate structured data (Place schema)
     * - Generate breadcrumb schema
     * - Throw 404 jika tidak ditemukan
     * 
     * Route: GET /potensi/{slug}
     */
    public function show($slug)
    {
        try {
            $potensi = $this->potensiService->getPotensiBySlug($slug);
            
            // Get related potensi
            $relatedPotensi = $this->potensiService->getRelatedPotensi($potensi, 3);
            
            // SEO Data
            $excerpt = strip_tags(substr($potensi->deskripsi, 0, 160));
            $seoData = SEOHelper::generateMetaTags([
                'title' => $potensi->nama . ' - Potensi Desa Warurejo',
                'description' => $excerpt,
                'keywords' => "potensi desa, {$potensi->nama}, {$potensi->kategori}, desa warurejo",
                'image' => asset('storage/' . $potensi->gambar),
                'type' => 'article'
            ]);
            
            // Structured Data for Place
            $structuredData = SEOHelper::getPlaceSchema($potensi);
            
            // Breadcrumb
            $breadcrumb = SEOHelper::getBreadcrumbSchema([
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Potensi Desa', 'url' => route('potensi.index')],
                ['name' => $potensi->nama, 'url' => route('potensi.show', $potensi->slug)]
            ]);
            
            return view('public.potensi.show', compact('potensi', 'relatedPotensi', 'seoData', 'structuredData', 'breadcrumb'));
        } catch (\Exception $e) {
            abort(404, 'Potensi desa tidak ditemukan');
        }
    }
}
