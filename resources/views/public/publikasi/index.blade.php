{{--
    PUBLIC PUBLIKASI INDEX
    
    Halaman list dokumen publikasi desa (APBDes, RPJMDes, RKPDes)
    
    FEATURES:
    - Hero section dengan gradient
    - Kategori filter dropdown (APBDes/RPJMDes/RKPDes/Lainnya)
    - Tahun filter buttons (clickable years)
    - Sort options (Terbaru/Terlama/Terpopuler)
    - Document list dengan details
    - Download counter per dokumen
    - Sidebar dengan recent downloads
    - Pagination (10 items per page)
    
    FILTER SYSTEM:
    - Kategori: Main filter, changes document type
    - Tahun: Filter by publication year
    - Urutkan: Sort by date or popularity
    - All filters persist in URL params
    
    DOCUMENT CARD:
    - PDF icon atau kategori badge
    - Judul dokumen (bold)
    - Kategori & tahun badges
    - Deskripsi excerpt (jika ada)
    - Tanggal terbit
    - Download counter dengan icon
    - File size info
    - "Lihat Detail" button
    - Direct download link
    
    SIDEBAR (Desktop):
    - "Dokumen Terbaru" section
    - Recent 5 downloads
    - Mini cards dengan:
      * Judul (truncated)
      * Kategori badge
      * Download link
    
    KATEGORI:
    - APBDes: Anggaran Pendapatan Belanja Desa
    - RPJMDes: Rencana Pembangunan Jangka Menengah
    - RKPDes: Rencana Kerja Pemerintah Desa
    - Lainnya: Dokumen lain-lain
    
    TAHUN FILTER:
    - Dynamic year list dari available data
    - "Semua Tahun" button
    - Active state styling
    - URL parameter based
    
    EMPTY STATE:
    - Friendly message
    - Illustration icon
    - "Tidak ada dokumen" text
    - Suggestion untuk filter lain
    
    RESPONSIVE:
    - Mobile: Full-width cards, no sidebar
    - Tablet: 2 columns untuk some sections
    - Desktop: Main + sidebar layout (3/4 + 1/4)
    
    SEO:
    - Dynamic title: Publikasi - {kategori}
    - Meta description per kategori
    - Structured data (Document schema)
    
    DATA:
    $publikasi: Paginated collection
    $kategori: Current kategori filter
    $categories: Available categories
    $availableYears: Years dengan documents
    $recentDownloads: 5 latest (sidebar)
    
    REQUEST PARAMS:
    - kategori: string (default 'Semua')
    - tahun: integer (optional)
    - urutkan: string (terbaru/terlama/terpopuler)
    
    Route: /publikasi
    Controller: PublikasiController@index
--}}
@extends('public.layouts.app')

@section('title', 'Publikasi - ' . $kategori)

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-primary-600 to-primary-800 py-8 sm:py-12 md:py-16">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 scroll-reveal">
        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-3 sm:mb-4">Publikasi Desa</h1>
        <p class="text-base sm:text-lg md:text-xl text-primary-100">Dokumen {{ $kategori }} Desa Warurejo</p>
    </div>
</section>

