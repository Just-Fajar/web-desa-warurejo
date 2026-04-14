@extends('public.layouts.app')

@section('title', $berita->judul . ' - Desa Warurejo')

@section('content')
{{-- Breadcrumb --}}
<section class="bg-gray-100 pt-24 pb-3 sm:pt-28 sm:pb-4 lg:pt-32">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center flex-wrap text-xs sm:text-sm text-gray-600 scroll-reveal overflow-hidden min-w-0">
            <a href="{{ route('home') }}" class="hover:text-primary-600 shrink-0">Beranda</a>
            <svg class="w-3 h-3 sm:w-4 sm:h-4 mx-1.5 sm:mx-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <a href="{{ route('berita.index') }}" class="hover:text-primary-600 shrink-0">Berita</a>
            <svg class="w-3 h-3 sm:w-4 sm:h-4 mx-1.5 sm:mx-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="text-gray-800 font-semibold truncate min-w-0">{{ Str::limit($berita->judul, 40) }}</span>
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

                    <div class="flex flex-wrap items-center text-[13px] sm:text-[14px] text-gray-500 mb-6 font-medium gap-y-1">
                        <span class="text-gray-800">{{ $berita->admin->name ?? $berita->admin->username ?? 'Administrator' }}</span>
                        <span class="mx-2 text-gray-300">-</span>
                        <span class="text-[#e32] font-semibold">Berita</span>
                        <span class="mx-2 text-gray-300">|</span>
                        <span>{{ \Carbon\Carbon::parse($berita->published_at ?? $berita->created_at)->locale('id')->isoFormat('dddd, D MMM Y HH:mm') }} WIB</span>
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

                    {{-- Social Share Buttons --}}
                    <div class="mt-8 pt-6 border-t border-gray-100 flex items-center gap-4">
                        <span class="text-sm font-bold text-[#003366]">Bagikan:</span>
                        <div class="flex items-center gap-2">
                            <!-- WhatsApp -->
                            <a href="https://wa.me/?text={{ urlencode($berita->judul . ' - ' . url()->current()) }}" target="_blank" class="w-10 h-10 rounded-full flex items-center justify-center bg-[#25D366] text-white hover:opacity-80 transition hover:-translate-y-1 hover:shadow-md" title="Bagikan ke WhatsApp">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </a>
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="w-10 h-10 rounded-full flex items-center justify-center bg-[#1877F2] text-white hover:opacity-80 transition hover:-translate-y-1 hover:shadow-md" title="Bagikan ke Facebook">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <!-- X (Twitter) -->
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($berita->judul) }}" target="_blank" class="w-10 h-10 rounded-full flex items-center justify-center bg-black text-white hover:opacity-80 transition hover:-translate-y-1 hover:shadow-md" title="Bagikan ke X">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                            <a href="#" onclick="event.preventDefault(); navigator.clipboard.writeText(window.location.href); alert('Tautan berita ini tersalin ke clipboard! Silahkan buka aplikasi Instagram Anda.');" class="w-10 h-10 rounded-full flex items-center justify-center bg-linear-to-tr from-[#f09433] via-[#dc2743] to-[#bc1888] text-white hover:opacity-80 transition hover:-translate-y-1 hover:shadow-md" title="Bagikan ke Instagram (Salin Tautan)">
                                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                            <!-- Telegram -->
                            <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($berita->judul) }}" target="_blank" class="w-10 h-10 rounded-full flex items-center justify-center bg-[#0088cc] text-white hover:opacity-80 transition hover:-translate-y-1 hover:shadow-md" title="Bagikan ke Telegram">
                                <svg class="w-5 h-5 ml-[-2px]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 24c6.627 0 12-5.373 12-12S18.627 0 12 0 0 5.373 0 12s5.373 12 12 12zm5.894-15.545c.421-2.02.13-2.316-.763-2.016l-10.74 4.143c-1.32.531-1.309 1.267-.241 1.595l2.76 1.042 1.341 4.298c.189.585.093.81.65.81.428 0 .616-.195.856-.425l2.052-1.996 4.27 3.155c.787.433 1.346.21 1.541-.741l2.784-13.125a1.867 1.867 0 00-4.51-3.26zM11.907 14.65c-.172.6-1.5 5.584-1.5 5.584s1.777-1.488 2.055-1.748c.036-.034.07-.066.1-.097l-2.053-.941.002-.001 5.962-4.135c.783-.497.106.326.106.326L11.907 14.65z"/></svg>
                            </a>
                        </div>
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
