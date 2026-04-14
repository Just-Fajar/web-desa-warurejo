@extends('public.layouts.app')

@section('title', 'Galeri Dokumentasi - Desa Warurejo')

@section('content')
{{-- Hero/Header Section --}}
<section class="pt-32 pb-8 bg-gray-50 border-b border-gray-100 relative overflow-hidden">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-purple-100 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-72 h-72 bg-purple-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl scroll-reveal">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-purple-100/80 text-purple-700 text-sm font-bold tracking-wide uppercase mb-4 border border-purple-200 shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-purple-600 animate-pulse"></span>
                GALERI VISUAL
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 mb-4 tracking-tight">
                Dokumentasi <span class="text-purple-600">Desa</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-600 max-w-2xl leading-relaxed font-medium">
                Merekam jejak, kegiatan, dan momen berharga melalui lensa kamera di Desa Warurejo.
            </p>
        </div>
    </div>
</section>

{{-- Filter Section --}}
<section class="py-4 sm:py-6 md:py-8 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8 md:mb-12 bg-white rounded-2xl shadow-sm border border-gray-100 p-3 sm:p-4 scroll-reveal">
                <form method="GET" action="{{ route('galeri.index') }}" class="flex flex-col gap-3">
                    <div class="relative w-full mb-4 md:mb-2">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Cari foto" 
                            value="{{ request('search') }}"
                            class="w-full pl-12 pr-4 py-4 md:text-lg border-2 border-gray-100 rounded-xl focus:ring-0 focus:border-purple-500 transition-colors bg-gray-50/50 focus:bg-white text-gray-800"
                            autocomplete="off"
                        >
                    </div>

                    {{-- Bottom Row: Filters --}}
                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="text" name="date_from" value="{{ request('date_from') }}" placeholder="Tanggal Mulai" class="datepicker w-full pl-10 pr-3 py-3 md:py-2.5 border border-gray-100 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500 bg-gray-50/70" title="Tanggal Mulai">
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="text" name="date_to" value="{{ request('date_to') }}" placeholder="Tanggal Akhir" class="datepicker w-full pl-10 pr-3 py-3 md:py-2.5 border border-gray-100 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500 bg-gray-50/70" title="Tanggal Akhir">
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                </div>
                                <select 
                                    name="kategori" 
                                    class="w-full pl-10 pr-8 py-3 md:py-2.5 border border-gray-100 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500 bg-gray-50/70 appearance-none"
                                >
                                    <option value="">Semua Kategori</option>
                                    <option value="kegiatan" {{ request('kategori') == 'kegiatan' ? 'selected' : '' }}>Kegiatan Desa</option>
                                    <option value="infrastruktur" {{ request('kategori') == 'infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                                    <option value="budaya" {{ request('kategori') == 'budaya' ? 'selected' : '' }}>Budaya</option>
                                    <option value="umkm" {{ request('kategori') == 'umkm' ? 'selected' : '' }}>UMKM</option>
                                    <option value="lainnya" {{ request('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                                </div>
                                <select 
                                    name="urutkan" 
                                    class="w-full pl-10 pr-8 py-3 md:py-2.5 border border-gray-100 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500 bg-gray-50/70 appearance-none"
                                >
                                    <option value="terbaru" {{ request('urutkan', 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="terlama" {{ request('urutkan') === 'terlama' ? 'selected' : '' }}>Terlama</option>
                                    <option value="terpopuler" {{ request('urutkan') === 'terpopuler' ? 'selected' : '' }}>Terpopuler</option>
                                </select>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2 mt-2 md:mt-0">
                            @if(request()->anyFilled(['search', 'date_from', 'date_to', 'kategori', 'urutkan']))
                            <a href="{{ route('galeri.index') }}" class="px-5 py-3 md:py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors inline-flex items-center">
                                Reset
                            </a>
                            @endif
                            <button 
                                type="submit" 
                                class="px-6 py-3 md:py-2.5 text-sm font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-xl transition-all shadow-md shadow-purple-500/20 active:scale-95 inline-flex items-center justify-center w-full md:w-auto"
                            >
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- Gallery Grid --}}
<section class="py-8 sm:py-12 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            @if(isset($galeris) && $galeris->count() > 0)
                {{-- Masonry Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6" x-data="{ openModal: false, modalSrc: '', modalTitle: '', modalDesc: '', modalImages: [] }">
                    @foreach($galeris as $galeri)
                        <div class="bg-white rounded-2xl p-3 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col ring-1 ring-black/5 hover:ring-purple-500/20 cursor-pointer"
                             @click="openModal = true; modalImages = @js($galeri->images->map(fn($img) => ['url' => asset('storage/' . $img->image_path)])->toArray()); modalTitle = '{{ addslashes($galeri->judul) }}'; modalDesc = '{{ addslashes($galeri->deskripsi ?? '') }}'">
                            
                            {{-- Image Wrapper --}}
                            <div class="relative block h-56 rounded-xl overflow-hidden mb-4">
                                @if($galeri->images && $galeri->images->count() > 0)
                                    <img 
                                        src="{{ $galeri->images->first()->image_url }}" 
                                        alt="{{ $galeri->judul }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    >
                                @else
                                    <div class="w-full h-full bg-linear-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                {{-- Gradient Overlay --}}
                                <div class="absolute inset-0 bg-linear-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>


                                
                                {{-- Multiple Images Indicator --}}
                                @if($galeri->images && $galeri->images->count() > 1)
                                    <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-sm px-2.5 py-1 rounded-md text-xs font-semibold text-white flex items-center gap-1.5 shadow-sm">
                                        <i class="fas fa-images"></i>{{ $galeri->images->count() }}
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Content --}}
                            <div class="px-2 pb-2 flex flex-col grow">
                                {{-- Meta Info --}}
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="bg-gray-50 text-gray-500 px-2.5 py-1 rounded-md text-xs font-medium border border-gray-100">
                                        Dokumentasi Desa
                                    </span>
                                    @php
                                        $kategoriTextColors = [
                                            'kegiatan' => 'text-green-600',
                                            'infrastruktur' => 'text-amber-600',
                                            'budaya' => 'text-purple-600',
                                            'umkm' => 'text-blue-600',
                                            'lainnya' => 'text-rose-600',
                                        ];
                                        $textColor = $kategoriTextColors[strtolower($galeri->kategori ?? '')] ?? 'text-gray-600';
                                    @endphp
                                    <span class="bg-gray-50 {{ $textColor }} px-2.5 py-1 rounded-md text-xs font-bold border border-gray-100 uppercase">
                                        {{ $galeri->kategori ?? 'Galeri' }}
                                    </span>
                                    <span class="text-gray-300">|</span>
                                    <span class="text-gray-500 text-xs font-medium">
                                        {{ $galeri->tanggal ? \Carbon\Carbon::parse($galeri->tanggal)->format('d M Y') : 'Tanpa Tanggal' }}
                                    </span>
                                </div>

                                {{-- Title --}}
                                <h3 class="text-lg font-bold text-gray-800 leading-snug group-hover:text-purple-600 transition-colors line-clamp-2 mb-2">
                                    {{ $galeri->judul }}
                                </h3>
                                
                                {{-- Deskripsi Expandable --}}
                                <div class="relative mb-4 grow cursor-pointer group/desc" @click.stop="$el.classList.toggle('is-expanded')" title="Klik untuk memperluas teks">
                                    <div class="text-sm text-gray-600 leading-relaxed overflow-hidden max-h-[2.8rem] transition-all duration-500 custom-scrollbar pr-2 desc-text">
                                        {{ $galeri->deskripsi ? strip_tags($galeri->deskripsi) : 'Preview galeri gambar.' }}
                                    </div>
                                    <div class="absolute bottom-0 right-0 bg-white/95 px-1.5 text-[10px] text-purple-600 font-semibold rounded opacity-0 group-hover/desc:opacity-100 transition-opacity fade-hint shadow-sm">Klik deskripsi</div>
                                    <div class="absolute bottom-0 left-0 w-full h-5 bg-linear-to-t from-white to-transparent opacity-100 pointer-events-none transition-opacity duration-300 fade-overlay"></div>
                                </div>

                                {{-- Action Area --}}
                                <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between group/action">
                                    <span class="text-purple-600 font-semibold text-sm group-hover/action:text-purple-700 transition-colors">
                                        Lihat Dokumentasi
                                    </span>
                                    <span class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center group-hover/action:bg-purple-600 group-hover/action:text-white transition-all duration-300 transform group-hover/action:translate-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    {{-- Modal with Image Carousel --}}
                    <div x-show="openModal" 
                         x-cloak
                         @click.self="openModal = false"
                         class="fixed inset-0 flex items-center justify-center p-2 sm:p-4 bg-gray-900/60 backdrop-blur-md"
                         style="z-index: 9999;"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         x-data="{ currentIndex: 0 }">
                        
                        {{-- Close Button --}}
                        <button @click="openModal = false" 
                                class="absolute top-4 right-4 sm:top-6 sm:right-8 w-12 h-12 shadow-2xl bg-white hover:bg-gray-100 text-gray-900 rounded-full flex items-center justify-center transition-all hover:scale-110"
                                style="z-index: 10000;">
                            <span class="sr-only">Tutup</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        
                        <div class="relative w-full max-w-6xl flex flex-col items-center justify-center h-full max-h-dvh py-8"
                             @click.stop>
                            
                            {{-- Image Carousel --}}
                            <div class="relative w-full flex-1 flex flex-col items-center justify-center min-h-0">
                                <template x-for="(image, index) in modalImages" :key="index">
                                    <img x-show="currentIndex === index" 
                                         :src="image.url" 
                                         :alt="modalTitle" 
                                         class="max-w-full max-h-full object-contain rounded-lg shadow-2xl drop-shadow-2xl"
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100">
                                </template>
                                
                                {{-- Navigation Arrows --}}
                                <template x-if="modalImages.length > 1">
                                    <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex items-center justify-between px-2 sm:px-8 pointer-events-none">
                                        <button @click="currentIndex = currentIndex > 0 ? currentIndex - 1 : modalImages.length - 1" 
                                                class="pointer-events-auto w-10 h-10 sm:w-12 sm:h-12 bg-black/50 hover:bg-black/80 text-white rounded-full flex items-center justify-center transition backdrop-blur border border-white/10">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        <button @click="currentIndex = currentIndex < modalImages.length - 1 ? currentIndex + 1 : 0" 
                                                class="pointer-events-auto w-10 h-10 sm:w-12 sm:h-12 bg-black/50 hover:bg-black/80 text-white rounded-full flex items-center justify-center transition backdrop-blur border border-white/10">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            
                            {{-- Info and Actions Bottom Bar --}}
                            <div class="w-full max-w-4xl mt-6 border border-white/10 bg-black/40 backdrop-blur-md rounded-2xl p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shrink-0">
                                <div class="flex-1 text-left w-full">
                                    <h2 class="text-xl md:text-2xl font-bold text-white mb-2" x-text="modalTitle"></h2>
                                    <div class="text-gray-300 text-sm md:text-base leading-relaxed overflow-y-auto max-h-32 custom-scrollbar pr-2" x-text="modalDesc || 'Tidak ada deskripsi'"></div>
                                </div>
                                <div class="flex items-center gap-3 shrink-0 w-full md:w-auto mt-2 md:mt-0 pt-3 md:pt-0 border-t border-white/10 md:border-0 relative top-[-5px]">
                                    <span x-show="modalImages.length > 1" class="text-white/80 text-sm font-semibold bg-white/10 px-3 py-2 rounded-xl" x-text="(currentIndex + 1) + ' / ' + modalImages.length"></span>
                                    {{-- Download Button --}}
                                    <a :href="modalImages[currentIndex]?.url" download class="flex-1 md:flex-none flex items-center justify-center gap-2 px-6 py-2 bg-linear-to-r from-purple-600 to-purple-800 hover:from-purple-500 hover:to-purple-700 text-white font-bold rounded-xl transition hover:-translate-y-0.5 shadow-lg shadow-purple-900/50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Unduh
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-8 sm:mt-10 md:mt-12">
                    {{ $galeris->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 md:p-12 text-center">
                    <div class="inline-block p-4 sm:p-6 bg-gray-100 rounded-full mb-4 sm:mb-6">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2 sm:mb-3">Belum Ada Dokumentasi</h3>
                    <p class="text-gray-600 mb-6 sm:mb-8 text-sm sm:text-base">
                        @if(request('search') || request('tipe') || request('kategori'))
                            Tidak ditemukan dokumentasi dengan kriteria yang Anda cari.
                        @else
                            Saat ini belum ada dokumentasi foto atau video yang tersedia. Silakan kembali lagi nanti.
                        @endif
                    </p>
                    @if(request('search') || request('tipe') || request('kategori'))
                        <a href="{{ route('galeri.index') }}" class="inline-block px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-semibold">
                            Lihat Semua Dokumentasi
                        </a>
                    @endif
                </div>
            @endif

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

/* Expandable Description */
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
.custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #cbd5e1; }
.is-expanded .desc-text { max-height: 12rem !important; overflow-y: auto !important; }
.is-expanded .fade-overlay, .is-expanded .fade-hint { opacity: 0 !important; pointer-events: none; }
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

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr('.datepicker', {
            locale: 'id',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'l, j F Y',
            maxDate: 'today',
            disableMobile: true
        });
    });
</script>
@endpush
