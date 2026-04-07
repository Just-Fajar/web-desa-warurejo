{{--
    PUBLIC POTENSI INDEX
    
    Halaman list potensi desa untuk pengunjung
    
    FEATURES:
    - Hero section dengan green gradient
    - Search filter (nama potensi)
    - Kategori filter (pertanian/peternakan/umkm/wisata/lainnya)
    - Sort options (Terbaru/Terlama/Terpopuler)
    - Grid layout responsive (1/2/3 columns)
    - Card dengan image, kategori badge, excerpt
    - View counter display
    - Contact info (telepon/WhatsApp)
    - Pagination (12 items per page)
    
    KATEGORI POTENSI:
    - Pertanian: Padi, sayuran, buah-buahan
    - Peternakan: Sapi, kambing, ayam
    - Perikanan: Ikan, udang, kolam
    - UMKM: Usaha kecil menengah
    - Wisata: Destinasi wisata desa
    - Kerajinan: Produk kerajinan lokal
    - Lainnya: Potensi lain
    
    CARD CONTENT:
    - Featured image (fallback default-potensi.jpg)
    - Kategori badge dengan color coding
    - Nama potensi
    - Excerpt (150 chars, strip HTML)
    - Lokasi (jika ada)
    - Contact info (telepon/WhatsApp clickable)
    - Views counter
    - "Lihat Detail" button
    
    RESPONSIVE:
    - Mobile: 1 column, full-width cards
    - Tablet: 2 columns
    - Desktop: 3 columns grid
    
    SEO:
    - Title: Potensi Desa - Desa Warurejo
    - Meta description: Kekayaan dan potensi desa
    - Slug-based URLs (/potensi/{slug})
    
    DATA:
    $potensi: Paginated collection dari PotensiDesaRepository
    Request params: search, kategori, urutkan
    
    Route: /potensi
    Controller: Public\PotensiController@index
--}}
@extends('public.layouts.app')

@section('title', 'Potensi Desa - Desa Warurejo')

@section('content')
{{-- Hero/Header Section --}}
<section class="pt-32 pb-8 bg-gray-50 border-b border-gray-100 relative overflow-hidden">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-emerald-100 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-72 h-72 bg-emerald-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl scroll-reveal">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100/80 text-emerald-700 text-sm font-bold tracking-wide uppercase mb-4 border border-emerald-200 shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                KEKAYAAN DESA
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 mb-4 tracking-tight">
                Potensi <span class="text-emerald-600">Desa</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-600 max-w-2xl leading-relaxed font-medium">
                Kekayaan alam, SDM, dan sektor usaha yang menggerakkan ekonomi Desa Warurejo.
            </p>
        </div>
    </div>
</section>

