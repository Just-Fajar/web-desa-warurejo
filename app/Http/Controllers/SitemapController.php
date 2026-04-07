<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\PotensiDesa;
use Illuminate\Http\Response;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    /**
     * Generate and return sitemap.xml
     */
    public function index(): Response
    {
        $sitemap = Sitemap::create();
        
        // Homepage
        $sitemap->add(
            Url::create(route('home'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );
        
        // Profil Pages
        $sitemap->add(
            Url::create(route('profil.visi-misi'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.8)
        );
        
        $sitemap->add(
            Url::create(route('profil.sejarah'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.8)
        );
        
        $sitemap->add(
            Url::create(route('profil.struktur-organisasi'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.8)
        );
        
        // Berita Index
        $sitemap->add(
            Url::create(route('berita.index'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.9)
        );
        
        // All Berita Articles
        Berita::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->each(function (Berita $berita) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('berita.show', $berita->slug))
                        ->setLastModificationDate($berita->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.7)
                );
            });
        
        // Potensi Desa Index
        $sitemap->add(
            Url::create(route('potensi.index'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.9)
        );
        
        // All Potensi Desa
        PotensiDesa::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->each(function (PotensiDesa $potensi) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('potensi.show', $potensi->slug))
                        ->setLastModificationDate($potensi->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.7)
                );
            });
        
        // Galeri
        $sitemap->add(
            Url::create(route('galeri.index'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8)
        );
        
        // Peta Desa
        $sitemap->add(
            Url::create(route('peta-desa'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.7)
        );
        
        // Kontak
        $sitemap->add(
            Url::create(route('kontak.index'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.7)
        );
        
        return response($sitemap->render(), 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
