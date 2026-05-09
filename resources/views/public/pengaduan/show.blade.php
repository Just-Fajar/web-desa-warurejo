@extends('public.layouts.app')

@section('title', $pengaduan->judul . ' - Forum Pengaduan')

@section('content')

    {{-- Breadcrumb Header --}}
    <section class="bg-gradient-to-br from-primary-700 via-primary-600 to-primary-800 pt-28 pb-12 relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/5 rounded-full blur-3xl"></div>
        <div class="container mx-auto px-4 relative z-10">
            <nav class="text-sm text-primary-200 mb-4">
                <a href="{{ route('pengaduan.index') }}" class="hover:text-white transition-colors">Forum Pengaduan</a>
                <span class="mx-2">›</span>
                <span class="text-white font-medium">Detail</span>
            </nav>
            <h1 class="text-2xl md:text-3xl font-extrabold text-white leading-tight">{{ $pengaduan->judul }}</h1>
        </div>
    </section>

    <section class="py-10 md:py-14 bg-gray-50">
        <div class="container mx-auto px-4 max-w-4xl">

            {{-- SECTION 1: Info Pengaduan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-6 ring-1 ring-black/5">
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    {{-- Status Badge --}}
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold {{ $pengaduan->status_badge['bg'] }} {{ $pengaduan->status_badge['text'] }} border {{ $pengaduan->status_badge['border'] }}">
                        {{ $pengaduan->status_badge['label'] }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Pelapor</p>
                            <p class="font-bold text-gray-800">{{ $pengaduan->nama_sensor }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Lokasi</p>
                            <p class="font-bold text-gray-800">{{ $pengaduan->lokasi_kejadian }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Tanggal</p>
                            <p class="font-bold text-gray-800">{{ $pengaduan->created_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Balasan</p>
                            <p class="font-bold text-gray-800">{{ $pengaduan->balasan->count() }} balasan admin</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Isi Pengaduan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-6 ring-1 ring-black/5">
                <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Isi Pengaduan</h2>
                <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($pengaduan->isi)) !!}
                </div>
            </div>

            {{-- SECTION 3: Lampiran (hanya jika ada) --}}
            @if($pengaduan->lampiran)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-6 ring-1 ring-black/5">
                    <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Lampiran</h2>

                    @if($pengaduan->isImage())
                        <div class="rounded-xl overflow-hidden border border-gray-100 cursor-pointer" onclick="openLampiranModal('{{ $pengaduan->lampiran_url }}')">
                            <img src="{{ $pengaduan->lampiran_url }}" alt="Lampiran pengaduan"
                                class="w-full max-h-96 object-contain bg-gray-50 hover:opacity-90 transition-opacity">
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Klik gambar untuk memperbesar</p>
                    @elseif($pengaduan->isPdf())
                        <a href="{{ $pengaduan->lampiran_url }}" target="_blank"
                            class="inline-flex items-center gap-3 bg-red-50 border border-red-100 rounded-xl px-5 py-4 hover:bg-red-100 transition-colors group">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">Unduh Lampiran PDF</p>
                                <p class="text-xs text-gray-500">Klik untuk membuka file</p>
                            </div>
                        </a>
                    @endif
                </div>
            @endif

            {{-- SECTION 4: Alasan Penolakan (jika status Ditolak & ada alasan) --}}
            @if($pengaduan->status === 'Ditolak' && $pengaduan->alasan_penolakan)
                <div class="bg-red-50 rounded-2xl border border-red-200 p-6 md:p-8 mb-6">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-red-700 uppercase tracking-wider mb-2">Alasan Penolakan</h3>
                            <p class="text-red-800 leading-relaxed">{{ $pengaduan->alasan_penolakan }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- SECTION 5: Balasan Admin --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-6 ring-1 ring-black/5">
                <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6">Respons Admin Desa</h2>

                @if($pengaduan->balasan->count() > 0)
                    <div class="space-y-4">
                        @foreach($pengaduan->balasan as $balasan)
                            <div class="bg-green-50/60 rounded-xl border border-green-100 p-5">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-bold text-green-800">Admin Desa</span>
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-600 text-white uppercase tracking-wider">Admin</span>
                                    </div>
                                    <span class="ml-auto text-xs text-gray-400">{{ $balasan->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="text-gray-700 leading-relaxed text-sm pl-10">
                                    {!! nl2br(e($balasan->isi)) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="text-gray-400 font-medium">Menunggu respons dari admin desa.</p>
                        <p class="text-gray-300 text-sm mt-1">Admin akan segera merespons pengaduan Anda.</p>
                    </div>
                @endif
            </div>

            {{-- Back Button --}}
            <div class="text-center">
                <a href="{{ route('pengaduan.index') }}"
                    class="inline-flex items-center gap-2 text-primary-600 font-semibold hover:text-primary-700 transition-colors bg-white px-6 py-3 rounded-full border border-gray-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Daftar Pengaduan
                </a>
            </div>

        </div>
    </section>

    {{-- Lampiran Image Modal --}}
    @if($pengaduan->lampiran && $pengaduan->isImage())
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
            document.getElementById('lampiranModal').addEventListener('click', function(e) {
                if (e.target === this) this.classList.add('hidden');
            });
        </script>
    @endif

@endsection
