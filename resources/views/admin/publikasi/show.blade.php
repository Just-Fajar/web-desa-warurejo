@extends('admin.layouts.app')

@section('title', 'Detail Publikasi - ' . $publikasi->judul)

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Publikasi</h1>
                <p class="text-sm text-gray-600 mt-1">Preview postingan dokumen publikasi desa</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.publikasi.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('admin.publikasi.edit', $publikasi->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Publikasi
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $publikasi->judul }}</h2>

                    @if($publikasi->thumbnail)
                        <div class="rounded-2xl overflow-hidden border border-gray-100 max-w-md">
                            <img src="{{ $publikasi->thumbnail_url }}" alt="{{ $publikasi->judul }}" class="w-full object-cover max-h-[300px]">
                        </div>
                    @endif

                    @if($publikasi->deskripsi)
                        <div class="prose max-w-none text-gray-800">
                            <p>{{ $publikasi->deskripsi }}</p>
                        </div>
                    @endif

                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="font-bold text-gray-900 text-lg mb-4">File Dokumen</h3>
                        <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-2xl">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-file-pdf text-red-500 text-3xl"></i>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ basename($publikasi->file_dokumen) }}</p>
                                    <p class="text-xs text-gray-500">PDF Document</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ $publikasi->file_url }}" target="_blank"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition text-sm font-semibold">
                                    <i class="fas fa-eye mr-2"></i> Lihat PDF
                                </a>
                                <a href="{{ route('publikasi.download', $publikasi->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition text-sm font-semibold">
                                    <i class="fas fa-download mr-2"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meta Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <h3 class="font-bold text-gray-900 text-lg border-b pb-3 border-gray-100">Informasi Detail</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kategori</span>
                            <span class="font-semibold text-gray-800 capitalize">{{ $publikasi->kategori }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Tahun</span>
                            <span class="font-semibold text-gray-800">{{ $publikasi->tahun }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span>
                                @include('admin.partials._status_badge', ['status' => $publikasi->status, 'publishedAt' => $publikasi->published_at])
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Total Download</span>
                            <span class="font-semibold text-gray-800"><i class="fas fa-download mr-1"></i>{{ number_format($publikasi->jumlah_download ?? 0) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Tanggal Dokumen</span>
                            <span class="text-gray-800 font-semibold">{{ $publikasi->tanggal_publikasi }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Tanggal Diunggah</span>
                            <span class="text-gray-800 font-medium">{{ optional($publikasi->created_at)->format('d M Y H:i') ?? '-' }} WIB</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
