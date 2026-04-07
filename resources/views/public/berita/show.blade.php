{{--
    PUBLIC BERITA DETAIL
    
    Halaman detail artikel berita dengan full content
    
    FEATURES:
    - Breadcrumb navigation (Home > Berita > Judul)
    - Featured image full-width (responsive height)
    - Category & date badges
    - Author info (admin name)
    - Full HTML content (sanitized dari TinyMCE)
    - View counter increment
    - Social share buttons (Facebook, Twitter, WhatsApp)
    - Related articles (3 berita terkait)
    - Back to list button
    
    CONTENT RENDERING:
    - {!! $berita->konten !!} - Raw HTML dari TinyMCE
    - Already sanitized di BeritaService dengan HTMLPurifier
    - Support images, videos, tables, formatted text
    
    SEO OPTIMIZATION:
    - Dynamic title: {judul} - Desa Warurejo
    - Meta description dari excerpt
    - Open Graph tags untuk social sharing
    - Article structured data (JSON-LD)
    - Canonical URL
    
    SOCIAL SHARING:
    - Facebook: Share dengan title + URL
    - Twitter: Tweet dengan title + URL + hashtags
    - WhatsApp: Share message dengan title + URL
    
    RELATED ARTICLES:
    - Same kategori berita
    - Exclude current article
    - Limit 3 articles
    - Random or latest
    
    TRACKING:
    - View counter auto-increment via BeritaService
    - Analytics tracking (visitor stats)
    
    DATA:
    $berita: Berita model with relationships (admin, category)
    $relatedBerita: Collection of related articles
    
    Route: /berita/{slug}
    Controller: Public\BeritaController@show
--}}
@extends('public.layouts.app')

@section('title', $berita->judul . ' - Desa Warurejo')

@section('content')
{{-- Breadcrumb --}}
<section class="bg-gray-100 pt-24 pb-3 sm:pt-28 sm:pb-4 lg:pt-32">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center text-xs sm:text-sm text-gray-600 scroll-reveal">
            <a href="{{ route('home') }}" class="hover:text-primary-600 shrink-0">Beranda</a>
            <svg class="w-3 h-3 sm:w-4 sm:h-4 mx-1.5 sm:mx-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <a href="{{ route('berita.index') }}" class="hover:text-primary-600 shrink-0">Berita</a>
            <svg class="w-3 h-3 sm:w-4 sm:h-4 mx-1.5 sm:mx-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="text-gray-800 font-semibold truncate">{{ Str::limit($berita->judul, 50) }}</span>
        </div>
    </div>
</section>

