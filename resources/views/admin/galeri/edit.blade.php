@extends('admin.layouts.app')

@section('title', 'Edit Galeri')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Galeri</h1>
                <p class="text-sm text-gray-600 mt-1">Perbarui informasi galeri desa</p>
            </div>

            <a href="{{ route('admin.galeri.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 space-y-6">
                    <!-- Judul -->
                    <div>
                        <label for="judul" class="block text-sm font-bold text-gray-900 mb-2">
                            Judul Galeri <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul', $galeri->judul) }}"
                            class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('judul') border-red-300 ring-red-100 @enderror"
                            required>
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug (Auto-generated) -->
                    <div>
                        <label for="slug" class="block text-sm font-bold text-gray-900 mb-2">
                            Slug <span class="text-xs text-gray-500 font-normal">(Otomatis dibuatkan dari judul)</span>
                        </label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', Str::slug($galeri->judul)) }}"
                            class="w-full px-5 py-3 bg-gray-50 border border-gray-300 shadow-sm rounded-xl text-gray-500 text-sm font-medium focus:outline-none cursor-not-allowed @error('slug') border-red-300 @enderror"
                            readonly>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-bold text-gray-900 mb-2">
                            Deskripsi
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="6"
                            class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('deskripsi') border-red-300 ring-red-100 @enderror">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pengaturan Konten -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kategori -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori"
                                class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('kategori') border-red-300 ring-red-100 @enderror"
                                required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="kegiatan" {{ old('kategori', $galeri->kategori) == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                                <option value="pembangunan" {{ old('kategori', $galeri->kategori) == 'pembangunan' ? 'selected' : '' }}>Pembangunan</option>
                                <option value="budaya" {{ old('kategori', $galeri->kategori) == 'budaya' ? 'selected' : '' }}>Budaya</option>
                                <option value="keagamaan" {{ old('kategori', $galeri->kategori) == 'keagamaan' ? 'selected' : '' }}>Keagamaan</option>
                                <option value="sosial" {{ old('kategori', $galeri->kategori) == 'sosial' ? 'selected' : '' }}>Sosial</option>
                                <option value="lainnya" {{ old('kategori', $galeri->kategori) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <!-- Tanggal -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Tanggal Kejadian</label>
                            <input type="text" id="tanggal" name="tanggal"
                                value="{{ old('tanggal', $galeri->tanggal ? $galeri->tanggal->format('Y-m-d') : '') }}"
                                placeholder="Pilih tanggal"
                                class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('tanggal') border-red-300 ring-red-100 @enderror"
                                required>
                        </div>
                    </div>

                    <!-- Media Upload -->
                    <div class="pt-6 border-t border-gray-200 mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Media</h3>
                        <div>
                            <label for="gambar" class="block text-sm font-bold text-gray-900 mb-2">Gambar Galeri</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center relative hover:border-primary-500 transition cursor-pointer flex flex-col justify-center min-h-[12rem]">
                                <input type="file" id="gambar" name="gambar" accept="image/*" onchange="previewImage(event)"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                <!-- Placeholder -->
                                <div id="uploadPlaceholder" class="{{ $galeri->gambar ? 'hidden' : '' }}">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M28 8H12a4 4 0 00-4 4v20m32-12v8M8 32l9.172-9.172a4 4 0 015.656 0L28 28l4 4m4-24h8m-4-4v8m-12 4h.02" />
                                    </svg>
                                    <h3 class="font-medium text-primary-600 hover:text-primary-700 text-sm">Upload / Ganti Gambar</h3>
                                    <p class="text-xs text-gray-500 mt-2">Format JPG, PNG, WEBP — Max 2MB</p>
                                </div>

                                <!-- Preview -->
                                <div id="previewContainer" class="{{ $galeri->gambar ? '' : 'hidden' }} absolute inset-0 w-full h-full p-2">
                                    <img id="imagePreview"
                                        src="{{ $galeri->gambar ? asset('storage/' . $galeri->gambar) : '' }}"
                                        class="rounded-lg shadow w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity rounded-xl">
                                        <p class="text-white text-sm font-medium">Ubah Gambar</p>
                                    </div>
                                </div>
                            </div>
                            @error('gambar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Tambahan</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div>
                                <p class="text-gray-500 font-medium mb-1">Dibuat</p>
                                <p class="text-gray-800 font-semibold">{{ \Carbon\Carbon::parse($galeri->created_at)->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 font-medium mb-1">Diupdate</p>
                                <p class="text-gray-800 font-semibold">{{ \Carbon\Carbon::parse($galeri->updated_at)->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 font-medium mb-1">Views</p>
                                <p class="text-gray-800 font-semibold">{{ number_format($galeri->views ?? 0) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 font-medium mb-1">Penulis</p>
                                <p class="text-gray-800 font-semibold">{{ $galeri->admin->name ?? 'Admin Desa' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pengaturan Publikasi -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-primary-600 mb-4">Pengaturan Publikasi</h3>
                        <div class="space-y-6">
                            @include('admin.partials._status_fields', [
                                'currentStatus' => old('status', $galeri->status),
                                'publishedAt' => old('published_at', $galeri->published_at ? $galeri->published_at->format('Y-m-d H:i') : ''),
                            ])
                        </div>
                    </div>

                </div>

                <!-- Form Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                    <a href="{{ route('admin.galeri.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition font-medium text-sm">
                        Batal
                    </a>
                    <button type="submit" id="submitBtn"
                        class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-semibold flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span id="submitBtnText" data-module="Galeri">Update Galeri</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
        <script>
            flatpickr("#tanggal", {
                altInput: true,
                altFormat: "j F Y",
                dateFormat: "Y-m-d",
                locale: "id"
            });

            function previewImage(event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('imagePreview').src = e.target.result;
                        document.getElementById('uploadPlaceholder').classList.add('hidden');
                        document.getElementById('previewContainer').classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            }

            // Auto-generate slug from judul
            document.getElementById('judul').addEventListener('input', function () {
                const judul = this.value;
                const slug = judul
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
                document.getElementById('slug').value = slug;
            });
        </script>
    @endpush

@endsection