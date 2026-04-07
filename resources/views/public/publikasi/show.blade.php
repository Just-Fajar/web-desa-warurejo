{{--
    PUBLIC PUBLIKASI DETAIL
    
    Halaman detail dokumen publikasi dengan preview & download
    
    FEATURES:
    - Breadcrumb navigation (Home > Publikasi > Kategori)
    - Document header dengan metadata
    - Kategori & tahun badges
    - Deskripsi lengkap
    - PDF preview embed (optional)
    - Download button (track counter)
    - Related documents (same kategori)
    - Document info sidebar
    - Social share (optional)
    
    HEADER SECTION:
    - Gradient background
    - Judul dokumen (large, bold)
    - Publication date dengan icon
    - Download counter dengan icon
    - Breadcrumb navigation
    
    MAIN CONTENT:
    1. Document Info Card:
       - Kategori badge (color coded)
       - Tahun badge
       - Deskripsi text
       - File size info
       - Upload date
    
    2. Download Button:
       - Green prominent button
       - Download icon
       - Track download increment
       - Force download file
    
    3. PDF Preview (Optional):
       - Embedded iframe
       - 800px height
       - Scrollable if needed
       - Fallback message untuk mobile
    
    SIDEBAR (Desktop):
    - File Information:
      * Format: PDF
      * Size: MB/KB
      * Pages: (optional)
      * Category
      * Year
    - Related Documents:
      * Same kategori
      * Limit 3-5 items
      * Mini cards
    
    DOWNLOAD TRACKING:
    - Increment jumlah_download counter
    - Track via PublikasiController@download
    - Force download dengan headers
    - Original filename preserved
    
    RELATED DOCUMENTS:
    - Same kategori filter
    - Exclude current document
    - Limit 3 items
    - Card layout dengan thumbnail
    
    RESPONSIVE:
    - Mobile: Full-width, stacked layout
    - Tablet: Sidebar below main
    - Desktop: Main + sidebar (3/4 + 1/4)
    
    SEO OPTIMIZATION:
    - Dynamic title: {judul}
    - Meta description dari deskripsi
    - Open Graph tags
    - Structured data (Document schema)
    
    KATEGORI COLORS:
    - APBDes: primary-100/800
    - RPJMDes: green-100/800
    - RKPDes: blue-100/800
    - Lainnya: gray-100/800
    
    DATA:
    $publikasi: Publikasi model dengan:
    - judul, kategori, tahun
    - deskripsi, file_path
    - tanggal_publikasi, jumlah_download
    - file_url accessor
    
    ROUTES:
    - GET /publikasi/{id}: Show detail
    - GET /publikasi/{id}/download: Download file
    
    Controller: PublikasiController@show, download
--}}
@extends('public.layouts.app')

@section('title', $publikasi->judul)

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-primary-600 to-primary-800 py-16">
    <div class="container mx-auto px-4">
        <nav class="text-sm mb-4">
            <ol class="flex items-center space-x-2 text-primary-100">
                <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                <li><span>/</span></li>
                <li><a href="{{ route('publikasi.index') }}" class="hover:text-white">Publikasi</a></li>
                <li><span>/</span></li>
                <li class="text-white">{{ $publikasi->kategori }}</li>
            </ol>
        </nav>
        <h1 class="text-4xl font-bold text-white mb-2">{{ $publikasi->judul }}</h1>
        <div class="flex items-center text-primary-100 text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            {{ $publikasi->tanggal_publikasi->format('d F Y') }}
            {{-- <span class="mx-3">•</span>
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            {{ $publikasi->jumlah_download }} unduhan --}}
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Document Info -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 bg-primary-100 text-primary-800 text-sm font-semibold rounded-full">
                            {{ $publikasi->kategori }}
                        </span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full">
                            Tahun {{ $publikasi->tahun }}
                        </span>
                    </div>

                    @if($publikasi->deskripsi)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $publikasi->deskripsi }}</p>
                        </div>
                    @endif

                    <!-- Download Button (di-comment, user public tidak boleh download) -->
                    {{-- <a href="{{ route('publikasi.download', $publikasi->id) }}"
                       class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Unduh Dokumen (PDF)
                    </a> --}}
                </div>

                <!-- PDF Preview -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Preview Dokumen</h3>
                    </div>
                    <div class="p-4">
                        <iframe src="{{ $publikasi->file_url }}" 
                                class="w-full h-[800px] border-0 rounded"
                                title="{{ $publikasi->judul }}">
                        </iframe>
                        <p class="text-sm text-gray-500 text-center mt-2">
                            {{-- Tidak bisa melihat preview? 
                            <a href="{{ route('publikasi.download', $publikasi->id) }}" class="text-primary-600 hover:underline">
                                Unduh dokumen
                            </a> --}}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:w-1/4">
                <!-- Related Documents -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Dokumen Terkait</h3>
                    
                    @if($relatedPublikasi->count() > 0)
                        <div class="space-y-4">
                            @foreach($relatedPublikasi as $doc)
                                <a href="{{ route('publikasi.show', $doc->id) }}" 
                                   class="flex gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 line-clamp-2 group-hover:text-primary-600">
                                            {{ $doc->judul }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs text-gray-500">{{ $doc->tahun }}</span>
                                            <span class="text-xs text-gray-400">•</span>
                                            {{-- <span class="text-xs text-gray-500">{{ $doc->jumlah_download }} unduhan</span> --}}
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <a href="{{ route('publikasi.index', ['kategori' => $publikasi->kategori]) }}"
                           class="block mt-4 text-center text-sm text-primary-600 hover:text-primary-700 font-medium">
                            Lihat Semua {{ $publikasi->kategori }} →
                        </a>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada dokumen terkait</p>
                    @endif
                </div>

                <!-- Back Button -->
                <a href="{{ route('publikasi.index', ['kategori' => $publikasi->kategori]) }}"
                   class="block w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-center font-medium rounded-lg transition-colors">
                    ← Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