{{-- Article Content --}}
<section class="py-6 sm:py-8 md:py-12 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-[1100px]">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            
            {{-- Main Article (Left Column) --}}
            <div class="lg:col-span-8">
                <article class="bg-white scroll-reveal">
                    
                    {{-- Title & Header Info (Detik style: Title -> Author -> Date) --}}
                    <h1 class="text-[28px] sm:text-[32px] md:text-[38px] font-bold text-[#003366] mb-4 leading-tight tracking-tight">
                        {{ $berita->judul }}
                    </h1>

                    <div class="flex items-center text-[13px] sm:text-[14px] text-gray-500 mb-6 font-medium">
                        <span class="text-gray-800">{{ $berita->admin->name ?? $berita->admin->username ?? 'Administrator' }}</span>
                        <span class="mx-2 text-gray-300">-</span>
                        <span class="text-[#e32] font-semibold">Berita</span>
                        <span class="mx-2 text-gray-300">|</span>
                        <span>{{ \Carbon\Carbon::parse($berita->published_at ?? $berita->created_at)->locale('id')->isoFormat('dddd, D MMMM Y HH:mm') }} WIB</span>
                    </div>

                    {{-- Featured Image --}}
                    @if($berita->gambar_utama)
                        <div class="relative w-full mb-3">
                            <img 
                                src="{{ $berita->gambar_utama_url }}" 
                                alt="{{ $berita->judul }}"
                                class="w-full object-cover rounded-sm"
                                loading="lazy"
                            >
                        </div>
                        <div class="text-[11px] sm:text-[12px] text-gray-500 italic mb-6 border-b border-gray-100 pb-4">
                            Foto: {{ $berita->judul }} (Dok. Desa Warurejo)
                        </div>
                    @endif

                    {{-- Content (with Dropcap/Bold style start like Detik) --}}
                    <div class="prose prose-sm sm:prose-base lg:prose-lg max-w-none text-[#333] leading-relaxed">
                        <span class="font-bold">Madiun</span> - {!! $berita->konten !!}
                    </div>

                    {{-- Tags/Topics (whn/yld style tag in detik) --}}
                    @if(isset($berita->tags) && $berita->tags)
                        <div class="mt-8 pt-4">
                            <div class="font-bold text-[#003366] mb-3 text-lg">Topik Terkait</div>
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $berita->tags) as $tag)
                                    <span class="px-4 py-2 bg-[#f1f1f1] text-[#333] text-[13px] sm:text-[14px] font-medium rounded-sm hover:bg-gray-200 transition cursor-pointer">
                                        {{ trim($tag) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </article>

                {{-- Berita Terkait List --}}
                @if(isset($relatedBerita) && $relatedBerita->count() > 0)
                    <div class="mt-12 pt-8 border-t-[3px] border-[#003366]">
                        <h2 class="text-xl font-bold text-[#003366] mb-6">Berita Terkait</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                            @foreach($relatedBerita->take(6) as $related)
                                <div class="border-b border-gray-200 pb-3">
                                    <a href="{{ route('berita.show', $related->slug) }}" class="flex group">
                                        @if($related->gambar_utama_url)
                                            <div class="w-20 h-16 shrink-0 bg-gray-100 rounded-md overflow-hidden mr-3">
                                                <img src="{{ $related->gambar_utama_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h3 class="text-[14px] font-bold text-gray-900 group-hover:text-[#003366] leading-snug line-clamp-3">
                                                {{ $related->judul }}
                                            </h3>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- Rekomendasi Text List --}}
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h2 class="text-xl font-bold text-[#003366] mb-6">Rekomendasi Untuk Anda</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                            @foreach($relatedBerita->shuffle()->take(6) as $rekomendasi)
                                <div class="border-b border-gray-200 pb-3">
                                    <a href="{{ route('berita.show', $rekomendasi->slug) }}" class="flex group">
                                        @if($rekomendasi->gambar_utama_url)
                                            <div class="w-20 h-16 shrink-0 bg-gray-100 rounded-md overflow-hidden mr-3">
                                                <img src="{{ $rekomendasi->gambar_utama_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h3 class="text-[14px] font-bold text-gray-900 group-hover:text-[#003366] leading-snug line-clamp-3">
                                                {{ $rekomendasi->judul }}
                                            </h3>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                {{-- Berita Lainnya Grid --}}
                @if(isset($relatedBerita) && $relatedBerita->count() > 0)
                    <div class="mt-10 pt-8 border-t border-gray-200 mb-10">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-[#003366]">Berita Lainnya</h2>
                            <a href="{{ route('berita.index') }}" class="text-sm font-semibold text-[#e32] hover:text-[#003366]">Selengkapnya &rarr;</a>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            @foreach($relatedBerita->shuffle()->take(3) as $lainnya)
                                <a href="{{ route('berita.show', $lainnya->slug) }}" class="group block">
                                    <div class="w-full h-36 bg-gray-200 rounded-lg overflow-hidden mb-3">
                                        @if($lainnya->gambar_utama_url)
                                            <img src="{{ $lainnya->gambar_utama_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        @endif
                                    </div>
                                    <p class="text-[13px] text-[#e32] mb-1">Berita Desa</p>
                                    <h3 class="text-[15px] font-bold text-gray-900 group-hover:text-[#003366] leading-tight line-clamp-3">
                                        {{ $lainnya->judul }}
                                    </h3>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar (Right Column) --}}
            <div class="lg:col-span-4 mt-8 lg:mt-0">
                <div class="sticky top-24">
                    {{-- Berita Terpopuler --}}
                    @if(isset($relatedBerita) && $relatedBerita->count() > 0)
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-[#003366] mb-5 border-b-2 border-[#003366] pb-2 inline-block">Berita Terpopuler</h2>
                            <div class="space-y-4">
                                @foreach($relatedBerita->take(5) as $index => $populer)
                                    <a href="{{ route('berita.show', $populer->slug) }}" class="flex group">
                                        <div class="text-3xl font-extrabold text-gray-300 mr-4 mt-1 italic group-hover:text-[#e32] transition">
                                            #{{ $index + 1 }}
                                        </div>
                                        <div>
                                            <h3 class="text-[15px] font-bold text-gray-900 group-hover:text-[#003366] leading-snug">
                                                {{ $populer->judul }}
                                            </h3>
                                        </div>
                                    </a>
                                    @if(!$loop->last)
                                        <div class="border-b border-gray-100"></div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="mt-6 text-center">
                                <a href="{{ route('berita.index') }}" class="inline-block bg-gray-100 hover:bg-gray-200 text-[#e32] font-semibold text-sm py-2 px-6 rounded-md transition">Lihat Selengkapnya &rarr;</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</section>

<style>
/* Style tambahan seperti Website Detik.com */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
h1, h2, h3, h4, h5, h6 {
    font-family: 'Poppins', sans-serif;
}

/* Scroll-triggered animations */
.scroll-reveal {
    opacity: 0;
    transform: translateY(50px);
    transition: opacity 0.8s ease-out, transform 0.8s ease-out;
}

.scroll-reveal.revealed {
    opacity: 1;
    transform: translateY(0);
}

.scroll-reveal-stagger {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.6s ease-out, transform 0.6s ease-out;
}

.scroll-reveal-stagger.revealed {
    opacity: 1;
    transform: translateY(0);
}
</style>

<script>
// Scroll-triggered animation observer
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        root: null,
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
            }
        });
    }, observerOptions);

    // Observe all elements with scroll-reveal classes
    document.querySelectorAll('.scroll-reveal, .scroll-reveal-stagger').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endsection
