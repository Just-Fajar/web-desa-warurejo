@extends('public.layouts.app')

@section('title', 'Forum Pengaduan')

@section('content')

    {{-- Hero Section --}}
    <section class="bg-slate-50 pt-32 pb-12 border-b border-gray-100">
        <div class="container mx-auto px-4 max-w-5xl">
            <div class="flex flex-col items-start text-left">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-px bg-slate-500"></div>
                    <span class="text-slate-500 text-sm font-semibold tracking-widest uppercase">Suara Warga</span>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 leading-[1.1]">
                    <span class="text-slate-900 block">Layanan Pengaduan</span>
                    <span class="text-slate-400 block">Masyarakat.</span>
                </h1>
                
                <p class="text-slate-600 text-lg leading-relaxed mb-10 max-w-2xl">
                    Sampaikan aspirasi, keluhan, dan saran Anda untuk membangun Desa Harapan yang lebih baik. Kami siap mendengar dan menindaklanjuti.
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <button id="btnOpenModal"
                        class="group w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-slate-900 text-white px-8 py-4 rounded-xl font-semibold hover:bg-slate-800 transition-all duration-300 shadow-[0_8px_20px_rgb(15,23,42,0.2)] hover:shadow-[0_10px_25px_rgb(15,23,42,0.3)] hover:-translate-y-0.5">
                        <div class="bg-white/10 p-2 rounded-lg group-hover:bg-white/20 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="text-base">Buat Pengaduan</span>
                    </button>
                    <a href="https://wa.me/62085168687700?text=Halo%20Admin%20Desa%20Warurejo,%20saya%20ingin%20menyampaikan%20pengaduan" target="_blank"
                        class="group w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-white text-slate-700 px-8 py-4 rounded-xl font-semibold border-2 border-slate-100 hover:border-green-500/30 hover:bg-green-50 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                        <div class="bg-green-50 p-2 rounded-lg group-hover:bg-green-100 transition-colors">
                            <svg class="w-5 h-5 text-green-500 group-hover:text-green-600 transition-colors" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                        </div>
                        <span class="text-base">Tanya via WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </section>



    {{-- Filter & List --}}
    <section class="py-8 md:py-12 bg-gray-50">
        <div class="container mx-auto px-4">

            {{-- Search & Filter Modern --}}
            <div class="mb-10 relative z-20 scroll-reveal">
                <form method="GET" action="{{ route('pengaduan.index') }}" class="bg-white rounded-2xl shadow-xl shadow-black/5 p-4 md:p-6 ring-1 ring-black/5" x-data="{ searchQuery: '{{ request('search') }}' }">
                    
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
                            placeholder="Cari tahu tentang pengaduan..." 
                            value="{{ request('search') }}"
                            class="w-full pl-12 pr-4 py-4 md:text-lg border-2 border-gray-300 bg-white rounded-xl focus:ring-0 focus:border-primary-500 transition-colors text-gray-900 font-semibold placeholder-gray-500"
                            autocomplete="off"
                        >
                    </div>
                    
                    {{-- Bottom Row: Filters --}}
                    <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                            {{-- Tanggal Mulai --}}
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="text" name="date_from" value="{{ request('date_from') }}" placeholder="Tanggal Mulai" class="datepicker w-full pl-10 pr-3 py-3 md:py-2.5 border-2 border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-900 focus:ring-primary-500 focus:border-primary-500 placeholder-gray-500" title="Tanggal Mulai">
                            </div>
                            {{-- Tanggal Akhir --}}
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="text" name="date_to" value="{{ request('date_to') }}" placeholder="Tanggal Akhir" class="datepicker w-full pl-10 pr-3 py-3 md:py-2.5 border-2 border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-900 focus:ring-primary-500 focus:border-primary-500 placeholder-gray-500" title="Tanggal Akhir">
                            </div>
                            {{-- Status Pengaduan --}}
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <select name="status" class="w-full pl-10 pr-8 py-3 md:py-2.5 border-2 border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-900 focus:ring-primary-500 focus:border-primary-500 appearance-none">
                                    <option value="">Semua Status</option>
                                    @foreach(['Menunggu' => 'Menunggu', 'Diproses' => 'Diproses', 'Selesai' => 'Selesai', 'Ditolak' => 'Ditolak'] as $value => $label)
                                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Urutkan --}}
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                                </div>
                                <select name="sort" class="w-full pl-10 pr-8 py-3 md:py-2.5 border-2 border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-900 focus:ring-primary-500 focus:border-primary-500 appearance-none">
                                    <option value="latest" {{ request('sort') === 'latest' || !request('sort') ? 'selected' : '' }}>Terbaru</option>
                                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                                </select>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2 mt-2 lg:mt-0">
                            @if(request()->anyFilled(['search', 'status', 'sort', 'date_from', 'date_to']))
                            <a href="{{ route('pengaduan.index') }}" class="px-5 py-3 md:py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors inline-flex items-center">
                                Reset
                            </a>
                            @endif
                            <button type="submit" class="px-6 py-3 md:py-2.5 text-sm font-bold text-white bg-slate-800 hover:bg-slate-900 rounded-xl transition-all shadow-md shadow-slate-900/20 active:scale-95 inline-flex items-center justify-center w-full md:w-auto">
                                Cari Pengaduan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Pengaduan List --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($pengaduan as $item)
                    <a href="{{ route('pengaduan.show', $item->id) }}"
                        class="block bg-white rounded-2xl p-5 md:p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:border-slate-200 transition-all duration-300 group ring-1 ring-black/5 hover:ring-slate-500/10 flex flex-col h-full relative">

                        {{-- Top Section: Badges & Arrow --}}
                        <div class="flex items-start justify-between gap-4 mb-3">
                            {{-- Status & Reply Badges --}}
                            <div class="flex flex-wrap items-center gap-2">
                                {{-- Status Badge --}}
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $item->status_badge['bg'] }} {{ $item->status_badge['text'] }} border {{ $item->status_badge['border'] }}">
                                    {{ $item->status_badge['label'] }}
                                </span>
                                {{-- Jumlah Balasan --}}
                                @if($item->balasan_count > 0)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full border border-green-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        {{ $item->balasan_count }} balasan
                                    </span>
                                @endif
                            </div>
                            
                            {{-- Arrow --}}
                            <div class="shrink-0 hidden sm:block">
                                <span class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-slate-800 group-hover:text-white transition-all duration-300 transform group-hover:translate-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 flex flex-col">
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-slate-800 transition-colors mb-2 line-clamp-2">
                                {{ $item->judul }}
                            </h3>

                            <p class="text-sm text-gray-600 line-clamp-3 mb-4 flex-1">
                                {{ Str::limit(strip_tags($item->isi), 200) }}
                            </p>

                            {{-- Meta Footer --}}
                            <div class="flex flex-col gap-2 text-xs text-gray-500 mt-auto pt-4 border-t border-gray-100">
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $item->nama_sensor }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $item->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate">{{ $item->lokasi_kejadian }}</span>
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-1 md:col-span-2 bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                        <svg class="w-20 h-20 mx-auto text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="text-lg font-bold text-gray-400 mb-2">Belum ada pengaduan</h3>
                        <p class="text-gray-400 text-sm mb-6">Jadilah yang pertama menyampaikan aspirasi Anda.</p>
                        <button id="btnOpenModalEmpty"
                            class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-full font-bold hover:bg-slate-800 shadow-md transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Pengaduan Pertama
                        </button>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($pengaduan->hasPages())
                <div class="mt-8">
                    {{ $pengaduan->links() }}
                </div>
            @endif
        </div>
    </section>

    {{-- ===========================
         MODAL FORM BUAT PENGADUAN
    ============================ --}}
    <div id="formModal" class="fixed inset-0 z-[110] hidden">
        {{-- Backdrop --}}
        <div id="modalBackdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        {{-- Modal Content --}}
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto relative">

                {{-- Close Button --}}
                <button id="btnCloseModal"
                    class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 hover:text-gray-700 transition-colors z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Header --}}
                <div class="p-6 pb-0">
                    <h2 class="text-2xl font-extrabold text-gray-800 mb-1">Buat Pengaduan</h2>
                    <p class="text-sm text-gray-500 mb-4">Sampaikan aspirasi atau keluhan Anda. Lengkapi data diri dengan jelas dan menggunakan informasi yang valid agar pengaduan dapat diverifikasi serta diproses dengan baik.</p>
                    
                    {{-- Warning Box --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex gap-3 text-amber-800">
                        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="text-xs sm:text-sm leading-relaxed">
                            <strong class="font-bold">Perhatian:</strong> Layanan pengaduan ini hanya ditujukan bagi masyarakat Desa Warurejo. Mohon menyampaikan pengaduan secara sopan, jujur, dan bertanggung jawab. Pengaduan yang mengandung laporan palsu, spam, bahasa kasar atau tidak pantas, fitnah, provokasi, ujaran kebencian, maupun informasi yang tidak dapat diverifikasi berhak ditolak atau dihapus oleh administrator.
                        </div>
                    </div>
                </div>

                {{-- Form --}}
                <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                    @csrf

                    {{-- Nama Pelapor --}}
                    <div>
                        <label for="nama_pelapor" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Pelapor <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pelapor" id="nama_pelapor" value="{{ old('nama_pelapor') }}" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-slate-400 focus:ring-4 focus:ring-slate-400/10 transition-all text-sm font-medium text-slate-800 placeholder-slate-400"
                            placeholder="Nama lengkap Anda">
                        @error('nama_pelapor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nomor WA --}}
                    <div>
                        <label for="nomor_wa" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" name="nomor_wa" id="nomor_wa" value="{{ old('nomor_wa') }}" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-slate-400 focus:ring-4 focus:ring-slate-400/10 transition-all text-sm font-medium text-slate-800 placeholder-slate-400"
                            placeholder="08xxx atau +628xxx">
                        @error('nomor_wa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Judul --}}
                    <div>
                        <label for="judul" class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Pengaduan <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-slate-400 focus:ring-4 focus:ring-slate-400/10 transition-all text-sm font-medium text-slate-800 placeholder-slate-400"
                            placeholder="Ringkas dan jelas">
                        @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Isi --}}
                    <div>
                        <label for="isi" class="block text-sm font-semibold text-slate-700 mb-1.5">Isi Pengaduan <span class="text-red-500">*</span></label>
                        <textarea name="isi" id="isi" rows="5" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-slate-400 focus:ring-4 focus:ring-slate-400/10 transition-all text-sm font-medium text-slate-800 placeholder-slate-400 resize-y"
                            placeholder="Jelaskan pengaduan Anda secara detail...">{{ old('isi') }}</textarea>
                        @error('isi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Lokasi --}}
                    <div>
                        <label for="lokasi_kejadian" class="block text-sm font-semibold text-slate-700 mb-1.5">Lokasi Kejadian <span class="text-red-500">*</span></label>
                        <input type="text" name="lokasi_kejadian" id="lokasi_kejadian" value="{{ old('lokasi_kejadian') }}" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-slate-400 focus:ring-4 focus:ring-slate-400/10 transition-all text-sm font-medium text-slate-800 placeholder-slate-400"
                            placeholder="Contoh: RT 02/RW 01, Dusun Krajan">
                        @error('lokasi_kejadian') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Lampiran --}}
                    <div>
                        <label for="lampiran" class="block text-sm font-semibold text-slate-700 mb-1.5">Lampiran <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <div class="relative group">
                            <input type="file" name="lampiran" id="lampiran" accept=".jpg,.jpeg,.png,.pdf"
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-slate-400 focus:ring-4 focus:ring-slate-400/10 transition-all text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-800 file:text-white hover:file:bg-slate-700 file:transition-colors file:cursor-pointer cursor-pointer text-slate-500">
                        </div>
                        <p class="text-xs text-slate-500 mt-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Format: JPG, JPEG, PNG, atau PDF. Maks 5MB.
                        </p>
                        @error('lampiran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="pt-4 border-t border-slate-100 mt-6">
                        <button type="submit"
                            class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold text-sm hover:bg-slate-800 transition-all shadow-[0_8px_20px_rgb(15,23,42,0.2)] hover:shadow-[0_10px_25px_rgb(15,23,42,0.3)] hover:-translate-y-0.5 flex items-center justify-center gap-2 group">
                            <svg class="w-5 h-5 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Kirim Pengaduan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Auto-open modal if validation errors --}}
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('formModal').classList.remove('hidden');
            });
        </script>
    @endif

    {{-- Success message via SweetAlert --}}
    @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    iconColor: '#22c55e',
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: 'OK',
                    timer: 4000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .scroll-reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
    .scroll-reveal.revealed { opacity: 1; transform: translateY(0); }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal logic to avoid CSP inline violations
        const formModal = document.getElementById('formModal');
        const btnOpenModal = document.getElementById('btnOpenModal');
        const btnOpenModalEmpty = document.getElementById('btnOpenModalEmpty');
        const btnCloseModal = document.getElementById('btnCloseModal');
        const modalBackdrop = document.getElementById('modalBackdrop');

        function openModal() {
            if (formModal) formModal.classList.remove('hidden');
        }

        function closeModal() {
            if (formModal) formModal.classList.add('hidden');
        }

        if (btnOpenModal) btnOpenModal.addEventListener('click', openModal);
        if (btnOpenModalEmpty) btnOpenModalEmpty.addEventListener('click', openModal);
        if (btnCloseModal) btnCloseModal.addEventListener('click', closeModal);
        if (modalBackdrop) modalBackdrop.addEventListener('click', closeModal);

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
        
        document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));
    });
</script>
@endpush
