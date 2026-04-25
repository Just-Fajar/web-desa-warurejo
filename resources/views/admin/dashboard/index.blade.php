@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-8">
        <style>
            @keyframes wave {
                0%, 100% { transform: rotate(0deg); }
                25% { transform: rotate(-15deg); }
                75% { transform: rotate(15deg); }
            }
            .animate-wave { animation: wave 1.5s infinite; transform-origin: 70% 70%; }
            .stat-card { @apply bg-white rounded-2xl border border-gray-100 p-6 flex flex-col justify-between transition-all duration-300 hover:shadow-lg hover:-translate-y-1 relative overflow-hidden; }
            .stat-card::after { content: ''; position: absolute; right: -20px; top: -20px; width: 100px; height: 100px; border-radius: 50%; opacity: 0.03; transition: all 0.3s; }
            .stat-card:hover::after { transform: scale(1.5); }
            .stat-card.blue::after { background-color: #3b82f6; }
            .stat-card.green::after { background-color: #10b981; }
            .stat-card.purple::after { background-color: #8b5cf6; }
            .stat-card.red::after { background-color: #ef4444; }
            .stat-card.yellow::after { background-color: #f59e0b; }
        </style>

        <!-- Welcome Message -->
        <div class="relative overflow-hidden bg-gradient-to-r from-emerald-600 to-teal-800 rounded-3xl shadow-xl p-8 sm:p-10 text-white">
            <div class="absolute top-[-20%] right-[-5%] w-64 h-64 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-[-20%] right-[15%] w-48 h-48 bg-emerald-300 opacity-20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold mb-2 tracking-tight">Selamat Datang, {{ auth()->guard('admin')->user()->name }}! <span class="inline-block hover:animate-wave cursor-default">👋</span></h1>
                    <p class="text-emerald-50/90 text-lg font-light">Kelola website, pantau statistik, dan berikan layanan terbaik untuk Desa Warurejo.</p>
                </div>
                <div class="hidden md:block">
                    <span class="bg-white/10 backdrop-blur-md px-5 py-2.5 rounded-xl border border-white/20 text-sm font-medium flex items-center gap-2 shadow-inner">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ now()->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">
            <!-- Total Berita -->
            <div class="stat-card blue group shadow-sm">
                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="w-12 h-12 bg-blue-50/80 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 border border-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 bg-gray-50 px-2 py-1 rounded-md">Berita</span>
                </div>
                <div class="relative z-10">
                    <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalBerita }}</h3>
                    <div class="flex items-center gap-2 mt-3">
                        <span class="text-[10px] sm:text-xs font-semibold text-green-700 bg-green-100 px-2 py-1 rounded-md flex-1 text-center">{{ $beritaPublished }} Publikasi</span>
                        <span class="text-[10px] sm:text-xs font-semibold text-yellow-700 bg-yellow-100 px-2 py-1 rounded-md flex-1 text-center">{{ $beritaDraft }} Draft</span>
                    </div>
                </div>
            </div>

            <!-- Total Potensi -->
            <div class="stat-card green group shadow-sm">
                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="w-12 h-12 bg-emerald-50/80 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 border border-emerald-100">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 bg-gray-50 px-2 py-1 rounded-md">Potensi</span>
                </div>
                <div class="relative z-10">
                    <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalPotensi }}</h3>
                    <p class="text-xs font-semibold text-gray-600 mt-3 bg-gray-50 rounded-md px-2 py-1 text-center">Potensi Desa</p>
                </div>
            </div>

            <!-- Total Galeri -->
            <div class="stat-card purple group shadow-sm">
                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="w-12 h-12 bg-purple-50/80 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 border border-purple-100">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 bg-gray-50 px-2 py-1 rounded-md">Galeri</span>
                </div>
                <div class="relative z-10">
                    <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalGaleri }}</h3>
                    <p class="text-xs font-semibold text-gray-600 mt-3 bg-gray-50 rounded-md px-2 py-1 text-center">Foto Media</p>
                </div>
            </div>

            <!-- Total Publikasi -->
            <div class="stat-card red group shadow-sm">
                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="w-12 h-12 bg-rose-50/80 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 border border-rose-100">
                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 bg-gray-50 px-2 py-1 rounded-md">Publikasi</span>
                </div>
                <div class="relative z-10">
                    <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalPublikasi }}</h3>
                    <p class="text-xs font-semibold text-gray-600 mt-3 bg-gray-50 rounded-md px-2 py-1 text-center">Dokumen</p>
                </div>
            </div>

            <!-- Total Pengunjung -->
            <div class="stat-card yellow group shadow-sm">
                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="w-12 h-12 bg-amber-50/80 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 border border-amber-100">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 bg-gray-50 px-2 py-1 rounded-md">Pengunjung</span>
                </div>
                <div class="relative z-10">
                    <h3 class="text-3xl font-extrabold text-gray-800">{{ number_format($totalPengunjung) }}</h3>
                    <p class="text-xs font-semibold text-gray-600 mt-3 bg-gray-50 rounded-md px-2 py-1 text-center">Total Pengunjung</p>
                </div>
            </div>
        </div>

        <!-- Visitor Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Hari Ini -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Hari Ini</p>
                    @if($pertumbuhanHariIni >= 0)
                        <span class="text-xs text-emerald-600 flex items-center font-bold bg-emerald-50 px-2 py-1 rounded-md">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            {{ number_format($pertumbuhanHariIni, 1) }}%
                        </span>
                    @else
                        <span class="text-xs text-rose-600 flex items-center font-bold bg-rose-50 px-2 py-1 rounded-md">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            {{ number_format(abs($pertumbuhanHariIni), 1) }}%
                        </span>
                    @endif
                </div>
                <h3 class="text-2xl font-extrabold text-gray-800">{{ number_format($pengunjungHariIni) }}</h3>
                <p class="text-xs font-medium text-gray-500 mt-1 flex items-center"> {{ number_format($pageViewsHariIni) }} Pengunjung hari ini</p>
            </div>

            <!-- Minggu Ini -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
                <p class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Minggu Ini</p>
                <h3 class="text-2xl font-extrabold text-gray-800">{{ number_format($pengunjungMingguIni) }}</h3>
                <p class="text-xs font-medium text-gray-500 mt-1">Pengunjung dalam 7 hari terakhir</p>
            </div>

            <!-- Bulan Ini -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
                <p class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Bulan Ini</p>
                <h3 class="text-2xl font-extrabold text-gray-800">{{ number_format($pengunjungBulanIni) }}</h3>
                <p class="text-xs font-medium text-gray-500 mt-1">Pengunjung dalam 30 hari terakhir</p>
            </div>

            <!-- Rata-rata -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
                <p class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Rata-rata / Hari</p>
                <h3 class="text-2xl font-extrabold text-gray-800">
                    {{ $pengunjungBulanIni > 0 ? number_format($pengunjungBulanIni / 30, 0) : 0 }}
                </h3>
                <p class="text-xs font-medium text-gray-500 mt-1">Rata-rata pengunjung dalam 30 hari</p>
            </div>
        </div>

        <!-- Chart Row 1: Visitor & Potensi -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Visitor Chart -->
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-8 relative">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 pb-4 border-b border-gray-50 gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 tracking-tight">Statistik Pengunjung</h2>
                        <p class="text-sm text-gray-500 mt-1">Statistik pengunjung tahun <span id="visitorYearLabel" class="font-semibold text-emerald-600">{{ $currentYear }}</span></p>
                    </div>

                    <!-- Year Picker with Navigation -->
                    <div class="flex items-center gap-2">
                        <button type="button" id="visitorYearPrev" class="w-9 h-9 flex items-center justify-center bg-gray-50 hover:bg-emerald-50 text-gray-500 hover:text-emerald-600 rounded-xl transition-colors border border-gray-100" title="Tahun Sebelumnya">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <input type="number" id="yearFilter" value="{{ $currentYear }}" min="2000" max="2100"
                               class="w-20 text-center form-input bg-gray-50 rounded-xl border-gray-100 shadow-none focus:border-emerald-500 focus:ring-emerald-500 focus:bg-white text-sm font-bold text-gray-700">
                        <button type="button" id="visitorYearNext" class="w-9 h-9 flex items-center justify-center bg-gray-50 hover:bg-emerald-50 text-gray-500 hover:text-emerald-600 rounded-xl transition-colors border border-gray-100" title="Tahun Berikutnya">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <button type="button" id="visitorYearReset" class="ml-1 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition-colors" title="Kembali ke Tahun Ini">
                            Tahun Ini
                        </button>
                    </div>
                </div>

                <!-- All Time Stats Summary -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100">
                        <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Pengunjung Unik</p>
                        <p class="text-xl font-bold text-blue-600">{{ number_format($allTimeStats['total_unique_visitors']) }}</p>
                    </div>
                    <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100">
                        <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Tampilan Halaman</p>
                        <p class="text-xl font-bold text-indigo-600">{{ number_format($allTimeStats['total_page_views']) }}</p>
                    </div>
                    <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100">
                        <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Hari Aktif</p>
                        <p class="text-xl font-bold text-emerald-600">{{ number_format($allTimeStats['days_active']) }}</p>
                    </div>
                    <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100">
                        <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Kunjungan Pertama</p>
                        <p class="text-sm font-bold text-slate-700 mt-1">{{ $allTimeStats['first_visit_date'] }}</p>
                    </div>
                </div>

                <!-- Loading Overlay -->
                <div id="chartLoading" style="display:none;" class="absolute inset-0 bg-white/80 backdrop-blur-sm z-20 flex items-center justify-center rounded-3xl">
                    <div class="text-center">
                        <svg class="animate-spin h-10 w-10 text-emerald-600 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm font-medium text-emerald-800">Menyegarkan data...</p>
                    </div>
                </div>

                <!-- Chart Canvas -->
                <div class="relative w-full h-[300px]">
                    <canvas id="visitorChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex flex-col justify-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 tracking-tight mb-2">Distribusi Potensi Desa</h2>
                    <p class="text-xs text-gray-500 mb-6">Persentase potensi berdasarkan kategori</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center flex-1">
                    <div class="relative h-[256px] w-full flex justify-center items-center">
                        <canvas id="potensiChart" style="z-index: 10;"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none" style="z-index: 0;">
                            <span class="text-[11px] font-semibold text-gray-500 mb-1">Total Potensi</span>
                            <span class="text-3xl font-extrabold text-gray-900">
                                @php
                                    $potensiTotalsArray = is_array($potensiTotals) ? $potensiTotals : (is_object($potensiTotals) ? $potensiTotals->toArray() : []);
                                    $totalData = array_sum($potensiTotalsArray);
                                @endphp
                                {{ $totalData }}
                            </span>
                        </div>
                    </div>
                    <div class="w-full flex flex-col gap-3 justify-center md:pl-8">
                        @php
                            $colors = ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#f43f5e', '#14b8a6', '#64748b'];
                            $potensiLabelsArray = is_array($potensiLabels) ? $potensiLabels : (is_object($potensiLabels) ? $potensiLabels->toArray() : []);
                        @endphp
                        @foreach($potensiLabelsArray as $i => $label)
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full shadow-sm" style="background-color: {{ $colors[$i % count($colors)] }}"></span>
                                <span class="text-gray-700 font-medium text-sm tracking-wide">{{ ucfirst($label) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Row 2: Content & Distribusi Galeri -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <!-- Content Statistics Chart -->
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-8 pb-10 relative">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b border-gray-50 pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 tracking-tight">Statistik Konten</h2>
                        <p class="text-sm text-gray-500 mt-1">Produksi konten tahun <span id="contentYearLabel" class="font-semibold text-emerald-600">{{ $currentContentYear }}</span></p>
                    </div>

                    <!-- Year Picker with Navigation -->
                    <div class="flex items-center gap-2">
                        <button type="button" id="contentYearPrev" class="w-9 h-9 flex items-center justify-center bg-gray-50 hover:bg-emerald-50 text-gray-500 hover:text-emerald-600 rounded-xl transition-colors border border-gray-100" title="Tahun Sebelumnya">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <input type="number" id="contentYearFilter" value="{{ $currentContentYear }}" min="2000" max="2100"
                               class="w-20 text-center form-input bg-gray-50 rounded-xl border-gray-100 shadow-none focus:border-emerald-500 focus:ring-emerald-500 focus:bg-white text-sm font-bold text-gray-700">
                        <button type="button" id="contentYearNext" class="w-9 h-9 flex items-center justify-center bg-gray-50 hover:bg-emerald-50 text-gray-500 hover:text-emerald-600 rounded-xl transition-colors border border-gray-100" title="Tahun Berikutnya">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <button type="button" id="contentYearReset" class="ml-1 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition-colors" title="Kembali ke Tahun Ini">
                            Tahun Ini
                        </button>
                    </div>
                </div>

                <div class="relative w-full h-[350px]">
                    <canvas id="contentChart"></canvas>

                    <!-- Loading Overlay -->
                    <div id="contentChartLoading" class="absolute inset-0 bg-white/80 backdrop-blur-sm z-20 flex items-center justify-center rounded-3xl" style="display:none;">
                        <div class="text-center">
                            <svg class="animate-spin h-10 w-10 text-emerald-600 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-sm font-medium text-emerald-800">Mempersiapkan data...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Distribusi Galeri -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex flex-col justify-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 tracking-tight mb-2">Distribusi Galeri</h2>
                    <p class="text-xs text-gray-500 mb-6">Persentase galeri berdasarkan kategori</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center flex-1">
                    <div class="relative h-[256px] w-full flex justify-center items-center">
                        <canvas id="galeriChart" style="z-index: 10;"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none" style="z-index: 0;">
                            <span class="text-[11px] font-semibold text-gray-500 mb-1">Total Galeri</span>
                            <span class="text-3xl font-extrabold text-gray-900">
                                @php
                                    $galeriTotalsArray = is_array($galeriTotals) ? $galeriTotals : (is_object($galeriTotals) ? $galeriTotals->toArray() : []);
                                    $totalGaleriData = array_sum($galeriTotalsArray);
                                @endphp
                                {{ $totalGaleriData }}
                            </span>
                        </div>
                    </div>
                    <div class="w-full flex flex-col gap-3 justify-center md:pl-8">
                        @php
                            $galeriColors = ['#10b981', '#f59e0b', '#a855f7', '#3b82f6', '#f43f5e'];
                            $galeriLabelsArray = is_array($galeriLabels) ? $galeriLabels : (is_object($galeriLabels) ? $galeriLabels->toArray() : []);
                        @endphp
                        @foreach($galeriLabelsArray as $i => $label)
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full shadow-sm" style="background-color: {{ $galeriColors[$i % count($galeriColors)] }}"></span>
                                <span class="text-gray-700 font-medium text-sm tracking-wide">{{ ucfirst($label) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Row 3: Top 5 Konten Terpopuler -->
        <div class="grid grid-cols-1 gap-6 mt-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex flex-col justify-center">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 id="topContentTitle" class="text-xl font-bold text-gray-800 tracking-tight mb-2">Top 5 Berita Populer</h2>
                        <p id="topContentSubtitle" class="text-xs text-gray-500">Artikel dengan jumlah tayangan tertinggi</p>
                    </div>
                    <!-- Nav Tabs -->
                    <div class="flex space-x-1 bg-gray-50 p-1 rounded-xl border border-gray-100">
                        <button class="topContentTab bg-white shadow-sm text-gray-800 border-gray-200 px-3 py-1.5 text-xs font-semibold rounded-lg transition-all border" data-target="berita">Berita</button>
                        <button class="topContentTab text-gray-500 hover:text-gray-700 px-3 py-1.5 text-xs font-semibold rounded-lg transition-all" data-target="potensi">Potensi</button>
                        <button class="topContentTab text-gray-500 hover:text-gray-700 px-3 py-1.5 text-xs font-semibold rounded-lg transition-all" data-target="galeri">Galeri</button>
                        <button class="topContentTab text-gray-500 hover:text-gray-700 px-3 py-1.5 text-xs font-semibold rounded-lg transition-all" data-target="publikasi">Dokumen</button>
                    </div>
                </div>
                <div class="relative h-[300px] w-full">
                    <canvas id="topContentChart"></canvas>
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Berita</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah Lihat</th>
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
                                        <div class="text-xs font-medium text-gray-600">
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

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-600">
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
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Potensi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah Lihat</th>
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
                                            <div class="text-xs font-medium text-gray-600">{{ Str::limit(strip_tags($potensi->deskripsi), 60) }}</div>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-600">
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
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Galeri</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah Lihat</th>
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
                                            <div class="text-xs font-medium text-gray-600">{{ $galeri->deskripsi ? Str::limit($galeri->deskripsi, 60) : 'Tidak ada deskripsi' }}</div>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-600">
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
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Publikasi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah Lihat</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Unduh</th>
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
                                            <div class="text-xs font-medium text-gray-600">{{ $publikasi->deskripsi ? Str::limit($publikasi->deskripsi, 60) : 'Tidak ada deskripsi' }}</div>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-600">
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

            let gradientBlue = visitorCtx.createLinearGradient(0, 0, 0, 350);
            gradientBlue.addColorStop(0, 'rgba(59, 130, 246, 0.6)');
            gradientBlue.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            let gradientOrange = visitorCtx.createLinearGradient(0, 0, 0, 350);
            gradientOrange.addColorStop(0, 'rgba(249, 115, 22, 0.6)');
            gradientOrange.addColorStop(1, 'rgba(249, 115, 22, 0.0)');

            visitorChart = new Chart(visitorCtx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Pengunjung Unik',
                            data: chartData.visitors,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: gradientBlue,
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
                            label: 'Tampilan Halaman',
                            data: chartData.pageViews,
                            borderColor: 'rgb(249, 115, 22)',
                            backgroundColor: gradientOrange,
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
                    layout: {
                        padding: { bottom: 10 }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 13,
                                    family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif",
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif",
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13,
                                family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif"
                            },
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 8,
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
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 12,
                                    family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif",
                                    weight: 'bold'
                                },
                                color: '#475569'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.04)',
                                drawBorder: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                font: {
                                    size: 12,
                                    family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif",
                                    weight: 'bold'
                                },
                                color: '#475569',
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

            let gradBerita = contentCtx.createLinearGradient(0, 0, 0, 350);
            gradBerita.addColorStop(0, 'rgba(59, 130, 246, 0.5)');
            gradBerita.addColorStop(1, 'rgba(59, 130, 246, 0.05)');

            let gradPotensi = contentCtx.createLinearGradient(0, 0, 0, 350);
            gradPotensi.addColorStop(0, 'rgba(34, 197, 94, 0.5)');
            gradPotensi.addColorStop(1, 'rgba(34, 197, 94, 0.05)');

            let gradGaleri = contentCtx.createLinearGradient(0, 0, 0, 350);
            gradGaleri.addColorStop(0, 'rgba(168, 85, 247, 0.5)');
            gradGaleri.addColorStop(1, 'rgba(168, 85, 247, 0.05)');

            let gradPublikasi = contentCtx.createLinearGradient(0, 0, 0, 350);
            gradPublikasi.addColorStop(0, 'rgba(249, 115, 22, 0.5)');
            gradPublikasi.addColorStop(1, 'rgba(249, 115, 22, 0.05)');

            contentChart = new Chart(contentCtx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Berita',
                            data: chartData.berita,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: gradBerita,
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointBackgroundColor: 'rgb(59, 130, 246)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        },
                        {
                            label: 'Potensi',
                            data: chartData.potensi,
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: gradPotensi,
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointBackgroundColor: 'rgb(34, 197, 94)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        },
                        {
                            label: 'Galeri',
                            data: chartData.galeri,
                            borderColor: 'rgb(168, 85, 247)',
                            backgroundColor: gradGaleri,
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointBackgroundColor: 'rgb(168, 85, 247)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        },
                        {
                            label: 'Publikasi',
                            data: chartData.publikasi,
                            borderColor: 'rgb(249, 115, 22)',
                            backgroundColor: gradPublikasi,
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointRadius: 0,
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
                    layout: {
                        padding: { bottom: 10 }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 13,
                                    family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif",
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif",
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13,
                                family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif"
                            },
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 8,
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
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 12,
                                    family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif",
                                    weight: 'bold'
                                },
                                color: '#475569'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.04)',
                                drawBorder: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 12,
                                    family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif",
                                    weight: 'bold'
                                },
                                color: '#475569'
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

        // ---------------- NEW ANALYTICS CHARTS ---------------- //

        // 1. Distribusi Potensi Desa (Doughnut Chart)
        const potensiCtx = document.getElementById('potensiChart').getContext('2d');
        new Chart(potensiCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($potensiLabels) !!},
                datasets: [{
                    data: {!! json_encode($potensiTotals) !!},
                    backgroundColor: [
                        '#10b981', // emerald
                        '#3b82f6', // blue
                        '#f59e0b', // amber
                        '#8b5cf6', // purple
                        '#f43f5e', // rose
                        '#14b8a6', // teal
                        '#64748b'  // slate
                    ],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { 
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif" },
                        bodyFont: { family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif" },
                        padding: 12,
                        cornerRadius: 8,
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1
                    }
                }
            }
        });

        // 1b. Distribusi Galeri (Doughnut Chart)
        const galeriCtx = document.getElementById('galeriChart').getContext('2d');
        new Chart(galeriCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($galeriLabels) !!},
                datasets: [{
                    data: {!! json_encode($galeriTotals) !!},
                    backgroundColor: [
                        '#10b981', // Kegiatan (Emerald)
                        '#f59e0b', // Pembangunan (Amber)
                        '#a855f7', // Budaya (Purple)
                        '#0ea5e9', // Keagamaan (Sky)
                        '#ec4899', // Sosial (Pink)
                        '#f43f5e'  // Lainnya (Rose)
                    ],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif" },
                        bodyFont: { family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif" },
                        padding: 12,
                        cornerRadius: 8,
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1
                    }
                }
            }
        });

        // 2. Top 5 Konten Populer (Horizontal Bar Chart)
        const topContentCtx = document.getElementById('topContentChart').getContext('2d');

        const topContentData = {
            berita: { title: "Top 5 Berita Populer", subtitle: "Artikel dengan jumlah tayangan tertinggi", rawTitles: {!! json_encode($topBerita->pluck('judul')->toArray()) !!}, labels: {!! json_encode($topBerita->pluck('judul')->map(fn($j) => \Illuminate\Support\Str::limit($j, 32))->toArray()) !!}, data: {!! json_encode($topBerita->pluck('views')->toArray()) !!}, colorStart: 'rgba(14, 165, 233, 0.9)', colorEnd: 'rgba(2, 132, 199, 0.9)', colorHover: 'rgba(2, 132, 199, 1)' },
            potensi: { title: "Top 5 Potensi Populer", subtitle: "Potensi desa dengan jumlah tayangan tertinggi", rawTitles: {!! json_encode($topPotensi->pluck('judul')->toArray()) !!}, labels: {!! json_encode($topPotensi->pluck('judul')->map(fn($j) => \Illuminate\Support\Str::limit($j, 32))->toArray()) !!}, data: {!! json_encode($topPotensi->pluck('views')->toArray()) !!}, colorStart: 'rgba(16, 185, 129, 0.9)', colorEnd: 'rgba(5, 150, 105, 0.9)', colorHover: 'rgba(5, 150, 105, 1)' },
            galeri: { title: "Top 5 Galeri Populer", subtitle: "Galeri dengan jumlah tayangan tertinggi", rawTitles: {!! json_encode($topGaleri->pluck('judul')->toArray()) !!}, labels: {!! json_encode($topGaleri->pluck('judul')->map(fn($j) => \Illuminate\Support\Str::limit($j, 32))->toArray()) !!}, data: {!! json_encode($topGaleri->pluck('views')->toArray()) !!}, colorStart: 'rgba(168, 85, 247, 0.9)', colorEnd: 'rgba(147, 51, 234, 0.9)', colorHover: 'rgba(147, 51, 234, 1)' },
            publikasi: { title: "Top 5 Dokumen Populer", subtitle: "Publikasi dengan jumlah tayangan tertinggi", rawTitles: {!! json_encode($topPublikasi->pluck('judul')->toArray()) !!}, labels: {!! json_encode($topPublikasi->pluck('judul')->map(fn($j) => \Illuminate\Support\Str::limit($j, 32))->toArray()) !!}, data: {!! json_encode($topPublikasi->pluck('views')->toArray()) !!}, colorStart: 'rgba(59, 130, 246, 0.9)', colorEnd: 'rgba(37, 99, 235, 0.9)', colorHover: 'rgba(37, 99, 235, 1)' }
        };

        let currentTopContent = topContentData['berita'];

        function createGradBar(ctx, colorStart, colorEnd) {
            let grad = ctx.createLinearGradient(0, 0, 400, 0);
            grad.addColorStop(0, colorStart);
            grad.addColorStop(1, colorEnd);
            return grad;
        }

        let topContentChart = new Chart(topContentCtx, {
            type: 'bar',
            data: {
                labels: currentTopContent.labels,
                datasets: [{
                    label: 'Views',
                    data: currentTopContent.data,
                    backgroundColor: createGradBar(topContentCtx, currentTopContent.colorStart, currentTopContent.colorEnd),
                    hoverBackgroundColor: currentTopContent.colorHover,
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 18
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { right: 80 } },
                plugins: {
                    legend: { display: false },
                    tooltip: { 
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif" },
                        bodyFont: { family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif" },
                        padding: 12,
                        cornerRadius: 8,
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        callbacks: { 
                            title: function(context) { return currentTopContent.rawTitles[context[0].dataIndex]; }
                        } 
                    }
                },
                scales: {
                    x: { 
                        grid: { display: false, drawBorder: false }, 
                        ticks: { display: false },
                        max: currentTopContent.data.length > 0 ? Math.max(...currentTopContent.data) * 1.15 : 10
                    },
                    y: { 
                        grid: { display: false, drawBorder: false }, 
                        ticks: { font: { size: 12, family: "'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif", weight: 'bold' }, color: '#1e293b' } 
                    }
                }
            },
            plugins: [{
                id: 'topContentLabels',
                afterDatasetsDraw(chart, args, pluginOptions) {
                    const { ctx, data } = chart;
                    ctx.save();
                    chart.getDatasetMeta(0).data.forEach((datapoint, index) => {
                        const value = data.datasets[0].data[index];
                        ctx.font = "bold 13px 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif";
                        ctx.fillStyle = '#0f172a';
                        ctx.textAlign = 'left';
                        ctx.textBaseline = 'middle';
                        ctx.fillText(value.toLocaleString() + ' views', datapoint.x + 10, datapoint.y);
                    });
                    ctx.restore();
                }
            }]
        });

        // Tab switcher logic
        const topContentTabs = document.querySelectorAll('.topContentTab');
        topContentTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Reset all tabs
                topContentTabs.forEach(t => {
                    t.className = 'topContentTab text-gray-500 hover:text-gray-700 px-3 py-1.5 text-xs font-semibold rounded-lg transition-all';
                });
                // Set active tab
                this.className = 'topContentTab bg-white shadow-sm text-gray-800 border-gray-200 px-3 py-1.5 text-xs font-semibold rounded-lg transition-all border';

                const target = this.getAttribute('data-target');
                currentTopContent = topContentData[target];

                // Update UI headers
                document.getElementById('topContentTitle').innerText = currentTopContent.title;
                document.getElementById('topContentSubtitle').innerText = currentTopContent.subtitle;

                // Update chart
                topContentChart.data.labels = currentTopContent.labels;
                topContentChart.data.datasets[0].data = currentTopContent.data;
                topContentChart.data.datasets[0].backgroundColor = createGradBar(topContentCtx, currentTopContent.colorStart, currentTopContent.colorEnd);
                topContentChart.data.datasets[0].hoverBackgroundColor = currentTopContent.colorHover;

                // Handle empty data case for dynamic max scale recalculation gracefully
                const newMax = currentTopContent.data.length > 0 ? Math.max(...currentTopContent.data) * 1.15 : 10;
                topContentChart.options.scales.x.max = newMax;

                topContentChart.update();
            });
        });
    </script>
@endsection