<!-- Main Content -->
<section class="py-6 sm:py-8 md:py-12 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-6 sm:gap-8">
            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Filter Section -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-4 sm:mb-6 scroll-reveal">
                    <div class="flex flex-col gap-4">
                        <!-- Kategori Dropdown -->
                        <div class="flex-1">
                            <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-2">Pilih Kategori</label>
                            <select id="kategori" 
                                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg text-sm sm:text-base focus:ring-2 focus:ring-primary-600 focus:border-transparent"
                                    onchange="window.location.href = '{{ route('publikasi.index') }}?kategori=' + this.value + '{{ request('tahun') ? '&tahun=' . request('tahun') : '' }}' + '{{ request('urutkan') ? '&urutkan=' . request('urutkan') : '' }}'">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ $kategori === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Urutkan Dropdown -->
                        <div class="flex-1">
                            <label for="urutkan" class="block text-sm font-semibold text-gray-700 mb-2">Urutkan</label>
                            <select id="urutkan" 
                                    class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg text-sm sm:text-base focus:ring-2 focus:ring-primary-600 focus:border-transparent"
                                    onchange="window.location.href = '{{ route('publikasi.index') }}?kategori={{ $kategori }}' + '{{ request('tahun') ? '&tahun=' . request('tahun') : '' }}' + '&urutkan=' + this.value">
                                <option value="terbaru" {{ request('urutkan', 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="terlama" {{ request('urutkan') === 'terlama' ? 'selected' : '' }}>Terlama</option>
                                <option value="terpopuler" {{ request('urutkan') === 'terpopuler' ? 'selected' : '' }}>Terpopuler</option>
                            </select>
                        </div>

                        <!-- Tahun Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Tahun</label>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('publikasi.index', ['kategori' => $kategori, 'urutkan' => request('urutkan', 'terbaru')]) }}" 
                                   class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg font-semibold text-xs sm:text-sm transition {{ !request('tahun') ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                    Semua Tahun
                                </a>
                                @foreach($availableYears as $year)
                                    <a href="{{ route('publikasi.index', ['kategori' => $kategori, 'tahun' => $year, 'urutkan' => request('urutkan', 'terbaru')]) }}" 
                                       class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg font-semibold text-xs sm:text-sm transition {{ request('tahun') == $year ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                        {{ $year }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents Grid -->
                <div class="space-y-3 sm:space-y-4">
                    @forelse($publikasi as $item)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="flex flex-col sm:flex-row">
                                <!-- Thumbnail -->
                                <div class="sm:w-40 md:w-48 shrink-0">
                                    @if($item->thumbnail)
                                        <img src="{{ $item->thumbnail_url }}" 
                                             alt="{{ $item->judul }}" 
                                             class="w-full h-40 sm:h-full object-cover">
                                    @else
                                        <div class="w-full h-40 sm:h-full bg-linear-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                                            <i class="fas fa-file-pdf text-4xl sm:text-5xl md:text-6xl text-primary-600"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="p-4 sm:p-6 flex-1">
                                    <div class="flex flex-wrap gap-1.5 sm:gap-2 mb-2 sm:mb-3">
                                        <span class="px-2 sm:px-3 py-1 bg-primary-100 text-primary-800 text-xs sm:text-sm font-semibold rounded-full">
                                            {{ $item->kategori }}
                                        </span>
                                        <span class="px-2 sm:px-3 py-1 bg-gray-100 text-gray-800 text-xs sm:text-sm font-semibold rounded-full">
                                            Tahun {{ $item->tahun }}
                                        </span>
                                    </div>

                                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-800 mb-2 hover:text-primary-600 transition">
                                        <a href="{{ route('publikasi.show', $item->id) }}">{{ $item->judul }}</a>
                                    </h3>

                                    @if($item->deskripsi)
                                        <p class="text-gray-600 mb-3 sm:mb-4 line-clamp-2 text-sm sm:text-base">{{ $item->deskripsi }}</p>
                                    @endif

                                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                        <div class="flex items-center text-xs sm:text-sm text-gray-500">
                                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($item->tanggal_publikasi)->format('d M Y') }}
                                            <span class="mx-1.5 sm:mx-2">•</span>
                                            {{-- <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
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
                        <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 md:p-12 text-center">
                            <i class="fas fa-folder-open text-4xl sm:text-5xl md:text-6xl text-gray-300 mb-3 sm:mb-4"></i>
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">Tidak Ada Publikasi</h3>
                            <p class="text-gray-500 text-sm sm:text-base">Belum ada dokumen {{ $kategori }} yang dipublikasikan.</p>
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
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-file-alt mr-2 text-primary-600"></i>
                        Publikasi Lainnya
                    </h3>
                    <div class="space-y-4">
                        @forelse($sidebarPublikasi as $item)
                            <a href="{{ route('publikasi.show', $item->id) }}" 
                               class="block group">
                                <div class="flex gap-3">
                                    <div class="w-16 h-16 flex-shrink-0 bg-gradient-to-br from-primary-100 to-primary-200 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file-pdf text-2xl text-primary-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">{{ $item->kategori }}</span>
                                        <h4 class="text-sm font-semibold text-gray-800 group-hover:text-primary-600 transition mt-1 line-clamp-2">
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
                <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-lg shadow-md p-6 mt-6 text-white">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-info-circle text-3xl mr-3"></i>
                        <h3 class="text-lg font-bold">Informasi</h3>
                    </div>
                    <p class="text-sm text-primary-100">
                        Semua dokumen publikasi desa dapat diakses secara gratis. Klik "Lihat" untuk melihat detail dan preview dokumen. Untuk mengunduh, buka halaman detail dan klik tombol unduh jika tersedia.
                    </p>
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
