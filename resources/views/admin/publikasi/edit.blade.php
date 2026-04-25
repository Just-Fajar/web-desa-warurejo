@extends('admin.layouts.app')

@section('title', 'Edit Publikasi')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Publikasi</h1>
                <p class="text-sm text-gray-500 mt-1">Perbarui dokumen publikasi desa</p>
            </div>
            <a href="{{ route('admin.publikasi.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-primary-600 transition-all shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.publikasi.update', $publikasi->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Konten Publikasi -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-primary-600 mb-4">Konten Publikasi</h2>

                        <!-- Judul -->
                        <div class="mb-4">
                            <label for="judul" class="block text-sm font-bold text-gray-700 mb-2">
                                Judul Dokumen <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="judul" name="judul" value="{{ old('judul', $publikasi->judul) }}"
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('judul') border-red-300 ring-red-100 @enderror"
                                placeholder="Contoh: Anggaran Pendapatan dan Belanja Desa Tahun 2025" required>
                            @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kategori & Tahun -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="kategori" class="block text-sm font-bold text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select id="kategori" name="kategori" required
                                    class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('kategori') border-red-300 ring-red-100 @enderror">
                                    <option value="">Pilih Kategori</option>
                                    <option value="APBDes" {{ old('kategori', $publikasi->kategori) == 'APBDes' ? 'selected' : '' }}>APBDes</option>
                                    <option value="RPJMDes" {{ old('kategori', $publikasi->kategori) == 'RPJMDes' ? 'selected' : '' }}>RPJMDes</option>
                                    <option value="RKPDes" {{ old('kategori', $publikasi->kategori) == 'RKPDes' ? 'selected' : '' }}>RKPDes</option>
                                </select>
                                @error('kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="tahun" class="block text-sm font-bold text-gray-700 mb-2">
                                    Tahun <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $publikasi->tahun) }}"
                                    min="2000" max="{{ date('Y') + 5 }}" required
                                    class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('tahun') border-red-300 ring-red-100 @enderror">
                                @error('tahun') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="4"
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('deskripsi') border-red-300 ring-red-100 @enderror"
                                placeholder="Deskripsi singkat tentang dokumen ini...">{{ old('deskripsi', $publikasi->deskripsi) }}</textarea>
                            @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Informasi Section -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-primary-600 mb-4">Informasi</h2>
                        <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-500 font-medium">Dibuat:</span>
                                <span
                                    class="text-gray-800">{{ \Carbon\Carbon::parse($publikasi->created_at)->format('d M Y H:i') }}</span>
                            </div>
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-500 font-medium">Diupdate:</span>
                                <span
                                    class="text-gray-800">{{ \Carbon\Carbon::parse($publikasi->updated_at)->format('d M Y H:i') }}</span>
                            </div>
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-500 font-medium">Total Download:</span>
                                <span class="text-gray-800">{{ number_format($publikasi->jumlah_download ?? 0) }}</span>
                            </div>
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-500 font-medium">Pengunggah:</span>
                                <span class="text-gray-800">{{ $publikasi->admin->name ?? 'Desa Warurejo' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Media -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-primary-600 mb-4">Media</h2>

                        <!-- File Dokumen -->
                        <div class="mb-6">
                            <label for="file_dokumen" class="block text-sm font-bold text-gray-700 mb-2">
                                File Dokumen (PDF) <span class="text-red-500">*</span>
                            </label>

                            @if($publikasi->file_dokumen)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg mb-3">
                                    <i class="fas fa-file-pdf text-red-500 text-2xl mr-3"></i>
                                    <div class="flex-1 overflow-hidden">
                                        <p class="text-sm font-semibold text-gray-700 truncate">
                                            {{ basename($publikasi->file_dokumen) }}</p>
                                        <p class="text-xs text-gray-500">File saat ini</p>
                                    </div>
                                    <a href="{{ $publikasi->file_url }}" target="_blank"
                                        class="text-primary-600 hover:text-primary-700 text-sm font-semibold whitespace-nowrap ml-2">
                                        <i class="fas fa-eye mr-1"></i> Lihat
                                    </a>
                                </div>
                            @endif

                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-500 transition relative cursor-pointer">
                                <input type="file" id="file_dokumen" name="file_dokumen" accept=".pdf"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    onchange="displayFileName('file_dokumen', 'file-name')">
                                <i class="fas fa-file-pdf text-4xl text-gray-400 mb-3"></i>
                                <p class="text-primary-600 hover:text-primary-700 font-semibold text-sm">Pilih File PDF Baru
                                </p>
                                <p class="text-xs text-gray-500 mt-2">Maksimal 10MB (Kosongkan jika tidak ubah)</p>
                                <p id="file-name" class="text-sm text-gray-700 font-semibold mt-2"></p>
                            </div>
                            @error('file_dokumen') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Thumbnail -->
                        <div class="mb-4">
                            <label for="thumbnail" class="block text-sm font-bold text-gray-700 mb-2">
                                Thumbnail (Opsional)
                            </label>

                            <div
                                class="border-2 border-dashed rounded-lg p-4 text-center relative hover:border-primary-400 transition cursor-pointer">
                                <input type="file" id="thumbnail" name="thumbnail"
                                    accept="image/jpeg,image/png,image/jpg,image/webp"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    onchange="previewThumbnail(event)">

                                <!-- Placeholder -->
                                <div id="thumbnailPlaceholder" class="{{ $publikasi->thumbnail ? 'hidden' : '' }}">
                                    <i class="fas fa-image text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-primary-600 hover:text-primary-700 font-semibold text-sm">Pilih Gambar
                                        Baru</p>
                                    <p class="text-xs text-gray-500 mt-2">Format JPG, PNG, WEBP — Max 2MB</p>
                                    <p id="thumbnail-name" class="text-sm text-gray-700 font-semibold mt-2"></p>
                                </div>

                                <!-- Preview -->
                                <div id="thumbnailPreviewContainer" class="{{ $publikasi->thumbnail ? '' : 'hidden' }}">
                                    <img id="thumbnailPreview"
                                        src="{{ $publikasi->thumbnail ? $publikasi->thumbnail_url : '' }}"
                                        class="rounded-lg shadow w-full object-cover max-h-60">
                                    <p class="text-xs text-gray-500 mt-2 italic">Klik area untuk mengganti gambar</p>
                                </div>
                            </div>
                            @error('thumbnail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Pengaturan -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-primary-600 mb-4">Pengaturan</h3>

                        <!-- Tanggal Publikasi -->
                        <div class="mb-4">
                            <label for="tanggal_publikasi" class="block text-sm font-bold text-gray-700 mb-2">
                                Tanggal Publikasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="tanggal_publikasi" name="tanggal_publikasi"
                                value="{{ old('tanggal_publikasi', $publikasi->tanggal_publikasi) }}"
                                placeholder="Pilih tanggal" required
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('tanggal_publikasi') border-red-300 ring-red-100 @enderror">
                            @error('tanggal_publikasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-bold text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" required
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('status') border-red-300 ring-red-100 @enderror">
                                <option value="published" {{ old('status', $publikasi->status) == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Submit Buttons -->
                        <button type="submit"
                            class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-3 rounded-lg font-semibold transition duration-200">
                            Update Perubahan
                        </button>

                        <div class="mt-3 text-center">
                            <a href="{{ route('admin.publikasi.index') }}"
                                class="text-sm text-gray-500 hover:text-gray-700">
                                Batal
                            </a>
                        </div>
                    </div>
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
            flatpickr("#tanggal_publikasi", {
                altInput: true,
                altFormat: "j F Y",
                dateFormat: "Y-m-d",
                locale: "id"
            });

            function displayFileName(inputId, displayId) {
                const input = document.getElementById(inputId);
                const display = document.getElementById(displayId);

                if (input.files.length > 0) {
                    const fileName = input.files[0].name;
                    const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
                    display.textContent = `${fileName} (${fileSize} MB)`;
                } else {
                    display.textContent = '';
                }
            }

            function previewThumbnail(event) {
                const reader = new FileReader();
                reader.onload = function () {
                    const output = document.getElementById('thumbnailPreview');
                    const container = document.getElementById('thumbnailPreviewContainer');
                    const placeholder = document.getElementById('thumbnailPlaceholder');

                    output.src = reader.result;
                    container.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                };
                if (event.target.files[0]) {
                    reader.readAsDataURL(event.target.files[0]);
                }
            }
        </script>
    @endpush
@endsection