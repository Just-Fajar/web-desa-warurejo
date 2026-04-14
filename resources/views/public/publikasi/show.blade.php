@extends('public.layouts.app')

@section('title', $publikasi->judul)

@section('content')
<!-- Header Section -->
<section class="pt-32 pb-8 bg-gray-50 border-b border-gray-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto scroll-reveal">
            <nav class="text-sm mb-6">
                <ol class="flex items-center space-x-2 text-gray-500 font-medium">
                    <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Beranda</a></li>
                    <li><svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                    <li><a href="{{ route('publikasi.index') }}" class="hover:text-primary-600 transition-colors">Publikasi</a></li>
                    <li><svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                    <li class="text-primary-600 font-semibold">{{ $publikasi->kategori }}</li>
                </ol>
            </nav>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 tracking-tight">{{ $publikasi->judul }}</h1>
            <div class="flex flex-wrap items-center gap-4 text-gray-500 text-sm font-medium">
                <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-lg border border-gray-100 shadow-sm">
                    <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $publikasi->tanggal_publikasi->format('d F Y') }}
                </div>
            </div>
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
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
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
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
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
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
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
                   class="block w-full px-4 py-3 bg-white border border-gray-100 shadow-sm hover:bg-gray-50 text-gray-700 text-center font-bold rounded-2xl transition-all hover:-translate-y-1">
                    ← Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
