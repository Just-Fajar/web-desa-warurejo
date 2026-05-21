@extends('admin.layouts.app')

@section('title', 'Detail Potensi Desa - ' . $potensi->nama)

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Potensi Desa</h1>
                <p class="text-sm text-gray-600 mt-1">Preview postingan potensi desa</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.potensi.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('admin.potensi.edit', $potensi->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Potensi
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $potensi->nama }}</h2>

                    @if($potensi->gambar)
                        <div class="rounded-2xl overflow-hidden border border-gray-100">
                            <img src="{{ Storage::url($potensi->gambar) }}" alt="{{ $potensi->nama }}" class="w-full object-cover max-h-[400px]">
                        </div>
                    @endif

                    <div class="prose max-w-none text-gray-800">
                        {!! $potensi->deskripsi !!}
                    </div>

                    <!-- Multiple Images Gallery -->
                    @if($potensi->fotoGaleri->count() > 0)
                        <div class="border-t border-gray-100 pt-6">
                            <h3 class="font-bold text-gray-900 text-lg mb-4">Foto Galeri Potensi</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                @foreach($potensi->fotoGaleri as $foto)
                                    <div class="rounded-xl overflow-hidden border border-gray-100 shadow-sm aspect-video">
                                        <img src="{{ Storage::url($foto->foto) }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Meta Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <h3 class="font-bold text-gray-900 text-lg border-b pb-3 border-gray-100">Informasi Detail</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kategori</span>
                            <span class="font-semibold text-gray-800 capitalize">{{ $potensi->kategori }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Lokasi</span>
                            <span class="font-semibold text-gray-800">{{ $potensi->lokasi ?? '-' }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Info Utama</span>
                            <span class="font-semibold text-gray-800">{{ $potensi->info_utama ?? '-' }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Pengelola</span>
                            <span class="font-semibold text-gray-800">{{ $potensi->nama_pengelola }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">WhatsApp</span>
                            <span class="font-semibold text-gray-800">+62 {{ $potensi->whatsapp }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span>
                                @include('admin.partials._status_badge', ['status' => $potensi->status, 'publishedAt' => $potensi->published_at])
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Views</span>
                            <span class="font-semibold text-gray-800"><i class="far fa-eye mr-1"></i>{{ number_format($potensi->views ?? 0) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Tanggal Dibuat</span>
                            <span class="text-gray-800 font-medium">{{ optional($potensi->created_at)->format('d M Y H:i') ?? '-' }} WIB</span>
                        </div>
                    </div>

                    @if($potensi->link_maps)
                        <div class="border-t border-gray-100 pt-4">
                            <a href="{{ $potensi->link_maps }}" target="_blank"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition text-sm font-semibold">
                                <i class="fas fa-map-marked-alt mr-2 text-primary-500"></i> Buka Google Maps
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
