<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Repositories\GaleriRepository;
use App\Helpers\SEOHelper;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    protected $galeriRepository;

    /**
     * Constructor - Inject GaleriRepository
     * Controller untuk handle halaman galeri public
     */
    public function __construct(GaleriRepository $galeriRepository)
    {
        $this->galeriRepository = $galeriRepository;
    }

    /**
     * Tampilkan list semua galeri published dengan filter
     * Filter: search, kategori, urutkan (terbaru/terlama/terpopuler)
     * Eager load admin dan images untuk prevent N+1
     * Pagination 24 items per page
     * Include SEO meta tags
     * 
     * Route: GET /galeri
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $kategori = $request->get('kategori');
        $urutkan = $request->get('urutkan', 'terbaru'); // terbaru, terlama, terpopuler
        
        // Build query
        $query = Galeri::with(['admin', 'images'])->published();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        
        if ($kategori) {
            $query->byKategori($kategori);
        }
        
        // Apply sorting
        switch ($urutkan) {
            case 'terpopuler':
                $query->orderBy('views', 'desc');
                break;
            case 'terlama':
                $query->oldest();
                break;
            case 'terbaru':
            default:
                $query->latest();
                break;
        }
        
        $galeris = $query->paginate(24)->appends($request->query());
        
        // SEO Data
        $title = 'Galeri Dokumentasi';
        if ($kategori) {
            $title .= " - " . ucfirst($kategori);
        }
        
        $seoData = SEOHelper::generateMetaTags([
            'title' => $title . ' - Desa Warurejo',
            'description' => 'Galeri foto dan video kegiatan, program, dan momen penting Desa Warurejo.',
            'keywords' => 'galeri desa warurejo, foto kegiatan desa, video desa',
            'type' => 'website'
        ]);
        
        return view('public.galeri.index', compact('galeris', 'search', 'kategori', 'seoData'));
    }
}
