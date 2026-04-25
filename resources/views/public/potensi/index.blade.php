@extends('public.layouts.app')

@section('title', 'Potensi Desa - Desa Warurejo')

@section('content')
    {{-- Hero/Header Section --}}
    <section class="pt-32 pb-8 bg-gray-50 border-b border-gray-100 relative overflow-hidden">
        {{-- Decorative Background Elements --}}
        <div
            class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-emerald-100 rounded-full blur-3xl opacity-50 pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 left-0 -mb-20 -ml-20 w-72 h-72 bg-emerald-50 rounded-full blur-3xl opacity-50 pointer-events-none">
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl scroll-reveal">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100/80 text-emerald-700 text-sm font-bold tracking-wide uppercase mb-4 border border-emerald-200 shadow-sm">
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
                <div class="mb-8 md:mb-12 bg-white rounded-2xl shadow-sm border border-gray-100 p-3 sm:p-4 scroll-reveal">
                    <form method="GET" action="{{ route('potensi.index') }}" class="flex flex-col gap-3">
                        <div class="relative w-full mb-4 md:mb-2">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" placeholder="Cari potensi desa..."
                                value="{{ request('search') }}"
                                class="w-full pl-12 pr-4 py-4 md:text-lg border-2 border-gray-100 rounded-xl focus:ring-0 focus:border-green-500 transition-colors bg-gray-50/50 focus:bg-white text-gray-800"
                                autocomplete="off">
                        </div>

                        {{-- Bottom Row: Filters --}}
                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-3">
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
                                        class="datepicker w-full pl-10 pr-3 py-3 md:py-2.5 border border-gray-100 rounded-lg text-sm focus:ring-green-500 focus:border-green-500 bg-gray-50/70"
                                        title="Tanggal Mulai">
                                </div>
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
                                        class="datepicker w-full pl-10 pr-3 py-3 md:py-2.5 border border-gray-100 rounded-lg text-sm focus:ring-green-500 focus:border-green-500 bg-gray-50/70"
                                        title="Tanggal Akhir">
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <select name="kategori"
                                        class="w-full pl-10 pr-8 py-3 md:py-2.5 border border-gray-100 rounded-lg text-sm focus:ring-green-500 focus:border-green-500 bg-gray-50/70 appearance-none">
                                        <option value="">Semua Kategori</option>
                                        <option value="pertanian" {{ request('kategori') == 'pertanian' ? 'selected' : '' }}>
                                            Pertanian</option>
                                        <option value="peternakan" {{ request('kategori') == 'peternakan' ? 'selected' : '' }}>Peternakan</option>
                                        <option value="perikanan" {{ request('kategori') == 'perikanan' ? 'selected' : '' }}>Perikanan</option>
                                        <option value="umkm" {{ request('kategori') == 'umkm' ? 'selected' : '' }}>UMKM
                                        </option>
                                        <option value="wisata" {{ request('kategori') == 'wisata' ? 'selected' : '' }}>Wisata
                                        </option>
                                        <option value="lainnya" {{ request('kategori') == 'lainnya' ? 'selected' : '' }}>
                                            Lainnya</option>
                                    </select>
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                                        </svg>
                                    </div>
                                    <select name="urutkan"
                                        class="w-full pl-10 pr-8 py-3 md:py-2.5 border border-gray-100 rounded-lg text-sm focus:ring-green-500 focus:border-green-500 bg-gray-50/70 appearance-none">
                                        <option value="terbaru" {{ request('urutkan', 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="terlama" {{ request('urutkan') === 'terlama' ? 'selected' : '' }}>
                                            Terlama</option>
                                        <option value="terpopuler" {{ request('urutkan') === 'terpopuler' ? 'selected' : '' }}>Terpopuler</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-2 mt-2 md:mt-0">
                                @if(request()->anyFilled(['search', 'date_from', 'date_to', 'kategori', 'urutkan']))
                                    <a href="{{ route('potensi.index') }}"
                                        class="px-5 py-3 md:py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors inline-flex items-center">
                                        Reset
                                    </a>
                                @endif
                                <button type="submit"
                                    class="px-6 py-3 md:py-2.5 text-sm font-bold text-white bg-green-600 hover:bg-green-700 rounded-xl transition-all shadow-md shadow-green-500/20 active:scale-95 inline-flex items-center justify-center w-full md:w-auto">
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Potensi List --}}
                @if(isset($potensi) && $potensi->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
                        @foreach($potensi as $item)
                                        <article
                                            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition group scroll-reveal-stagger">
                                            {{-- Gambar --}}
                                            <div class="relative overflow-hidden h-44 sm:h-48 md:h-56">
                                                <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('images/default-potensi.jpg') }}"
                                                    alt="{{ $item->nama }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
                                                    onerror="this.src='{{ asset('images/logo-web-desa.jpg') }}'">

                                                {{-- Category Badge --}}
                                                <div class="absolute top-2 sm:top-3 right-2 sm:right-3">
                                                    @php
                                                        $kategoriColors = [
                                                            'pertanian' => 'bg-green-600',
                                                            'peternakan' => 'bg-amber-600',
                                                            'perikanan' => 'bg-blue-600',
                                                            'umkm' => 'bg-purple-600',
                                                            'wisata' => 'bg-pink-600',
                                                            'lainnya' => 'bg-gray-600',
                                                        ];
                                                        $bgColor = $kategoriColors[$item->kategori ?? 'lainnya'] ?? 'bg-gray-600';
                                                    @endphp
                              <span
                                                        class="px-2 sm:px-3 py-1 {{ $bgColor }} text-white text-xs font-semibold rounded-full uppercase">
                                                        {{ ucfirst($item->kategori ?? 'Lainnya') }}
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Content --}}
                                            <div class="p-4 sm:p-6">
                                                {{-- Title --}}
                                                <h3
                                                    class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3 group-hover:text-green-600 transition line-clamp-2">
                                                    {{ $item->nama }}
                                                </h3>

                                                {{-- Description --}}
                                                <p class="text-gray-600 mb-3 sm:mb-4 line-clamp-3 text-sm sm:text-base">
                                                    {{ $item->deskripsi_singkat ?? Str::limit(strip_tags($item->deskripsi), 120) }}
                                                </p>

                                                {{-- Meta Info --}}
                                                @if($item->lokasi)
                                                    <div class="flex items-center text-xs sm:text-sm text-gray-500 mb-3 sm:mb-4">
                                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 shrink-0" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <span>{{ $item->lokasi }}</span>
                                                    </div>
                                                @endif

                                                {{-- Read More --}}
                                                <a href="{{ route('potensi.show', $item->slug) }}"
                                                    class="inline-flex items-center text-green-600 hover:text-green-700 font-semibold text-sm sm:text-base">
                                                    Lihat Detail
                                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 ml-2 group-hover:translate-x-1 transition" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 5l7 7-7 7" />
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
                            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
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
                            <a href="{{ route('potensi.index') }}"
                                class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                                Lihat Semua Potensi
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
    </style>

    <script>
        // Scroll-triggered animation observer
        document.addEventListener('DOMContentLoaded', function () {
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
        document.addEventListener('DOMContentLoaded', function () {
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