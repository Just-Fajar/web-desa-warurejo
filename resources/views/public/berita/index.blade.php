@extends('public.layouts.app')

@section('title', 'Berita Desa - Desa Warurejo')

@section('content')
{{-- Hero/Header Section --}}
<section class="pt-32 pb-8 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto scroll-reveal">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-px bg-primary-500"></div>
                <span class="text-primary-600 text-sm font-semibold tracking-widest uppercase">Kabar Desa</span>
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-gray-900 mb-4 tracking-tight">
                Berita <span class="text-primary-600">Desa</span>
            </h1>
            <p class="text-base sm:text-lg text-gray-500 max-w-2xl">
                Pusat Informasi dan Kabar Terbaru dari Desa Warurejo
            </p>
        </div>
    </div>
</section>

{{-- Berita Content --}}
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            {{-- Search & Filter --}}
            {{-- Search & Filter Modern --}}
            <div class="mb-10 relative z-20 scroll-reveal">
                <form method="GET" action="{{ route('berita.index') }}" class="bg-white rounded-2xl shadow-xl shadow-black/5 p-4 md:p-6 ring-1 ring-black/5" x-data="searchAutocomplete()">
                    
                    {{-- Top Row: Big Search --}}
                    <div class="relative w-full mb-4">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            name="search" 
                            x-model="searchQuery"
                            @input.debounce.300ms="fetchSuggestions()"
                            @focus="showSuggestions = true"
                            @click.away="showSuggestions = false"
                            placeholder="Cari tahu apa yang sedang terjadi" 
                            value="{{ request('search') }}"
                            class="w-full pl-12 pr-4 py-4 md:text-lg border-2 border-gray-300 bg-white rounded-xl focus:ring-0 focus:border-primary-500 transition-colors text-gray-900 font-semibold placeholder-gray-500"
                            autocomplete="off"
                        >
                        
                        {{-- Autocomplete Dropdown --}}
                        <div x-show="showSuggestions && suggestions.length > 0" x-transition class="absolute z-30 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-2xl max-h-64 overflow-y-auto ring-1 ring-black/5">
                            <template x-for="suggestion in suggestions" :key="suggestion.url">
                                <a :href="suggestion.url" class="block px-4 py-3 hover:bg-primary-50 border-b border-gray-50 last:border-0 transition-colors">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-3 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                        <span x-text="suggestion.title" class="text-gray-700 font-medium"></span>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                    
                    {{-- Bottom Row: Filters --}}
                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="text" name="date_from" value="{{ request('date_from') }}" placeholder="Tanggal Mulai" class="datepicker w-full pl-10 pr-3 py-3 md:py-2.5 border-2 border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-900 focus:ring-primary-500 focus:border-primary-500 placeholder-gray-500" title="Tanggal Mulai">
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="text" name="date_to" value="{{ request('date_to') }}" placeholder="Tanggal Akhir" class="datepicker w-full pl-10 pr-3 py-3 md:py-2.5 border-2 border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-900 focus:ring-primary-500 focus:border-primary-500 placeholder-gray-500" title="Tanggal Akhir">
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                                </div>
                                <select name="sort" class="w-full pl-10 pr-8 py-3 md:py-2.5 border-2 border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-900 focus:ring-primary-500 focus:border-primary-500 appearance-none">
                                    <option value="latest" {{ request('sort') === 'latest' || !request('sort') ? 'selected' : '' }}>Terbaru</option>
                                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Terpopuler</option>
                                </select>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2 mt-2 md:mt-0">
                            @if(request()->anyFilled(['search', 'date_from', 'date_to', 'sort']))
                            <a href="{{ route('berita.index') }}" class="px-5 py-3 md:py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors inline-flex items-center">
                                Reset
                            </a>
                            @endif
                            <button type="submit" class="px-6 py-3 md:py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-all shadow-md shadow-primary-500/20 active:scale-95 inline-flex items-center justify-center w-full md:w-auto">
                                Cari Berita
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Berita List --}}
            @if(isset($berita) && $berita->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 items-start">
                    @foreach($berita as $item)
                        {{-- MODERN CARD DESIGN (from homepage) --}}
                        <div class="bg-white rounded-2xl p-3 border border-gray-100 shadow-sm hover:shadow-xl hover:border-primary-100 transition-all duration-300 group flex flex-col scroll-reveal-stagger ring-1 ring-black/5 hover:ring-primary-500/20">

                            {{-- Image Wrapper --}}
                            <a href="{{ route('berita.show', $item->slug) }}" class="relative block h-56 rounded-xl overflow-hidden mb-4">
                                @if($item->gambar_utama)
                                    <img src="{{ $item->gambar_utama_url }}" alt="{{ $item->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" decoding="async">
                                @else
                                    <div class="w-full h-full bg-linear-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                {{-- Gradient Overlay --}}
                                <div class="absolute inset-0 bg-linear-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                {{-- Category Badge & Date Badge --}}
                                <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-2.5 py-1.5 rounded-lg text-xs font-semibold text-gray-700 flex items-center gap-1.5 shadow-sm z-10">
                                    <span class="text-primary-600">Berita</span>
                                    <span class="text-gray-300">|</span>
                                    <span>{{ $item->published_at ? $item->published_at->format('d M Y') : $item->created_at->format('d M Y') }}</span>
                                </div>
                            </a>

                            {{-- Content --}}
                            <div class="px-2 pb-2 flex flex-col grow">
                                {{-- Views & Admin --}}
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="bg-gray-50 text-gray-500 px-2.5 py-1 rounded-md text-xs font-medium flex items-center gap-1.5 border border-gray-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        {{ number_format($item->views) }} kali dilihat
                                    </span>
                                    @if($item->admin)
                                        <span class="bg-gray-50 text-gray-500 px-2.5 py-1 rounded-md text-xs font-medium flex items-center gap-1.5 border border-gray-100 shrink-0 max-w-40" title="Dipublikasikan oleh {{ $item->admin->name ?? $item->admin->username }}">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            <span class="truncate">{{ $item->admin->name ?? $item->admin->username }}</span>
                                        </span>
                                    @endif
                                </div>

                                {{-- Judul --}}
                                <a href="{{ route('berita.show', $item->slug) }}" class="block mb-2">
                                    <h3 class="text-lg font-bold text-gray-800 leading-snug group-hover:text-primary-600 transition-colors line-clamp-2">
                                        {{ $item->judul }}
                                    </h3>
                                </a>

                                {{-- Deskripsi Expandable --}}
                                <div class="relative mb-4 grow cursor-pointer group/desc" onclick="this.classList.toggle('is-expanded')" title="Klik untuk memperluas teks">
                                    <div class="text-sm text-gray-600 leading-relaxed overflow-hidden max-h-[2.8rem] transition-all duration-500 custom-scrollbar pr-2 desc-text">
                                        {{ $item->excerpt ?? Str::limit(strip_tags($item->konten), 300) }}
                                    </div>
                                    <div class="absolute bottom-0 right-0 bg-white/95 px-1.5 text-[10px] text-primary-600 font-semibold rounded opacity-0 group-hover/desc:opacity-100 transition-opacity fade-hint shadow-sm">Klik deskripsi</div>
                                    <div class="absolute bottom-0 left-0 w-full h-5 bg-linear-to-t from-white to-transparent opacity-100 pointer-events-none transition-opacity duration-300 fade-overlay"></div>
                                </div>

                                {{-- Action Area --}}
                                <a href="{{ route('berita.show', $item->slug) }}" class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between group/action">
                                    <span class="text-primary-600 font-semibold text-sm group-hover/action:text-primary-700 transition-colors">
                                        Baca Selengkapnya
                                    </span>
                                    <span class="w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center group-hover/action:bg-primary-600 group-hover/action:text-white transition-all duration-300 transform group-hover/action:translate-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $berita->withQueryString()->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <div class="inline-block p-6 bg-gray-100 rounded-full mb-6">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Berita</h3>
                    <p class="text-gray-600 mb-6">
                        @if(request('search'))
                            Tidak ditemukan berita dengan kata kunci "{{ request('search') }}".
                        @else
                            Saat ini belum ada berita yang dipublikasikan. Silakan kembali lagi nanti.
                        @endif
                    </p>
                    @if(request('search'))
                        <a href="{{ route('berita.index') }}" class="inline-block px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold">
                            Lihat Semua Berita
                        </a>
                    @endif
                </div>
            @endif

        </div>
    </div>
</section>

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

@push('scripts')
<script>
function searchAutocomplete() {
    return {
        searchQuery: '{{ request("search") }}',
        suggestions: [],
        showSuggestions: false,
        
        async fetchSuggestions() {
            if (this.searchQuery.length < 2) {
                this.suggestions = [];
                return;
            }
            
            try {
                const response = await fetch(`{{ route('berita.autocomplete') }}?q=${encodeURIComponent(this.searchQuery)}`);
                this.suggestions = await response.json();
                this.showSuggestions = this.suggestions.length > 0;
            } catch (error) {
                console.error('Autocomplete error:', error);
                this.suggestions = [];
            }
        }
    }
}
</script>

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

/* Custom Scrollbar for Card Description Extrapolated from Home */
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
.custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #d1d5db; }

/* Expanded Description Style */
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
@endpush
