{{--
    PUBLIC LAYOUT TEMPLATE
    
    Master layout untuk semua halaman public website
    
    STRUKTUR:
    - Head: SEO meta tags (auto-generated dari SEOHelper)
    - Navbar: Navigation menu (sticky)
    - Main: @yield('content') area
    - Footer: Contact info & links
    - WhatsApp FAB: Floating action button (hanya di homepage)
    
    SEO FEATURES:
    - Dynamic meta tags (title, description, keywords)
    - Open Graph tags (Facebook sharing)
    - Twitter Card tags (Twitter sharing)
    - Structured Data JSON-LD (Google rich results)
    - Canonical URL
    - Robots meta (index, follow)
    
    SEO DATA:
    $seoData array bisa dipass dari controller:
    [
        'title' => 'Custom Title',
        'description' => 'Custom description...',
        'image' => 'https://example.com/image.jpg',
        'type' => 'article',
    ]
    
    STRUCTURED DATA:
    $structuredData: Custom schema (Article, Event, Product)
    Default: Organization schema untuk desa
    
    USAGE:
    @extends('public.layouts.app')
    @section('content')
        <!-- Your page content -->
    @endsection
    
    @push('styles')
        <!-- Additional CSS -->
    @endpush
    
    @push('scripts')
        <!-- Additional JS -->
    @endpush
--}}
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
        <script type="application/ld+json">
            {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @else
        {{-- Default Organization Schema --}}
        <script type="application/ld+json">
            {!! json_encode(SEOHelper::getOrganizationSchema(), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endif
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-gray-50">
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

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
