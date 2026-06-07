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

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 space-y-6">
                    <!-- Judul -->
                    <div>
                        <label for="judul" class="block text-sm font-bold text-gray-900 mb-2">
                            Judul Dokumen <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul', $publikasi->judul) }}"
                            class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('judul') border-red-300 ring-red-100 @enderror"
                            placeholder="Contoh: Anggaran Pendapatan dan Belanja Desa Tahun 2025" required>
                        @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Slug (Auto-generated) -->
                    <div>
                        <label for="slug" class="block text-sm font-bold text-gray-900 mb-2">
                            Slug <span class="text-xs text-gray-500 font-normal">(Otomatis dibuatkan dari judul)</span>
                        </label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', Str::slug($publikasi->judul)) }}"
                            class="w-full px-5 py-3 bg-gray-50 border border-gray-300 shadow-sm rounded-xl text-gray-500 text-sm font-medium focus:outline-none cursor-not-allowed @error('slug') border-red-300 @enderror"
                            readonly>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori & Tahun -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="kategori" class="block text-sm font-bold text-gray-900 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="kategori" name="kategori" required
                                class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('kategori') border-red-300 ring-red-100 @enderror">
                                <option value="">Pilih Kategori</option>
                                <option value="APBDes" {{ old('kategori', $publikasi->kategori) == 'APBDes' ? 'selected' : '' }}>APBDes</option>
                                <option value="RPJMDes" {{ old('kategori', $publikasi->kategori) == 'RPJMDes' ? 'selected' : '' }}>RPJMDes</option>
                                <option value="RKPDes" {{ old('kategori', $publikasi->kategori) == 'RKPDes' ? 'selected' : '' }}>RKPDes</option>
                            </select>
                            @error('kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="tahun" class="block text-sm font-bold text-gray-900 mb-2">
                                Tahun <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $publikasi->tahun) }}"
                                min="2000" max="{{ date('Y') + 5 }}" required
                                class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('tahun') border-red-300 ring-red-100 @enderror">
                            @error('tahun') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-bold text-gray-900 mb-2">
                            Deskripsi
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                            class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('deskripsi') border-red-300 ring-red-100 @enderror"
                            placeholder="Deskripsi singkat tentang dokumen ini...">{{ old('deskripsi', $publikasi->deskripsi) }}</textarea>
                        @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Media Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- File Dokumen -->
                        <div>
                            <label for="file_dokumen" class="block text-sm font-bold text-gray-900 mb-2">
                                File Dokumen (PDF) <span class="text-red-500">*</span>
                            </label>

                            @if($publikasi->file_dokumen)
                                <div class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-xl mb-3">
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
                                class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-primary-500 transition relative cursor-pointer flex flex-col justify-center w-full aspect-video overflow-hidden bg-gray-50">
                                <input type="file" id="file_dokumen" name="file_dokumen" accept=".pdf"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <i class="fas fa-file-pdf text-4xl text-gray-400 mb-3"></i>
                                <p class="text-primary-600 hover:text-primary-700 font-semibold text-sm">Pilih File PDF Baru
                                </p>
                                <p class="text-xs text-gray-500 mt-2">Maksimal 10MB (Kosongkan jika tidak ubah)</p>
                                <p id="file-name" class="text-sm text-gray-700 font-semibold mt-2"></p>
                            </div>
                            @error('file_dokumen') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Thumbnail -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-3">
                                Thumbnail (Opsional)
                            </label>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Thumbnail Saat Ini (Sebelum Edit) -->
                                <div>
                                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Gambar Saat Ini (Sebelum Edit)</span>
                                    <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50 w-full aspect-video flex items-center justify-center relative shadow-sm">
                                        @if($publikasi->thumbnail)
                                            <img src="{{ $publikasi->thumbnail_url }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="text-center p-6 text-gray-400">
                                                <i class="fas fa-image text-3xl mb-1"></i>
                                                <p class="text-xs">Tidak ada gambar saat ini</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Thumbnail Baru (Setelah Edit) -->
                                <div>
                                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Gambar Baru (Setelah Edit)</span>
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center relative hover:border-primary-400 transition cursor-pointer flex flex-col justify-center w-full aspect-video overflow-hidden bg-gray-50 shadow-sm">
                                        <input type="file" id="thumbnail" name="thumbnail"
                                            accept="image/jpeg,image/png,image/jpg,image/webp"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                        <!-- Placeholder -->
                                        <div id="thumbnailPlaceholder">
                                            <i class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                                            <p class="text-primary-600 hover:text-primary-700 font-semibold text-xs">Pilih Gambar Baru</p>
                                            <p class="text-[10px] text-gray-500 mt-1">Format JPG, PNG, WEBP — Max 2MB</p>
                                        </div>

                                        <!-- Preview -->
                                        <div id="thumbnailPreviewContainer" class="hidden absolute inset-0 w-full h-full p-2 bg-gray-50">
                                            <img id="thumbnailPreview" src=""
                                                class="rounded-lg shadow w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center opacity-0 hover:opacity-100 transition-opacity rounded-xl">
                                                <p class="text-white text-xs font-medium mb-1">Ubah Gambar Baru</p>
                                                <button type="button" id="btnBatalGantiThumbnail" class="px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white text-[10px] font-bold rounded transition-all focus:outline-none z-20">
                                                    Batal Ganti
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('thumbnail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <!-- Pengaturan Publikasi -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-primary-600 mb-4">Pengaturan Publikasi</h3>
                        <div class="space-y-6">
                            <!-- Tanggal Dokumen -->
                            <div>
                                <label for="tanggal_publikasi" class="block text-sm font-bold text-gray-900 mb-2">
                                    Tanggal Dokumen <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="tanggal_publikasi" name="tanggal_publikasi"
                                    value="{{ old('tanggal_publikasi', $publikasi->tanggal_publikasi) }}"
                                    placeholder="Pilih tanggal" required
                                    class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('tanggal_publikasi') border-red-300 ring-red-100 @enderror">
                                @error('tanggal_publikasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Status & Scheduling -->
                            @include('admin.partials._status_fields', [
                                'currentStatus' => old('status', $publikasi->status),
                                'publishedAt' => old('published_at', $publikasi->published_at ? $publikasi->published_at->format('Y-m-d H:i') : ''),
                            ])
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Tambahan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div>
                                <p class="text-gray-500 font-medium mb-1">Dibuat</p>
                                <p class="text-gray-800 font-semibold">{{ \Carbon\Carbon::parse($publikasi->created_at)->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 font-medium mb-1">Diupdate</p>
                                <p class="text-gray-800 font-semibold">{{ \Carbon\Carbon::parse($publikasi->updated_at)->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 font-medium mb-1">Pengunggah</p>
                                <p class="text-gray-800 font-semibold">{{ $publikasi->admin->name ?? 'Desa Warurejo' }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Form Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                    <a href="{{ route('admin.publikasi.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition font-medium text-sm">
                        Batal
                    </a>
                    <button type="submit" id="submitBtn"
                        class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-semibold flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span id="submitBtnText" data-module="Publikasi">Update Publikasi</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush

    @push('scripts')
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

                if (input && display && input.files.length > 0) {
                    const fileName = input.files[0].name;
                    const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
                    display.textContent = `${fileName} (${fileSize} MB)`;
                } else if (display) {
                    display.textContent = '';
                }
            }

            const fileDokumenInput = document.getElementById('file_dokumen');
            if (fileDokumenInput) {
                fileDokumenInput.addEventListener('change', function() {
                    displayFileName('file_dokumen', 'file-name');
                });
            }

             const thumbnailInput = document.getElementById('thumbnail');
            if (thumbnailInput) {
                thumbnailInput.addEventListener('change', function(event) {
                    const reader = new FileReader();
                    reader.onload = function () {
                        const output = document.getElementById('thumbnailPreview');
                        const container = document.getElementById('thumbnailPreviewContainer');
                        const placeholder = document.getElementById('thumbnailPlaceholder');

                        if (output) output.src = reader.result;
                        if (container) container.classList.remove('hidden');
                        if (placeholder) placeholder.classList.add('hidden');
                    };
                    if (event.target.files[0]) {
                        reader.readAsDataURL(event.target.files[0]);
                    }
                });
            }

            const btnBatalGantiThumbnail = document.getElementById('btnBatalGantiThumbnail');
            if (btnBatalGantiThumbnail) {
                btnBatalGantiThumbnail.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    document.getElementById('thumbnail').value = '';
                    document.getElementById('thumbnailPlaceholder').classList.remove('hidden');
                    document.getElementById('thumbnailPreviewContainer').classList.add('hidden');
                });
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