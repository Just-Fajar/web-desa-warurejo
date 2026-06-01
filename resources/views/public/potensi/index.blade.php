@extends('public.layouts.app')

@section('title', 'Potensi Desa - Desa Warurejo')

@section('content')
    {{-- Hero/Header Section --}}
    <section class="pt-32 pb-8 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto scroll-reveal">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-px bg-emerald-500"></div>
                    <span class="text-emerald-600 text-sm font-semibold tracking-widest uppercase">Kekayaan Desa</span>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-gray-900 mb-4 tracking-tight">
                    Potensi <span class="text-emerald-600">Desa</span>
                </h1>
                <p class="text-base sm:text-lg text-gray-500 max-w-2xl">
                    Kenali lebih dekat berbagai potensi, SDA, dan daya tarik di Desa Warurejo
                </p>
            </div>
        </div>
    </section>

    {{-- Potensi Content --}}
    <section class="py-8 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">

                {{-- Search & Filter Modern --}}
                <div class="mb-10 relative z-20 scroll-reveal">
                    <form method="GET" action="{{ route('potensi.index') }}"
                        class="bg-white rounded-2xl shadow-xl shadow-black/5 p-4 md:p-6 ring-1 ring-black/5"
                        x-data="{ searchQuery: '{{ request('search') }}' }">

                        {{-- Top Row: Big Search --}}
                        <div class="relative w-full mb-4">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" x-model="searchQuery" placeholder="Cari tahu potensi desa..."
                                value="{{ request('search') }}"
                                class="w-full pl-12 pr-4 py-4 md:text-lg border-2 border-gray-300 bg-white rounded-xl focus:ring-0 focus:border-emerald-500 transition-colors text-gray-900 font-semibold placeholder-gray-500"
                                autocomplete="off">
                        </div>

                        {{-- Bottom Row: Filters --}}
                        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                                {{-- Tanggal Mulai --}}
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="date_from" value="{{ request('date_from') }}"
                                        placeholder="Tanggal Mulai"
                                        class="datepicker w-full pl-10 pr-3 py-3 md:py-2.5 border-2 border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-900 focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-500"
                                        title="Tanggal Mulai">
                                </div>
                                {{-- Tanggal Akhir --}}
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="date_to" value="{{ request('date_to') }}"
                                        placeholder="Tanggal Akhir"
                                        class="datepicker w-full pl-10 pr-3 py-3 md:py-2.5 border-2 border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-900 focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-500"
                                        title="Tanggal Akhir">
                                </div>
                                {{-- Kategori --}}
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                    </div>
                                    <select name="kategori"
                                        class="w-full pl-10 pr-8 py-3 md:py-2.5 border-2 border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-900 focus:ring-emerald-500 focus:border-emerald-500 appearance-none">
                                        <option value="">Semua Kategori</option>
                                        @foreach($kategoriList as $key => $label)
                                            <option value="{{ $key }}" {{ request('kategori') === $key ? 'selected' : '' }}>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Urutkan --}}
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                                        </svg>
                                    </div>
                                    <select name="sort"
                                        class="w-full pl-10 pr-8 py-3 md:py-2.5 border-2 border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-900 focus:ring-emerald-500 focus:border-emerald-500 appearance-none">
                                        <option value="latest" {{ request('sort') === 'latest' || !request('sort') ? 'selected' : '' }}>Terbaru</option>
                                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama
                                        </option>
                                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>
                                            Terpopuler</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-2 mt-2 lg:mt-0">
                                @if(request()->anyFilled(['search', 'kategori', 'sort', 'date_from', 'date_to']))
                                    <a href="{{ route('potensi.index') }}"
                                        class="px-5 py-3 md:py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors inline-flex items-center">
                                        Reset
                                    </a>
                                @endif
                                <button type="submit"
                                    class="px-6 py-3 md:py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-all shadow-md shadow-emerald-500/20 active:scale-95 inline-flex items-center justify-center w-full md:w-auto">
                                    Cari Potensi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Potensi Grid --}}
                @if(isset($potensi) && $potensi->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 items-start">
                        @foreach($potensi as $item)
                            {{-- MODERN CARD DESIGN --}}
                            <div
                                class="bg-white rounded-2xl p-3 border border-gray-100 shadow-sm hover:shadow-xl hover:border-emerald-100 transition-all duration-300 group flex flex-col scroll-reveal-stagger ring-1 ring-black/5 hover:ring-emerald-500/20">

                                {{-- Image Wrapper --}}
                                <a href="{{ route('potensi.show', $item->slug) }}"
                                    class="relative block h-56 rounded-xl overflow-hidden mb-4">
                                    <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('images/logo-web-desa.webp') }}"
                                        alt="{{ $item->nama }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        loading="lazy" decoding="async"
                                        onerror="this.src='{{ asset('images/logo-web-desa.webp') }}'">

                                    {{-- Gradient Overlay --}}
                                    <div
                                        class="absolute inset-0 bg-linear-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>

                                    {{-- Category Badge & Date Badge --}}
                                    @php $colors = $kategoriBadgeColors[$item->kategori ?? 'lainnya'] ?? ['bg' => '#F3F4F6', 'text' => '#4B5563']; @endphp
                                    <div
                                        class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-2.5 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-sm z-10">
                                        <span
                                            style="color: {{ $colors['text'] }};">{{ ucfirst($item->kategori ?? 'Lainnya') }}</span>
                                        <span class="text-gray-300 font-normal">|</span>
                                        <span
                                            class="text-gray-700 font-medium">{{ $item->published_at ? $item->published_at->format('d M Y') : $item->created_at->format('d M Y') }}</span>
                                    </div>
                                </a>

                                {{-- Content --}}
                                <div class="px-2 pb-2 flex flex-col grow">
                                    {{-- Views & Location --}}
                                    <div class="flex items-center gap-2 mb-3">
                                        <span
                                            class="bg-gray-50 text-gray-500 px-2.5 py-1 rounded-md text-xs font-medium flex items-center gap-1.5 border border-gray-100">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            {{ number_format($item->views) }} kali dilihat
                                        </span>
                                        @if($item->lokasi)
                                            <span
                                                class="bg-gray-50 text-gray-500 px-2.5 py-1 rounded-md text-xs font-medium flex items-center gap-1.5 border border-gray-100 shrink-0 max-w-40"
                                                title="{{ $item->lokasi }}">
                                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span class="truncate">{{ $item->lokasi }}</span>
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Judul --}}
                                    <a href="{{ route('potensi.show', $item->slug) }}" class="block mb-2">
                                        <h3
                                            class="text-lg font-bold text-gray-800 leading-snug group-hover:text-emerald-600 transition-colors line-clamp-2">
                                            {{ $item->nama }}
                                        </h3>
                                    </a>

                                    {{-- Info Utama --}}
                                    @if($item->info_utama)
                                        <div class="text-sm text-emerald-600 font-medium mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="truncate">{{ $item->info_utama }}</span>
                                        </div>
                                    @endif

                                    {{-- Deskripsi Expandable --}}
                                    <div class="relative mb-4 grow cursor-pointer group/desc"
                                        onclick="this.classList.toggle('is-expanded')" title="Klik untuk memperluas teks">
                                        <div
                                            class="text-sm text-gray-600 leading-relaxed overflow-hidden max-h-[2.8rem] transition-all duration-500 custom-scrollbar pr-2 desc-text">
                                            {{ Str::limit(strip_tags($item->deskripsi), 300) }}
                                        </div>
                                        <div
                                            class="absolute bottom-0 right-0 bg-white/95 px-1.5 text-[10px] text-emerald-600 font-semibold rounded opacity-0 group-hover/desc:opacity-100 transition-opacity fade-hint shadow-sm">
                                            Klik deskripsi</div>
                                        <div
                                            class="absolute bottom-0 left-0 w-full h-5 bg-linear-to-t from-white to-transparent opacity-100 pointer-events-none transition-opacity duration-300 fade-overlay">
                                        </div>
                                    </div>

                                    {{-- Action Area --}}
                                    <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                                        <a href="{{ route('potensi.show', $item->slug) }}" class="group/action flex items-center">
                                            <span
                                                class="text-emerald-600 font-semibold text-sm group-hover/action:text-emerald-700 transition-colors">
                                                Baca Selengkapnya
                                            </span>
                                            <span
                                                class="ml-2 w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover/action:bg-emerald-600 group-hover/action:text-white transition-all duration-300 transform group-hover/action:translate-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </span>
                                        </a>
                                        @if($item->whatsapp)
                                            <a href="{{ $item->whatsapp_link }}" target="_blank" rel="noopener"
                                                class="text-green-500 hover:text-green-600 p-2 bg-green-50 hover:bg-green-100 rounded-full transition-colors"
                                                title="Hubungi via WhatsApp">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($potensi->hasPages())
                        <div class="mt-12">
                            {{ $potensi->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    {{-- Empty State --}}
                    <div class="bg-white rounded-lg shadow-md p-12 text-center border border-gray-100">
                        <div class="inline-block p-6 bg-gray-100 rounded-full mb-6">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Data Potensi</h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                            @if(request('search') || request('kategori'))
                                Tidak ditemukan potensi dengan kriteria pencarian Anda. Coba dengan kata kunci lain.
                            @else
                                Belum ada data potensi desa yang dipublikasikan saat ini.
                            @endif
                        </p>
                        @if(request('search') || request('kategori'))
                            <a href="{{ route('potensi.index') }}"
                                class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali ke Semua Potensi
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
    <style>
        .scroll-reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .scroll-reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        .scroll-reveal-stagger {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .scroll-reveal-stagger.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        .is-expanded .desc-text {
            max-height: 500px !important;
        }

        .is-expanded .fade-overlay {
            opacity: 0 !important;
        }

        .is-expanded .fade-hint {
            opacity: 0 !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Datepicker
            flatpickr('.datepicker', {
                locale: 'id',
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'l, j F Y',
                maxDate: 'today',
                disableMobile: true
            });

            // Scroll animations
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('revealed');
                        }, index * 100);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

            document.querySelectorAll('.scroll-reveal, .scroll-reveal-stagger').forEach(el => observer.observe(el));
        });
    </script>
@endpush