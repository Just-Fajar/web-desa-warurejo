<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\BeritaService;
use App\Helpers\SEOHelper;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    protected $beritaService;

    /**
     * Constructor - Inject BeritaService
     * Controller untuk handle halaman berita public
     */
    public function __construct(BeritaService $beritaService)
    {
        $this->beritaService = $beritaService;
    }

    /**
     * Tampilkan list semua berita published dengan filter
     * Filter available: search, date_from, date_to, sort (latest/popular/oldest)
     * Jika ada filter aktif, gunakan searchWithFilters
     * Jika tidak ada filter, ambil semua published berita
     * Include SEO meta tags
     * 
     * Route: GET /berita
     */
    public function index(Request $request)
    {
        $perPage = 12;
        
        // Get filter parameters
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $sortBy = $request->get('sort', 'latest'); // latest, popular, oldest
        
        // Apply filters
        if ($search || $dateFrom || $dateTo || $sortBy !== 'latest') {
            $berita = $this->beritaService->searchWithFilters([
                'search' => $search,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'sort' => $sortBy
            ], $perPage);
        } else {
            $berita = $this->beritaService->getPublishedBerita($perPage);
        }
        
        // SEO Data
        $seoData = SEOHelper::generateMetaTags([
            'title' => 'Berita Desa - Desa Warurejo',
            'description' => 'Kumpulan berita dan informasi terkini dari Desa Warurejo. Dapatkan update kegiatan, program, dan pengumuman desa.',
            'keywords' => 'berita desa warurejo, informasi desa, kegiatan desa, pengumuman desa',
            'type' => 'website'
        ]);
        
        return view('public.berita.index', compact('berita', 'seoData'));
    }
    
    /**
     * API endpoint untuk search autocomplete suggestions
     * Return JSON array dengan title dan URL berita
     * Minimum 2 karakter untuk trigger search
     * 
     * Route: GET /berita/autocomplete?q=keyword
     */
    public function autocomplete(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $suggestions = $this->beritaService->getSearchSuggestions($query, 5);
        
        return response()->json($suggestions);
    }

    /**
     * Tampilkan detail berita by slug
     * - Auto increment views counter
     * - Load related berita (4 terbaru)
     * - Generate SEO meta tags dengan Open Graph
     * - Generate structured data (Article schema)
     * - Generate breadcrumb schema untuk Google
     * - Throw 404 jika berita tidak ditemukan
     * 
     * Route: GET /berita/{slug}
     */
    public function show($slug)
    {
        try {
            $berita = $this->beritaService->getBeritaBySlug($slug);
            
            // Get related berita (same category or recent)
            $relatedBerita = $this->beritaService->getLatestBerita(4);
            
            // SEO Data
            $excerpt = strip_tags(substr($berita->konten, 0, 160));
            $seoData = SEOHelper::generateMetaTags([
                'title' => $berita->judul . ' - Berita Desa Warurejo',
                'description' => $excerpt,
                'keywords' => "berita desa, {$berita->judul}, desa warurejo",
                'image' => asset('storage/' . $berita->gambar),
                'type' => 'article'
            ]);
            
            // Structured Data for Article
            $structuredData = SEOHelper::getArticleSchema($berita);
            
            // Breadcrumb
            $breadcrumb = SEOHelper::getBreadcrumbSchema([
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Berita', 'url' => route('berita.index')],
                ['name' => $berita->judul, 'url' => route('berita.show', $berita->slug)]
            ]);
            
            return view('public.berita.show', compact('berita', 'relatedBerita', 'seoData', 'structuredData', 'breadcrumb'));
        } catch (\Exception $e) {
            abort(404, 'Berita tidak ditemukan');
        }
    }
}
