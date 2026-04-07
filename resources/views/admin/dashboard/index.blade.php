{{--
    ADMIN DASHBOARD
    
    Halaman utama admin panel dengan statistik dan overview
    
    SECTIONS:
    1. Welcome Message: Greeting dengan nama admin
    2. Statistics Cards: Total berita/potensi/galeri/publikasi/struktur
    3. Visitor Charts: Line chart (daily) + Pie chart (sources)
    4. Recent Activity: Latest berita & potensi
    
    DATA DARI CONTROLLER:
    - $totalBerita, $beritaPublished, $beritaDraft
    - $totalPotensi, $totalGaleri, $totalPublikasi, $totalStruktur
    - $visitorStatsJson: JSON untuk Chart.js
    - $latestBerita, $latestPotensi: Collection
    
    CHARTS:
    - Chart.js library (loaded via CDN)
    - visitorChart: Line chart (7 hari terakhir)
    - sourceChart: Doughnut chart (Direct/Referral/Social)
    
    FEATURES:
    - Real-time statistics counts
    - Publish/Draft breakdown
    - Responsive grid layout (1/2/5 columns)
    - Quick links ke management pages
    
    Route: /admin/dashboard
    Controller: App\Http\Controllers\Admin\DashboardController@index
--}}
@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Message -->
    <div class="bg-linear-to-r from-primary-600 to-primary-800 rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ auth()->guard('admin')->user()->name }}! 👋</h1>
        <p class="text-primary-100">Kelola website Desa Warurejo dari dashboard ini</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Total Berita -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Berita</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalBerita }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="text-green-600 font-medium">{{ $beritaPublished }}</span> Published • 
                        <span class="text-yellow-600 font-medium">{{ $beritaDraft }}</span> Draft
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Potensi -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Potensi</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalPotensi }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Potensi Desa</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Galeri -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Galeri</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalGaleri }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Foto</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Publikasi -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Publikasi</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalPublikasi }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Dokumen</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Pengunjung -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Pengunjung</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalPengunjung) }}</h3>
                    <p class="text-xs text-gray-500 mt-1">All Time</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Visitor Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Hari Ini -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm text-gray-600">Hari Ini</p>
                @if($pertumbuhanHariIni >= 0)
                    <span class="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded">
                        +{{ number_format($pertumbuhanHariIni, 1) }}%
                    </span>
                @else
                    <span class="text-xs text-red-600 font-medium bg-red-50 px-2 py-1 rounded">
                        {{ number_format($pertumbuhanHariIni, 1) }}%
                    </span>
                @endif
            </div>
            <h3 class="text-2xl font-bold text-gray-800">{{ number_format($pengunjungHariIni) }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($pageViewsHariIni) }} page views</p>
        </div>

        <!-- Minggu Ini -->
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-2">Minggu Ini</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ number_format($pengunjungMingguIni) }}</h3>
            <p class="text-xs text-gray-500 mt-1">7 hari terakhir</p>
        </div>

        <!-- Bulan Ini -->
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-2">Bulan Ini</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ number_format($pengunjungBulanIni) }}</h3>
            <p class="text-xs text-gray-500 mt-1">30 hari terakhir</p>
        </div>

        <!-- Rata-rata -->
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-2">Rata-rata Harian</p>
            <h3 class="text-2xl font-bold text-gray-800">
                {{ $pengunjungBulanIni > 0 ? number_format($pengunjungBulanIni / 30, 0) : 0 }}
            </h3>
            <p class="text-xs text-gray-500 mt-1">Per hari (30 hari)</p>
        </div>
    </div>

    <!-- Chart & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Visitor Chart -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Statistik Pengunjung</h2>
                    <p class="text-sm text-gray-500 mt-1">Data sepanjang tahun <span id="visitorYearLabel">{{ $currentYear }}</span></p>
                </div>
                
                <!-- Year Picker with Navigation -->
                <div class="flex items-center gap-2">
                    <button type="button" id="visitorYearPrev" class="p-2 hover:bg-gray-100 rounded transition" title="Tahun Sebelumnya">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <input type="number" id="yearFilter" value="{{ $currentYear }}" min="2000" max="2100"
                           class="w-24 text-center form-input rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm font-semibold">
                    <button type="button" id="visitorYearNext" class="p-2 hover:bg-gray-100 rounded transition" title="Tahun Berikutnya">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <button type="button" id="visitorYearReset" class="ml-2 px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded-lg transition" title="Kembali ke Tahun Ini">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Tahun Ini
                    </button>
                </div>
            </div>

            <!-- All Time Stats Summary -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 p-4 bg-linear-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                <div class="text-center">
                    <p class="text-xs text-gray-600 mb-1">Total Unique Visitors</p>
                    <p class="text-lg font-bold text-blue-600">{{ number_format($allTimeStats['total_unique_visitors']) }}</p>
                    <p class="text-xs text-gray-500">All Time</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-600 mb-1">Total Page Views</p>
                    <p class="text-lg font-bold text-indigo-600">{{ number_format($allTimeStats['total_page_views']) }}</p>
                    <p class="text-xs text-gray-500">All Time</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-600 mb-1">Hari Aktif</p>
                    <p class="text-lg font-bold text-purple-600">{{ number_format($allTimeStats['days_active']) }}</p>
                    <p class="text-xs text-gray-500">Hari</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-600 mb-1">Sejak</p>
                    <p class="text-sm font-bold text-gray-700">{{ $allTimeStats['first_visit_date'] }}</p>
                    <p class="text-xs text-gray-500">First Visit</p>
                </div>
            </div>

            <!-- Loading Overlay -->
            <div id="chartLoading" style="display:none;" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center rounded-lg">
                <div class="text-center">
                    <svg class="animate-spin h-10 w-10 text-primary-600 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-sm text-gray-600">Loading data...</p>
                </div>
            </div>

            <!-- Chart Canvas -->
            <div class="relative">
                <canvas id="visitorChart"></canvas>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('admin.berita.create') }}" 
                   class="flex items-center justify-between p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 group-hover:text-blue-600">Tambah Berita</p>
                            <p class="text-xs text-gray-500">Buat berita baru</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

