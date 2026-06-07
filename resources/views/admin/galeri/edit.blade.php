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

        <form action="{{ route('admin.galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data" id="galeriForm">
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
                        
                        <!-- Upload Foto Baru -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                Upload Foto Baru <span class="text-xs font-normal text-gray-500">(Bisa pilih banyak foto untuk ditambahkan)</span>
                            </label>

                            <div id="multiUploadArea"
                                class="border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center bg-gray-50 hover:border-primary-500 transition cursor-pointer relative">

                                <input type="file" id="multiGambar" name="images[]" accept="image/*" multiple
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                                <!-- Placeholder -->
                                <div id="multiUploadPlaceholder">
                                    <div class="text-gray-400 mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto opacity-50 text-primary-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="font-semibold text-gray-700 text-sm">Klik atau Tarik Gambar untuk Ditambahkan</p>
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, WEBP (Max 2MB per file)</p>
                                </div>
                            </div>

                            <!-- Preview Grid untuk Foto Baru -->
                            <div id="previewGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-4" style="display: none;"></div>

                            @error('images')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            @error('images.*')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto Galeri Saat Ini -->
                        <div class="pt-6 border-t border-gray-100">
                            <label class="block text-sm font-bold text-gray-900 mb-3">
                                Foto Galeri Saat Ini
                            </label>
                            
                            @if($galeri->images->count() > 0)
                                <div id="existingGaleri" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    @foreach ($galeri->images as $index => $image)
                                        <div id="foto-{{ $image->id }}" class="relative group aspect-square rounded-2xl overflow-hidden border border-gray-200 bg-gray-50 shadow-sm">
                                            <img src="{{ $image->image_url }}" class="w-full h-full object-cover">
                                            
                                            <!-- Hover actions -->
                                            <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button type="button" class="hapus-foto-btn p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all" data-id="{{ $image->id }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="absolute bottom-0 left-0 bg-black/60 text-white text-xs px-2 py-1 m-2 rounded">
                                                Foto {{ $index + 1 }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div id="existingGaleri" class="text-center py-6 text-gray-500 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                    <p class="text-sm">Belum ada foto di galeri ini.</p>
                                </div>
                            @endif
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
            $(document).ready(function () {
                flatpickr("#tanggal", {
                    altInput: true,
                    altFormat: "j F Y",
                    dateFormat: "Y-m-d",
                    locale: "id"
                });

                // Hapus Foto Galeri handler via AJAX
                $(document).on('click', '.hapus-foto-btn', function() {
                    const fotoId = $(this).data('id');
                    if (!confirm('Hapus foto ini dari galeri?')) return;
                    $.ajax({
                        url: '/admin/galeri/foto/' + fotoId,
                        type: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(response) {
                            if (response.success) {
                                $('#foto-' + fotoId).fadeOut(300, function() {
                                    $(this).remove();
                                    // Check if existing gallery is now empty to show placeholder
                                    if ($('#existingGaleri > div').length === 0) {
                                        $('#existingGaleri').replaceWith(`
                                            <div id="existingGaleri" class="text-center py-6 text-gray-500 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                                <p class="text-sm">Belum ada foto di galeri ini.</p>
                                            </div>
                                        `);
                                    }
                                });
                            }
                        },
                        error: function() { alert('Gagal menghapus foto'); }
                    });
                });

                let selectedFiles = [];

                // MULTIPLE UPLOAD HANDLER
                document.getElementById('multiGambar').addEventListener('change', function (event) {
                    const files = Array.from(event.target.files);

                    files.forEach(file => {
                        if (file.size > 2048000) {
                            alert(`File ${file.name} terlalu besar (max 2MB)`);
                            return;
                        }

                        if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
                            selectedFiles.push(file);
                        }
                    });

                    updatePreviewGrid();
                    updateFileInput();
                });

                function updatePreviewGrid() {
                    const previewGrid = document.getElementById('previewGrid');
                    const placeholder = document.getElementById('multiUploadPlaceholder');

                    if (selectedFiles.length > 0) {
                        previewGrid.style.display = 'grid';
                        placeholder.style.display = 'none';

                        previewGrid.innerHTML = '';

                        selectedFiles.forEach((file, index) => {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                const col = document.createElement('div');
                                col.className = 'relative group aspect-square rounded-2xl overflow-hidden border border-gray-200 bg-gray-50 shadow-sm';
                                col.innerHTML = `
                                    <img src="${e.target.result}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button" 
                                                data-index="${index}" 
                                                class="remove-img-btn p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="absolute bottom-0 left-0 bg-primary-600 text-white text-xs px-2 py-1 m-2 rounded">
                                        Baru ${index + 1}
                                    </div>
                                `;
                                previewGrid.appendChild(col);
                            };
                            reader.readAsDataURL(file);
                        });
                    } else {
                        previewGrid.style.display = 'none';
                        placeholder.style.display = 'block';
                    }
                }

                // Event delegation to remove images without inline onclick handlers
                const previewGridEl = document.getElementById('previewGrid');
                if (previewGridEl) {
                    previewGridEl.addEventListener('click', function(e) {
                        const button = e.target.closest('.remove-img-btn');
                        if (button) {
                            const index = parseInt(button.getAttribute('data-index'));
                            removeImage(index);
                        }
                    });
                }

                function removeImage(index) {
                    selectedFiles.splice(index, 1);
                    updatePreviewGrid();
                    updateFileInput();
                }

                function updateFileInput() {
                    const input = document.getElementById('multiGambar');
                    const dataTransfer = new DataTransfer();

                    selectedFiles.forEach(file => {
                        dataTransfer.items.add(file);
                    });

                    input.files = dataTransfer.files;
                }

                // Form validation on submit
                document.getElementById('galeriForm').addEventListener('submit', function (e) {
                    const existingPhotosCount = $('#existingGaleri [id^="foto-"]').length;
                    if (existingPhotosCount === 0 && selectedFiles.length === 0) {
                        e.preventDefault();
                        alert('Silakan pilih minimal 1 gambar!');
                        return false;
                    }
                });

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
            });
        </script>
    @endpush

@endsection