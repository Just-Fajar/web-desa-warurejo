@extends('public.layouts.app')

@section('title', 'Detail Pengaduan - Desa Warurejo')

@section('content')
    {{-- Breadcrumb --}}
    <section class="bg-gray-100 pt-24 pb-3 sm:pt-28 sm:pb-4 lg:pt-32">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center flex-wrap text-xs sm:text-sm text-gray-600 scroll-reveal overflow-hidden min-w-0">
                <a href="{{ route('home') }}" class="hover:text-primary-600 shrink-0">Beranda</a>
                <svg class="w-3 h-3 sm:w-4 sm:h-4 mx-1.5 sm:mx-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
                <a href="{{ route('pengaduan.index') }}" class="hover:text-primary-600 shrink-0">Forum Pengaduan</a>
                <svg class="w-3 h-3 sm:w-4 sm:h-4 mx-1.5 sm:mx-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-gray-800 font-semibold truncate min-w-0">{{ Str::limit($pengaduan->judul, 40) }}</span>
            </div>
        </div>
    </section>

    {{-- Article Content --}}
    <section class="py-6 sm:py-8 md:py-12 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-[1100px]">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

                {{-- Main Article (Left Column) --}}
                <div class="lg:col-span-8">
                    <article class="bg-white scroll-reveal">

                        {{-- Title & Header Info --}}
                        <h1
                            class="text-[28px] sm:text-[32px] md:text-[38px] font-bold text-[#003366] mb-4 leading-tight tracking-tight">
                            {{ $pengaduan->judul }}
                        </h1>

                        <div class="flex flex-wrap items-center text-[13px] sm:text-[14px] text-gray-500 mb-6 font-medium gap-y-2">
                            <span class="text-gray-800">{{ $pengaduan->nama_sensor }}</span>
                            <span class="mx-2 text-gray-300">-</span>
                            <span class="font-semibold text-[#003366]">Pengaduan Publik</span>
                            <span class="mx-2 text-gray-300">|</span>
                            <span>{{ $pengaduan->created_at->locale('id')->isoFormat('dddd, D MMM Y HH:mm') }} WIB</span>
                            
                            <span class="ml-auto inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold {{ $pengaduan->status_badge['bg'] }} {{ $pengaduan->status_badge['text'] }} border {{ $pengaduan->status_badge['border'] }}">
                                {{ $pengaduan->status_badge['label'] }}
                            </span>
                        </div>

                        {{-- Featured Image (Lampiran if image) --}}
                        @if($pengaduan->lampiran && $pengaduan->isImage())
                            <div class="relative w-full mb-3 cursor-pointer group" onclick="openLampiranModal('{{ $pengaduan->lampiran_url }}')">
                                <img src="{{ $pengaduan->lampiran_url }}" alt="{{ $pengaduan->judul }}"
                                    class="w-full object-cover rounded-sm group-hover:opacity-90 transition-opacity" loading="lazy">
                            </div>
                            <div class="text-[11px] sm:text-[12px] text-gray-500 italic mb-8 border-b border-gray-100 pb-4">
                                Lampiran Foto: Laporan Warga (Klik untuk memperbesar)
                            </div>
                        @elseif($pengaduan->lampiran && $pengaduan->isPdf())
                            <div class="mb-8 border-b border-gray-100 pb-6">
                                <a href="{{ $pengaduan->lampiran_url }}" target="_blank"
                                    class="inline-flex items-center gap-3 bg-red-50 border border-red-100 rounded-xl px-5 py-4 hover:bg-red-100 transition-colors group w-full sm:w-auto">
                                    <div
                                        class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors shrink-0">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">Unduh Lampiran PDF</p>
                                        <p class="text-xs text-gray-500">Klik untuk membuka atau mengunduh file</p>
                                    </div>
                                </a>
                            </div>
                        @else
                            <div class="border-b border-gray-100 mb-6"></div>
                        @endif

                        {{-- Content --}}
                        <div class="prose prose-sm sm:prose-base lg:prose-lg max-w-none text-[#333] leading-relaxed break-words overflow-hidden mb-8">
                            <span class="font-bold">Laporan</span> - {!! nl2br(e($pengaduan->isi)) !!}
                        </div>

                        {{-- Alasan Penolakan --}}
                        @if($pengaduan->status === 'Ditolak' && $pengaduan->alasan_penolakan)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-5 mb-8">
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-[14px] font-bold text-red-700 uppercase tracking-wider mb-2">Alasan Penolakan Laporan</h3>
                                        <p class="text-red-800 leading-relaxed text-sm">{{ $pengaduan->alasan_penolakan }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Balasan Admin (Tanggapan) --}}
                        @if($pengaduan->balasan->count() > 0)
                            <div class="mt-12 pt-8 border-t-[3px] border-[#003366]">
                                <h2 class="text-xl font-bold text-[#003366] mb-6 flex items-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    Tanggapan Admin Desa
                                </h2>
                                <div class="space-y-6">
                                    @foreach($pengaduan->balasan as $balasan)
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                                <span class="text-sm font-bold text-[#003366]">Admin Desa</span>
                                                <span class="ml-auto text-xs text-gray-500 font-medium">{{ $balasan->created_at->format('d M Y, H:i') }}</span>
                                            </div>
                                            <div class="text-gray-700 leading-relaxed text-sm sm:text-base">
                                                @if($balasan->isi)
                                                    <div class="prose prose-sm max-w-none mb-3">
                                                        {!! nl2br(e($balasan->isi)) !!}
                                                    </div>
                                                @endif
                                                @if($balasan->lampiran && $balasan->isImage())
                                                    <div class="mt-4 border-t border-gray-200 pt-3">
                                                        <div class="text-[11px] text-gray-500 italic mb-2">Lampiran Bukti dari Admin:</div>
                                                        <div class="rounded-lg overflow-hidden border border-gray-200 max-w-sm cursor-pointer hover:opacity-90 transition-opacity" onclick="openLampiranModal('{{ $balasan->lampiran_url }}')">
                                                            <img src="{{ $balasan->lampiran_url }}" alt="Bukti dari admin" class="w-full object-cover">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="mt-12 pt-8 border-t border-gray-100 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 text-gray-400 mb-4 border border-gray-100">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-700 mb-1">Menunggu Tanggapan</h3>
                                <p class="text-gray-500 font-medium text-sm">Laporan ini masih dalam antrean untuk diproses dan ditanggapi oleh pihak desa.</p>
                            </div>
                        @endif

                    </article>
                </div>

                {{-- Sidebar (Right Column) --}}
                <div class="lg:col-span-4 mt-8 lg:mt-0">
                    <div class="sticky top-24">
                        
                        {{-- Informasi Pengaduan Sidebar --}}
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-8">
                            <h2 class="text-xl font-bold text-[#003366] mb-5 border-b-2 border-[#003366] pb-2 inline-block">
                                Detail Laporan
                            </h2>
                            <div class="space-y-4 text-sm">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shrink-0 border border-gray-200">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="block text-gray-500 font-semibold mb-0.5">Nama Pelapor</span>
                                        <span class="font-bold text-gray-900">{{ $pengaduan->nama_sensor }}</span>
                                    </div>
                                </div>
                                
                                <div class="border-b border-gray-200"></div>

                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shrink-0 border border-gray-200">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="block text-gray-500 font-semibold mb-0.5">Lokasi Kejadian</span>
                                        <span class="font-bold text-gray-900">{{ $pengaduan->lokasi_kejadian }}</span>
                                    </div>
                                </div>

                                <div class="border-b border-gray-200"></div>

                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shrink-0 border border-gray-200">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="block text-gray-500 font-semibold mb-0.5">Tanggal Dilaporkan</span>
                                        <span class="font-bold text-gray-900">{{ $pengaduan->created_at->format('d F Y') }}</span>
                                        <span class="text-gray-500 ml-1">{{ $pengaduan->created_at->format('H:i') }} WIB</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Lampiran Image Modal --}}
    <div id="lampiranModal" class="fixed inset-0 bg-black/90 backdrop-blur-sm flex items-center justify-center hidden z-[100]">
        <div class="relative max-w-5xl w-full mx-4">
            <button onclick="document.getElementById('lampiranModal').classList.add('hidden')"
                class="absolute -top-12 right-0 text-white/70 hover:text-white text-4xl font-bold transition-colors">&times;</button>
            <img id="lampiranModalImage" src="" class="w-full max-h-[85vh] object-contain rounded-xl shadow-2xl">
        </div>
    </div>
    
    <script>
        function openLampiranModal(url) {
            document.getElementById('lampiranModalImage').src = url;
            document.getElementById('lampiranModal').classList.remove('hidden');
        }
        document.getElementById('lampiranModal').addEventListener('click', function (e) {
            if (e.target === this) this.classList.add('hidden');
        });
    </script>

    <style>
        /* Detik.com inspired typography */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
        }

        .scroll-reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .scroll-reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observerOptions = { root: null, threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.scroll-reveal').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
@endsection