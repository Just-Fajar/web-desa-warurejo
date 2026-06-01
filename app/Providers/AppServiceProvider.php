<?php

namespace App\Providers;

use App\Models\Berita;
// Models
use App\Models\Galeri;
use App\Models\PotensiDesa;
use App\Models\StrukturOrganisasi;
use App\Repositories\BeritaRepository;
// Repositories
use App\Repositories\GaleriRepository;
use App\Repositories\PotensiDesaRepository;
use App\Repositories\StrukturOrganisasiRepository;
use App\Services\BeritaService;
// Services
use App\Services\GaleriService;
use App\Services\HtmlSanitizerService;
use App\Services\ImageUploadService;
use App\Services\PotensiDesaService;
use App\Services\StrukturOrganisasiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Load helpers
        require_once app_path('Helpers/SecurityHelper.php');

        // Register Repositories
        $this->app->singleton(BeritaRepository::class, function ($app) {
            return new BeritaRepository(new Berita);
        });

        $this->app->singleton(GaleriRepository::class, function ($app) {
            return new GaleriRepository(new Galeri);
        });

        $this->app->singleton(PotensiDesaRepository::class, function ($app) {
            return new PotensiDesaRepository(new PotensiDesa);
        });

        $this->app->singleton(StrukturOrganisasiRepository::class, function ($app) {
            return new StrukturOrganisasiRepository(new StrukturOrganisasi);
        });

        // Register ImageUploadService
        $this->app->singleton(ImageUploadService::class, function ($app) {
            return new ImageUploadService;
        });

        // Register HtmlSanitizerService
        $this->app->singleton(HtmlSanitizerService::class, function ($app) {
            return new HtmlSanitizerService;
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

        // Register custom blade directive for CSP nonce
        \Illuminate\Support\Facades\Blade::directive('nonce', function () {
            return 'nonce="<?php echo csp_nonce(); ?>"';
        });

        // Integrate CSP nonce with Vite compiler
        \Illuminate\Support\Facades\Vite::useCspNonce(csp_nonce());

        // Define rate limiter for public forms (pengaduan submission)
        \Illuminate\Support\Facades\RateLimiter::for('public-forms', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($request->ip());
        });

        // Define rate limiter for public page views (anti-scraping)
        \Illuminate\Support\Facades\RateLimiter::for('public-pages', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)->by($request->ip());
        });
    }
}
