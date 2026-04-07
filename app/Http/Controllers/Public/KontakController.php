<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ProfilDesa;
use App\Helpers\SEOHelper;

class KontakController extends Controller
{
    public function index()
    {
        $profil = ProfilDesa::getInstance();
        
        $seoData = SEOHelper::generateMetaTags([
            'title' => 'Kontak - Desa Warurejo',
            'description' => "Hubungi Desa Warurejo melalui WhatsApp, telepon, email, atau kunjungi kantor desa kami.",
            'keywords' => 'kontak desa warurejo, hubungi desa, alamat kantor desa, telepon desa',
            'type' => 'website'
        ]);
        
        return view('public.kontak.index', compact('profil', 'seoData'));
    }
}
