@extends('public.layouts.app')

@section('title', 'Publikasi - ' . $kategori)

@section('content')
    <!-- Header Section -->
    <section class="pt-32 pb-4 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto scroll-reveal">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-px bg-primary-500"></div>
                    <span class="text-primary-600 text-sm font-semibold tracking-widest uppercase">Dokumen Desa</span>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-gray-900 mb-4 tracking-tight">
                    Publikasi <span class="text-primary-600">Desa</span>
                </h1>
                <p class="text-base sm:text-lg text-gray-500 max-w-2xl">
                    Arsip Dokumen {{ $kategori }} Resmi Desa Warurejo
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-4 sm:py-8 md:py-12 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6 sm:gap-8">
                <!-- Main Content -->
                <div class="lg:w-3/4">
                    <!-- Filter Section -->
                    <div
                        class="mb-8 md:mb-12 bg-white rounded-2xl shadow-sm border border-gray-100 p-3 sm:p-4 scroll-reveal">
                        <form method="GET" action="{{ route('publikasi.index') }}" class="flex flex-col gap-3">
                            <div class="relative w-full mb-4 md:mb-2">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" placeholder="Cari publikasi desa..."
                                    value="{{ request('search') }}"
                                    class="w-full pl-12 pr-4 py-4 md:text-lg border-2 border-gray-300 bg-white rounded-xl focus:ring-0 focus:border-primary-500 transition-colors text-gray-900 font-semibold placeholder-gray-500"
                                    autocomplete="off">
                            </div>

                            {{-- Bottom Row: Filters --}}
                            <div class="flex flex-col md:flex-row md:items-center gap-4">
                                <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <!-- Tahun -->
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <select name="tahun"
                                            class="w-full pl-10 pr-8 py-3 md:py-2.5 border-2 border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-900 focus:ring-primary-500 focus:border-primary-500 appearance-none">
                                            <option value="">Semua Tahun</option>
                                            @foreach($availableYears as $year)
                                                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                                    Tahun {{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Kategori -->
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </div>
                                        <select name="kategori"
                                            class="w-full pl-10 pr-8 py-3 md:py-2.5 border-2 border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-900 focus:ring-primary-500 focus:border-primary-500 appearance-none">
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat }}" {{ request('kategori') == $cat || (!request('kategori') && $cat == 'APBDes') ? 'selected' : '' }}>{{ $cat }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Urutkan -->
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                                            </svg>
                                        </div>
                                        <select name="urutkan"
                                            class="w-full pl-10 pr-8 py-3 md:py-2.5 border-2 border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-900 focus:ring-primary-500 focus:border-primary-500 appearance-none">
                                            <option value="terbaru" {{ request('urutkan', 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                            <option value="terlama" {{ request('urutkan') === 'terlama' ? 'selected' : '' }}>
                                                Terlama</option>
                                            <option value="terpopuler" {{ request('urutkan') === 'terpopuler' ? 'selected' : '' }}>Terpopuler</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex items-center gap-2 mt-2 md:mt-0">
                                    @if(request()->anyFilled(['search', 'tahun', 'kategori', 'urutkan']))
                                        <a href="{{ route('publikasi.index') }}"
                                            class="px-5 py-3 md:py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors inline-flex items-center">
                                            Reset
                                        </a>
                                    @endif
                                    <button type="submit"
                                        class="px-6 py-3 md:py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-all shadow-md shadow-primary-500/20 active:scale-95 inline-flex items-center justify-center w-full md:w-auto">
                                        Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Documents Grid -->
                    <div class="space-y-3 sm:space-y-4">
                        @forelse($publikasi as $item)
                            <div
                                class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                                <div class="flex flex-col sm:flex-row">
                                    <!-- Thumbnail -->
                                    <div class="sm:w-40 md:w-48 shrink-0">
                                        @if($item->thumbnail)
                                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->judul }}"
                                                class="w-full h-40 sm:h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-40 sm:h-full bg-linear-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                                                <i class="fas fa-file-pdf text-4xl sm:text-5xl md:text-6xl text-primary-600"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="p-4 sm:p-6 flex-1">
                                        <div class="flex flex-wrap gap-1.5 sm:gap-2 mb-2 sm:mb-3">
                                            <span
                                                class="px-2 sm:px-3 py-1 bg-primary-100 text-primary-800 text-xs sm:text-sm font-semibold rounded-full">
                                                {{ $item->kategori }}
                                            </span>
                                            <span
                                                class="px-2 sm:px-3 py-1 bg-gray-100 text-gray-800 text-xs sm:text-sm font-semibold rounded-full">
                                                Tahun {{ $item->tahun }}
                                            </span>
                                        </div>

                                        <h3
                                            class="text-base sm:text-lg md:text-xl font-bold text-gray-800 mb-2 hover:text-primary-600 transition">
                                            <a href="{{ route('publikasi.show', $item->id) }}">{{ $item->judul }}</a>
                                        </h3>

                                        @if($item->deskripsi)
                                            <p class="text-gray-600 mb-3 sm:mb-4 line-clamp-2 text-sm sm:text-base">
                                                {{ $item->deskripsi }}</p>
                                        @endif

                                        <div
                                            class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                            <div class="flex items-center text-xs sm:text-sm text-gray-500">
                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ \Carbon\Carbon::parse($item->tanggal_publikasi)->format('d M Y') }}
                                                <span class="mx-1.5 sm:mx-2">•</span>
                                                {{-- <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                {{ $item->jumlah_download }} unduhan --}}
                                            </div>

                                            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                                                <a href="{{ route('publikasi.show', $item->id) }}"
                                                    class="flex-1 sm:flex-none px-3 sm:px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-xs sm:text-sm font-semibold transition duration-200 text-center">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Lihat
                                                </a>
                                                {{-- <a href="{{ route('publikasi.download', $item->id) }}"
                                                    class="flex-1 sm:flex-none px-3 sm:px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs sm:text-sm font-semibold transition duration-200 text-center">
                                                    <i class="fas fa-download mr-1"></i>
                                                    Unduh
                                                </a> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 md:p-12 text-center">
                                <i class="fas fa-folder-open text-4xl sm:text-5xl md:text-6xl text-gray-300 mb-3 sm:mb-4"></i>
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">Tidak Ada Publikasi</h3>
                                <p class="text-gray-500 text-sm sm:text-base">Belum ada dokumen {{ $kategori }} yang
                                    dipublikasikan.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($publikasi->hasPages())
                        <div class="mt-8">
                            {{ $publikasi->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:w-1/4">
                    <!-- Publikasi Lainnya -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-0 lg:mt-0">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-file-alt mr-2 text-primary-600"></i>
                            Publikasi Lainnya
                        </h3>
                        <div class="space-y-4">
                            @forelse($sidebarPublikasi as $item)
                                <a href="{{ route('publikasi.show', $item->id) }}" class="block group">
                                    <div class="flex gap-3">
                                        <div class="w-16 h-16 flex-shrink-0 rounded-xl overflow-hidden">
                                            @if($item->thumbnail)
                                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->judul }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div
                                                    class="w-full h-full bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                                                    <i class="fas fa-file-pdf text-2xl text-primary-600"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <span
                                                class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">{{ $item->kategori }}</span>
                                            <h4
                                                class="text-sm font-semibold text-gray-800 group-hover:text-primary-600 transition mt-1 line-clamp-2">
                                                {{ $item->judul }}
                                            </h4>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="far fa-calendar mr-1"></i>
                                                {{ \Carbon\Carbon::parse($item->tanggal_publikasi)->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                                @if(!$loop->last)
                                    <hr class="border-gray-200">
                                @endif
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">Tidak ada publikasi lainnya</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div
                        class="bg-primary-50 rounded-2xl shadow-sm border border-primary-100 p-6 mt-6 hover:shadow-md transition-all group">
                        <div class="flex items-start gap-4">
                            <div
                                class="bg-white p-3 rounded-xl shadow-sm text-primary-600 shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Panduan Unduh</h3>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Dokumen arsip dapat diakses gratis. Klik tombol <span
                                        class="font-semibold text-primary-600">"Lihat"</span> untuk mereview atau
                                    melanjutkan mengunduh dokumen publikasi asli ke perangkat Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
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