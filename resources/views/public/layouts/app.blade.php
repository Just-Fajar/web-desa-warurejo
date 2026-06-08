<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- SEO Meta Tags --}}
    @php
        use App\Helpers\SEOHelper;

        $seo = SEOHelper::generateMetaTags($seoData ?? []);
    @endphp


    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    <meta name="keywords" content="{{ $seo['keywords'] }}">
    <meta name="author" content="{{ $seo['author'] }}">
    <meta name="robots" content="index, follow">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ $seo['url'] }}">

    {{-- Open Graph Meta Tags (Facebook) --}}
    <meta property="og:type" content="{{ $seo['type'] }}">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['description'] }}">
    <meta property="og:image" content="{{ $seo['image'] }}">
    <meta property="og:url" content="{{ $seo['url'] }}">
    <meta property="og:site_name" content="{{ $seo['site_name'] }}">
    <meta property="og:locale" content="{{ $seo['locale'] }}">

    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="{{ $seo['twitter_card'] }}">
    <meta name="twitter:title" content="{{ $seo['title'] }}">
    <meta name="twitter:description" content="{{ $seo['description'] }}">
    <meta name="twitter:image" content="{{ $seo['image'] }}">

    {{-- Structured Data (JSON-LD) --}}
    @if(isset($structuredData))
        <script type="application/ld+json" @nonce>
                {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
            </script>
    @else
        {{-- Default Organization Schema --}}
        <script type="application/ld+json" @nonce>
                {!! json_encode(SEOHelper::getOrganizationSchema(), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
            </script>
    @endif

    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="{{ asset('images/Logo-Kabupaten-copy.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preload" as="style" href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">

    {{-- LCP Preloading for Homepage --}}
    @if(request()->routeIs('home') || request()->is('/'))
        <link rel="preload" as="image" href="{{ asset('images/logo-web-desa.webp') }}" fetchpriority="high">
    @endif

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-gray-50 overflow-x-hidden w-full relative">
    <!-- Navigation -->
    @include('public.partials.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('public.partials.footer')

    {{-- WhatsApp FAB - Hanya di halaman beranda --}}
    @if(request()->routeIs('home') || request()->is('/'))
        @include('public.partials.whatsapp-fab')
    @endif

    <!-- Global Image Load Error Handler -->
    <script @nonce>
        window.addEventListener('error', function(e) {
            if (e.target && e.target.tagName === 'IMG') {
                const fallback = e.target.getAttribute('data-fallback');
                if (fallback && e.target.src !== fallback) {
                    e.target.src = fallback;
                }
            }
        }, true);
    </script>

    <!-- Scripts -->
    @stack('scripts')
</body>

</html>