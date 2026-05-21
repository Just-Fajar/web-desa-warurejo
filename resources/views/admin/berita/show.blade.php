@extends('admin.layouts.app')

@section('title', 'Detail Berita - ' . $berita->judul)

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Berita</h1>
                <p class="text-sm text-gray-600 mt-1">Preview postingan berita desa</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.berita.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('admin.berita.edit', $berita->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Berita
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $berita->judul }}</h2>

                    @if($berita->gambar_utama)
                        <div class="rounded-2xl overflow-hidden border border-gray-100">
                            <img src="{{ $berita->gambar_utama_url }}" alt="{{ $berita->judul }}" class="w-full object-cover max-h-[400px]">
                        </div>
                    @endif

                    @if($berita->ringkasan)
                        <div class="bg-slate-50 border-l-4 border-slate-300 p-4 rounded-r-xl">
                            <p class="text-gray-700 font-medium text-sm italic">"{{ $berita->ringkasan }}"</p>
                        </div>
                    @endif

                    <div class="prose max-w-none text-gray-800">
                        {!! $berita->konten !!}
                    </div>
                </div>
            </div>

            <!-- Meta Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <h3 class="font-bold text-gray-900 text-lg border-b pb-3 border-gray-100">Metadata</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span>
                                @include('admin.partials._status_badge', ['status' => $berita->status, 'publishedAt' => $berita->published_at])
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Penulis</span>
                            <span class="font-semibold text-gray-800">{{ $berita->admin->name ?? 'Unknown' }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Pembaca (Views)</span>
                            <span class="font-semibold text-gray-800"><i class="far fa-eye mr-1"></i>{{ number_format($berita->views ?? 0) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Tanggal Dibuat</span>
                            <span class="text-gray-800 font-medium">{{ optional($berita->created_at)->format('d M Y H:i') ?? '-' }} WIB</span>
                        </div>

                        @if($berita->published_at)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tanggal Terbit</span>
                                <span class="text-gray-800 font-medium">{{ optional($berita->published_at)->format('d M Y H:i') ?? '-' }} WIB</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
