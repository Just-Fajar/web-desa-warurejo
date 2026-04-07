<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ProfilDesa;
use App\Services\StrukturOrganisasiService;
use App\Helpers\SEOHelper;

class ProfilController extends Controller
{
    /**
     * Tampilkan halaman profil desa overview
     * Route: GET /profil
     */
    public function index()
    {
        $profil = ProfilDesa::getInstance();
        
        $seoData = SEOHelper::generateMetaTags([
            'title' => 'Profil Desa - Desa Warurejo',
            'description' => "Profil lengkap Desa Warurejo meliputi visi misi, sejarah, struktur organisasi, dan informasi desa.",
            'keywords' => 'profil desa warurejo, visi misi desa, sejarah desa, struktur organisasi',
            'type' => 'website'
        ]);
        
        return view('public.profil.index', compact('profil', 'seoData'));
    }

    /**
     * Tampilkan halaman visi dan misi desa
     * Route: GET /profil/visi-misi
     */
    public function visiMisi()
    {
        $profil = ProfilDesa::getInstance();
        
        $seoData = SEOHelper::generateMetaTags([
            'title' => 'Visi & Misi - Desa Warurejo',
            'description' => "Visi dan Misi Desa Warurejo dalam membangun dan mengembangkan desa untuk kesejahteraan masyarakat.",
            'keywords' => 'visi misi desa warurejo, tujuan desa, program desa',
            'type' => 'website'
        ]);
        
        return view('public.profil.visi-misi', compact('profil', 'seoData'));
    }

    /**
     * Tampilkan halaman sejarah desa
     * Route: GET /profil/sejarah
     */
    public function sejarah()
    {
        $profil = ProfilDesa::getInstance();
        
        $seoData = SEOHelper::generateMetaTags([
            'title' => 'Sejarah Desa - Desa Warurejo',
            'description' => "Sejarah panjang Desa Warurejo dari masa ke masa. Pelajari asal-usul dan perkembangan desa kami.",
            'keywords' => 'sejarah desa warurejo, asal usul desa, perkembangan desa',
            'type' => 'website'
        ]);
        
        return view('public.profil.sejarah', compact('profil', 'seoData'));
    }

    /**
     * Tampilkan halaman struktur organisasi pemerintahan desa
     * Load data terstruktur dengan hierarki (Kepala, Sekretaris, Kaur, Kasi)
     * 
     * Route: GET /profil/struktur-organisasi
     */
    public function strukturOrganisasi(StrukturOrganisasiService $strukturOrganisasiService)
    {
        $profil = ProfilDesa::getInstance();
        
        // Get structured data for display
        $struktur = $strukturOrganisasiService->getStructuredData();
        
        $seoData = SEOHelper::generateMetaTags([
            'title' => 'Struktur Organisasi - Desa Warurejo',
            'description' => "Struktur organisasi pemerintahan Desa Warurejo beserta perangkat dan aparatur desa.",
            'keywords' => 'struktur organisasi desa warurejo, perangkat desa, kepala desa, aparatur desa',
            'type' => 'website'
        ]);
        
        return view('public.profil.struktur-organisasi', compact('profil', 'struktur', 'seoData'));
    }
}