<a href="{{ route('admin.potensi.create') }}" 
   class="flex items-center justify-between p-4 bg-green-50 hover:bg-green-100 rounded-lg transition group">
    <div class="flex items-center">
        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <div>
            <p class="font-semibold text-gray-800 group-hover:text-green-600">Tambah Potensi</p>
            <p class="text-xs text-gray-500">Buat potensi baru</p>
        </div>
    </div>
    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
</a>


                <a href="{{ route('admin.galeri.create') }}" class="block">
                    <div class="flex items-center justify-between p-4 bg-purple-50 hover:bg-purple-100 rounded-lg cursor-pointer transition group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 group-hover:text-purple-600">Upload Galeri</p>
                                <p class="text-xs text-gray-500">Tambah foto ke galeri desa</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('admin.publikasi.create') }}" class="block">
                    <div class="flex items-center justify-between p-4 bg-red-50 hover:bg-red-100 rounded-lg cursor-pointer transition group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 group-hover:text-red-600">Upload Publikasi</p>
                                <p class="text-xs text-gray-500">Tambah dokumen publikasi</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('admin.struktur-organisasi.index') }}" class="block">
                    <div class="flex items-center justify-between p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg cursor-pointer transition group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 group-hover:text-yellow-600">Kelola Struktur Organisasi</p>
                                <p class="text-xs text-gray-500">Manajemen anggota struktur</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Content Statistics Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Statistik Konten</h2>
                <p class="text-sm text-gray-500 mt-1">Data konten tahun <span id="contentYearLabel">{{ $currentContentYear }}</span></p>
            </div>
            
            <!-- Year Picker with Navigation -->
            <div class="flex items-center gap-2">
                <button type="button" id="contentYearPrev" class="p-2 hover:bg-gray-100 rounded transition" title="Tahun Sebelumnya">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <input type="number" id="contentYearFilter" value="{{ $currentContentYear }}" min="2000" max="2100"
                       class="w-24 text-center form-input rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm font-semibold">
                <button type="button" id="contentYearNext" class="p-2 hover:bg-gray-100 rounded transition" title="Tahun Berikutnya">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <button type="button" id="contentYearReset" class="ml-2 px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded-lg transition" title="Kembali ke Tahun Ini">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Tahun Ini
                </button>
            </div>
        </div>
        
        <div class="relative">
            <canvas id="contentChart"></canvas>
            
            <!-- Loading Overlay -->
            <div id="contentChartLoading" class="absolute inset-0 bg-white bg-opacity-90 items-center justify-center" style="display:none;">
                <div class="text-center">
                    <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-600 text-sm">Memuat data...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Berita Terbaru</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Berita</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Lihat</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentBerita as $berita)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="shrink-0 h-10 w-10">
                                    <img
                                        class="h-10 w-10 rounded object-cover"
                                        src="{{ $berita->gambar_utama_url }}"
                                        alt=""
                                    >
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ Str::limit($berita->judul, 50) }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ Str::limit($berita->excerpt, 60) }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($berita->status === 'published')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Published
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Draft
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $berita->created_at->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <i class="far fa-eye mr-1"></i>
                            {{ number_format($berita->views) }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.berita.edit', $berita->id) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-red-600 hover:text-red-900 transition-colors delete-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Belum ada berita
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 bg-gray-50 border-t border-gray-200">
        <a href="{{ route('admin.berita.index') }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
            Lihat Semua Berita →
        </a>
    </div>
</div>

    <!-- Potensi Terbaru -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Potensi Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Potensi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Lihat</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentPotensi as $potensi)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="shrink-0 h-10 w-10">
                                        @if($potensi->gambar)
                                            <img class="h-10 w-10 rounded object-cover" src="{{ asset('storage/' . $potensi->gambar) }}" alt="">
                                        @else
                                            <div class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($potensi->nama, 50) }}</div>
                                        <div class="text-xs text-gray-500">{{ Str::limit(strip_tags($potensi->deskripsi), 60) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($potensi->kategori) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($potensi->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $potensi->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="far fa-eye mr-1"></i>
                                {{ number_format($potensi->views) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.potensi.edit', $potensi->id) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.potensi.destroy', $potensi->id) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-600 hover:text-red-900 transition-colors delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Belum ada potensi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            <a href="{{ route('admin.potensi.index') }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                Lihat Semua Potensi →
            </a>
        </div>
    </div>

    <!-- Galeri Terbaru -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Galeri Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Galeri</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Lihat</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentGaleri as $galeri)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="shrink-0 h-10 w-10">
                                        @if($galeri->gambar)
                                                <img src="{{ asset('storage/' . $galeri->gambar) }}" class="h-10 w-10 rounded object-cover">
                                            @elseif($galeri->images->first())
                                                <img src="{{ asset('storage/' . $galeri->images->first()->image_path) }}" class="h-10 w-10 rounded object-cover">
                                            @else
                                            <div class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($galeri->judul, 50) }}</div>
                                        <div class="text-xs text-gray-500">{{ $galeri->deskripsi ? Str::limit($galeri->deskripsi, 60) : 'Tidak ada deskripsi' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ ucfirst($galeri->kategori) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($galeri->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $galeri->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="far fa-eye mr-1"></i>
                                {{ number_format($galeri->views) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.galeri.edit', $galeri->id) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.galeri.destroy', $galeri->id) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-600 hover:text-red-900 transition-colors delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Belum ada galeri
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            <a href="{{ route('admin.galeri.index') }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                Lihat Semua Galeri →
            </a>
        </div>
    </div>

    <!-- Publikasi Terbaru -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Publikasi Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Publikasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Lihat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Unduh</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentPublikasi as $publikasi)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="shrink-0 h-10 w-10">
                                        @if($publikasi->thumbnail)
                                            <img class="h-10 w-10 rounded object-cover" src="{{ asset('storage/' . $publikasi->thumbnail) }}" alt="">
                                        @else
                                            <img class="h-10 w-10 rounded object-cover border border-gray-200" src="{{ asset('images/pdf-preview.svg') }}" alt="PDF">
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($publikasi->judul, 50) }}</div>
                                        <div class="text-xs text-gray-500">{{ $publikasi->deskripsi ? Str::limit($publikasi->deskripsi, 60) : 'Tidak ada deskripsi' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $publikasi->kategori === 'APBDes' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $publikasi->kategori === 'RPJMDes' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $publikasi->kategori === 'RKPDes' ? 'bg-purple-100 text-purple-800' : '' }}">
                                    {{ $publikasi->kategori }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($publikasi->status === 'published')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Published
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $publikasi->tanggal_publikasi ? \Carbon\Carbon::parse($publikasi->tanggal_publikasi)->format('d M Y') : $publikasi->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="far fa-eye mr-1"></i>
                                {{ number_format($publikasi->views) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="fas fa-download mr-1"></i>
                                {{ number_format($publikasi->jumlah_download) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.publikasi.edit', $publikasi->id) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.publikasi.destroy', $publikasi->id) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-600 hover:text-red-900 transition-colors delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Belum ada publikasi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            <a href="{{ route('admin.publikasi.index') }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                Lihat Semua Publikasi →
            </a>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Visitor Statistics Chart - with year filter
    let visitorChart = null;
    const visitorCtx = document.getElementById('visitorChart').getContext('2d');
    
    // Initialize chart with initial data
    function initVisitorChart(chartData) {
        if (visitorChart) {
            visitorChart.destroy();
        }
        
        visitorChart = new Chart(visitorCtx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Unique Visitors',
                        data: chartData.visitors,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Page Views',
                        data: chartData.pageViews,
                        borderColor: 'rgb(249, 115, 22)',
                        backgroundColor: 'rgba(249, 115, 22, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgb(249, 115, 22)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 13,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed.y.toLocaleString();
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Initial chart load
    const initialChartData = {
        labels: {!! json_encode($visitorChartData['labels']) !!},
        visitors: {!! json_encode($visitorChartData['visitors']) !!},
        pageViews: {!! json_encode($visitorChartData['pageViews']) !!}
    };
    initVisitorChart(initialChartData);
    
    // Visitor Year Filter - function to load data
    function loadVisitorChartByYear(year) {
        const loadingOverlay = document.getElementById('chartLoading');
        loadingOverlay.style.display = 'flex';
        document.getElementById('visitorYearLabel').textContent = year;
        
        fetch(`/admin/dashboard/visitor-chart?year=${year}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                initVisitorChart(data.data);
            }
        })
        .catch(error => {
            console.error('Error fetching chart data:', error);
            alert('Gagal memuat data grafik. Silakan coba lagi.');
        })
        .finally(() => {
            loadingOverlay.style.display = 'none';
        });
    }
    
    // Year filter input change event
    document.getElementById('yearFilter').addEventListener('change', function() {
        loadVisitorChartByYear(parseInt(this.value));
    });
    
    // Previous year button
    document.getElementById('visitorYearPrev').addEventListener('click', function() {
        const yearInput = document.getElementById('yearFilter');
        const newYear = parseInt(yearInput.value) - 1;
        yearInput.value = newYear;
        loadVisitorChartByYear(newYear);
    });
    
    // Next year button
    document.getElementById('visitorYearNext').addEventListener('click', function() {
        const yearInput = document.getElementById('yearFilter');
        const newYear = parseInt(yearInput.value) + 1;
        yearInput.value = newYear;
        loadVisitorChartByYear(newYear);
    });
    
    // Reset to current year button
    document.getElementById('visitorYearReset').addEventListener('click', function() {
        const currentYear = {{ Carbon\Carbon::now()->year }};
        document.getElementById('yearFilter').value = currentYear;
        loadVisitorChartByYear(currentYear);
    });

    // Content Statistics Chart - with year filter
    let contentChart = null;
    const contentCtx = document.getElementById('contentChart').getContext('2d');
    
    // Initialize content chart with initial data
    function initContentChart(chartData) {
        if (contentChart) {
            contentChart.destroy();
        }
        
        contentChart = new Chart(contentCtx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Berita',
                        data: chartData.berita,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Potensi',
                        data: chartData.potensi,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgb(34, 197, 94)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Galeri',
                        data: chartData.galeri,
                        borderColor: 'rgb(168, 85, 247)',
                        backgroundColor: 'rgba(168, 85, 247, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgb(168, 85, 247)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Publikasi',
                        data: chartData.publikasi,
                        borderColor: 'rgb(249, 115, 22)',
                        backgroundColor: 'rgba(249, 115, 22, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgb(249, 115, 22)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 13,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed.y + ' konten';
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Initial content chart load
    const initialContentChartData = {
        labels: {!! json_encode($monthlyStats['labels']) !!},
        berita: {!! json_encode($monthlyStats['berita']) !!},
        potensi: {!! json_encode($monthlyStats['potensi']) !!},
        galeri: {!! json_encode($monthlyStats['galeri']) !!},
        publikasi: {!! json_encode($monthlyStats['publikasi']) !!}
    };
    initContentChart(initialContentChartData);
    
    // Content Year Filter - function to load data
    function loadContentChartByYear(year) {
        const loadingOverlay = document.getElementById('contentChartLoading');
        loadingOverlay.style.display = 'flex';
        document.getElementById('contentYearLabel').textContent = year;
        
        fetch(`/admin/dashboard/content-chart?year=${year}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                initContentChart(data.data);
            }
        })
        .catch(error => {
            console.error('Error fetching content chart data:', error);
            alert('Gagal memuat data grafik konten. Silakan coba lagi.');
        })
        .finally(() => {
            loadingOverlay.style.display = 'none';
        });
    }
    
    // Content year filter input change event
    document.getElementById('contentYearFilter').addEventListener('change', function() {
        loadContentChartByYear(parseInt(this.value));
    });
    
    // Previous year button
    document.getElementById('contentYearPrev').addEventListener('click', function() {
        const yearInput = document.getElementById('contentYearFilter');
        const newYear = parseInt(yearInput.value) - 1;
        yearInput.value = newYear;
        loadContentChartByYear(newYear);
    });
    
    // Next year button
    document.getElementById('contentYearNext').addEventListener('click', function() {
        const yearInput = document.getElementById('contentYearFilter');
        const newYear = parseInt(yearInput.value) + 1;
        yearInput.value = newYear;
        loadContentChartByYear(newYear);
    });
    
    // Reset to current year button
    document.getElementById('contentYearReset').addEventListener('click', function() {
        const currentYear = {{ Carbon\Carbon::now()->year }};
        document.getElementById('contentYearFilter').value = currentYear;
        loadContentChartByYear(currentYear);
    });
</script>
@endsection