{{-- Potensi Content --}}
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            {{-- Filter & Search --}}
            <div class="mb-6 sm:mb-8 bg-white rounded-lg shadow-md p-4 sm:p-6 scroll-reveal">
                <form method="GET" action="{{ route('potensi.index') }}" class="flex flex-col gap-3 sm:gap-4">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Cari potensi desa..." 
                            value="{{ request('search') }}"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg text-sm sm:text-base focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    <select 
                        name="kategori" 
                        class="px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg text-sm sm:text-base focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                        <option value="">Semua Kategori</option>
                        <option value="pertanian" {{ request('kategori') == 'pertanian' ? 'selected' : '' }}>Pertanian</option>
                        <option value="peternakan" {{ request('kategori') == 'peternakan' ? 'selected' : '' }}>Peternakan</option>
                        <option value="umkm" {{ request('kategori') == 'umkm' ? 'selected' : '' }}>UMKM</option>
                        <option value="wisata" {{ request('kategori') == 'wisata' ? 'selected' : '' }}>Wisata</option>
                        <option value="lainnya" {{ request('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    <select 
                        name="urutkan" 
                        class="px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg text-sm sm:text-base focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                        <option value="terbaru" {{ request('urutkan', 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terlama" {{ request('urutkan') === 'terlama' ? 'selected' : '' }}>Terlama</option>
                        <option value="terpopuler" {{ request('urutkan') === 'terpopuler' ? 'selected' : '' }}>Terpopuler</option>
                    </select>
                    <button 
                        type="submit" 
                        class="px-4 sm:px-6 py-2.5 sm:py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold text-sm sm:text-base"
                    >
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari
                    </button>
                    </div>
                </form>
            </div>

            {{-- Potensi List --}}
            @if(isset($potensi) && $potensi->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
                    @foreach($potensi as $item)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition group scroll-reveal-stagger">
                            {{-- Gambar --}}
                            <div class="relative overflow-hidden h-44 sm:h-48 md:h-56">
                                <img 
                                    src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('images/default-potensi.jpg') }}" 
                                    alt="{{ $item->nama }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
                                    onerror="this.src='{{ asset('images/logo-web-desa.jpg') }}'"
                                >
                                
                                {{-- Category Badge --}}
                                <div class="absolute top-2 sm:top-3 right-2 sm:right-3">
                                    @php
                                        $kategoriColors = [
                                            'pertanian' => 'bg-green-600',
                                            'peternakan' => 'bg-amber-600',
                                            'perikanan' => 'bg-blue-600',
                                            'umkm' => 'bg-purple-600',
                                            'wisata' => 'bg-pink-600',
                                            'kerajinan' => 'bg-indigo-600',
                                            'lainnya' => 'bg-gray-600',
                                        ];
                                        $bgColor = $kategoriColors[$item->kategori ?? 'lainnya'] ?? 'bg-gray-600';
                                    @endphp
                                    <span class="px-2 sm:px-3 py-1 {{ $bgColor }} text-white text-xs font-semibold rounded-full uppercase">
                                        {{ ucfirst($item->kategori ?? 'Lainnya') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-4 sm:p-6">
                                {{-- Title --}}
                                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3 group-hover:text-green-600 transition line-clamp-2">
                                    {{ $item->nama }}
                                </h3>

                                {{-- Description --}}
                                <p class="text-gray-600 mb-3 sm:mb-4 line-clamp-3 text-sm sm:text-base">
                                    {{ $item->deskripsi_singkat ?? Str::limit(strip_tags($item->deskripsi), 120) }}
                                </p>

                                {{-- Meta Info --}}
                                @if($item->lokasi)
                                    <div class="flex items-center text-xs sm:text-sm text-gray-500 mb-3 sm:mb-4">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $item->lokasi }}</span>
                                    </div>
                                @endif

                                {{-- Read More --}}
                                <a 
                                    href="{{ route('potensi.show', $item->slug) }}" 
                                    class="inline-flex items-center text-green-600 hover:text-green-700 font-semibold text-sm sm:text-base"
                                >
                                    Lihat Detail
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 ml-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($potensi->hasPages())
                    <div class="mt-8 sm:mt-10 md:mt-12">
                        {{ $potensi->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 md:p-12 text-center">
                    <div class="inline-block p-4 sm:p-6 bg-gray-100 rounded-full mb-4 sm:mb-6">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Data Potensi</h3>
                    <p class="text-gray-600 mb-6">
                        @if(request('search') || request('kategori'))
                            Tidak ditemukan potensi dengan kriteria yang Anda cari.
                        @else
                            Saat ini belum ada data potensi desa yang ditampilkan. Silakan kembali lagi nanti.
                        @endif
                    </p>
                    @if(request('search') || request('kategori'))
                        <a href="{{ route('potensi.index') }}" class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                            Lihat Semua Potensi
                        </a>
                    @endif
                </div>
            @endif

        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-16 bg-linear-to-r from-green-600 to-green-700">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                Tertarik dengan Potensi Desa?
            </h2>
            <p class="text-lg text-green-100 mb-8">
                Hubungi kami untuk informasi lebih lanjut mengenai potensi dan kerjasama
            </p>
            <a 
                href="https://wa.me/62085168687700?text=Halo,%20saya%20ingin%20mengetahui%20lebih%20lanjut%20tentang%20potensi%20desa" 
                target="_blank"
                class="inline-flex items-center px-8 py-4 bg-white text-green-600 rounded-lg hover:bg-green-50 transition font-bold text-lg shadow-lg"
            >
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                Hubungi Kami
            </a>
        </div>
    </div>
</section>

<style>
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
