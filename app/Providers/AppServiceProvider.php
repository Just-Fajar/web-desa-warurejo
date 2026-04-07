<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Models
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\PotensiDesa;
use App\Models\StrukturOrganisasi;

// Repositories
use App\Repositories\BeritaRepository;
use App\Repositories\GaleriRepository;
use App\Repositories\PotensiDesaRepository;
use App\Repositories\StrukturOrganisasiRepository;

// Services
use App\Services\BeritaService;
use App\Services\GaleriService;
use App\Services\PotensiDesaService;
use App\Services\StrukturOrganisasiService;
use App\Services\ImageUploadService;
use App\Services\HtmlSanitizerService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Repositories
        $this->app->singleton(BeritaRepository::class, function ($app) {
            return new BeritaRepository(new Berita());
        });

        $this->app->singleton(GaleriRepository::class, function ($app) {
            return new GaleriRepository(new Galeri());
        });

        $this->app->singleton(PotensiDesaRepository::class, function ($app) {
            return new PotensiDesaRepository(new PotensiDesa());
        });

        $this->app->singleton(StrukturOrganisasiRepository::class, function ($app) {
            return new StrukturOrganisasiRepository(new StrukturOrganisasi());
        });

        // Register ImageUploadService
        $this->app->singleton(ImageUploadService::class, function ($app) {
            return new ImageUploadService();
        });

        // Register HtmlSanitizerService
        $this->app->singleton(HtmlSanitizerService::class, function ($app) {
            return new HtmlSanitizerService();
        });

        // Register Services
        $this->app->singleton(BeritaService::class, function ($app) {
            return new BeritaService(
                $app->make(BeritaRepository::class),
                $app->make(ImageUploadService::class),
                $app->make(HtmlSanitizerService::class)
            );
        });

        $this->app->singleton(GaleriService::class, function ($app) {
            return new GaleriService($app->make(GaleriRepository::class));
        });

        $this->app->singleton(PotensiDesaService::class, function ($app) {
            return new PotensiDesaService(
                $app->make(PotensiDesaRepository::class),
                $app->make(HtmlSanitizerService::class)
            );
        });

        $this->app->singleton(StrukturOrganisasiService::class, function ($app) {
            return new StrukturOrganisasiService(
                $app->make(StrukturOrganisasiRepository::class),
                $app->make(ImageUploadService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production environment
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
