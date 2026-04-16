@extends('admin.layouts.app')

@section('title', 'Edit Berita')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Berita</h1>
            <p class="text-sm text-gray-600 mt-1">Perbarui informasi berita</p>
        </div>
        <a href="{{ route('admin.berita.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data" id="beritaForm">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Konten Berita -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-primary-600 mb-4">Konten Berita</h2>
                    
                    <!-- Judul -->
                    <div class="mb-4">
                        <label for="judul" class="block text-sm font-bold text-gray-700 mb-2">
                            Judul Berita <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul', $berita->judul) }}"
                               class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('judul') border-red-300 ring-red-100 @enderror"
                               required>
                        @error('judul') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-4">
                        <label for="slug" class="block text-sm font-bold text-gray-700 mb-2">
                            Slug <span class="text-xs text-gray-500 font-normal">(Otomatis)</span>
                        </label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $berita->slug) }}"
                               class="w-full px-5 py-3 bg-gray-100/70 border border-gray-100 rounded-xl text-gray-500 text-sm font-medium focus:outline-none cursor-not-allowed"
                               readonly>
                        @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Ringkasan -->
                    <div class="mb-4">
                        <label for="ringkasan" class="block text-sm font-bold text-gray-700 mb-2">
                            Ringkasan/Excerpt
                        </label>
                        <textarea name="ringkasan" id="ringkasan" rows="3"
                                  class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('ringkasan') border-red-300 ring-red-100 @enderror">{{ old('ringkasan', $berita->ringkasan) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500"><span id="ringkasanCount">{{ strlen(old('ringkasan', $berita->ringkasan ?? '')) }}</span>/500 karakter</p>
                        @error('ringkasan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Konten -->
                    <div class="mb-4">
                        <label for="konten" class="block text-sm font-bold text-gray-700 mb-2">
                            Konten Berita <span class="text-red-500">*</span>
                        </label>
                        <textarea name="konten" id="konten" rows="10"
                                  class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('konten') border-red-300 @enderror">{{ old('konten', $berita->konten) }}</textarea>
                        @error('konten') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Informasi Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-primary-600 mb-4">Informasi</h2>
                    <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                            <span class="text-gray-500 font-medium">Dibuat:</span> 
                            <span class="text-gray-800">{{ $berita->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                            <span class="text-gray-500 font-medium">Diupdate:</span> 
                            <span class="text-gray-800">{{ $berita->updated_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                            <span class="text-gray-500 font-medium">Views:</span> 
                            <span class="text-gray-800">{{ number_format($berita->views ?? 0) }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                            <span class="text-gray-500 font-medium">Penulis:</span> 
                            <span class="text-gray-800">{{ $berita->admin->name ?? 'Desa Warurejo' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                
                <!-- Media Upload -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-primary-600 mb-4">Media</h2>

                    <div class="border-2 border-dashed rounded-lg p-4 text-center relative hover:border-primary-400 transition cursor-pointer">
                        <input type="file" id="gambar_utama" name="gambar_utama" accept="image/*" onchange="previewImage(event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                        <!-- Placeholder -->
                        <div id="uploadPlaceholder" class="{{ $berita->gambar_utama ? 'hidden' : '' }}">
                            <svg class="w-12 h-12 mx-auto mb-3 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M28 8H12a4 4 0 00-4 4v20m32-12v8M8 32l9.172-9.172a4 4 0 015.656 0L28 28l4 4m4-24h8m-4-4v8m-12 4h.02"/>
                            </svg>
                            <h3 class="font-medium text-gray-700">Upload / Ganti Gambar</h3>
                            <p class="text-xs text-gray-500">Format JPG, PNG, WEBP — Max 2MB</p>
                        </div>

                        <!-- Preview -->
                        <div id="previewContainer" class="{{ $berita->gambar_utama ? '' : 'hidden' }}">
                            <img id="imagePreview" src="{{ $berita->gambar_utama ? $berita->gambar_utama_url : '' }}" class="rounded-lg shadow w-full object-cover max-h-60">
                            <p class="text-xs text-gray-500 mt-2 italic">Klik area untuk mengganti gambar</p>
                        </div>
                    </div>
                    @error('gambar_utama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Pengaturan -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-primary-600 mb-4">Pengaturan</h2>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-bold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium" required>
                            <option value="published" {{ old('status', $berita->status) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tanggal Publikasi -->
                    <div class="mb-4">
                        <label for="published_at" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Publikasi</label>
                        <input type="text" name="published_at" id="published_at" value="{{ old('published_at', $berita->published_at ? $berita->published_at->format('Y-m-d H:i') : '') }}" placeholder="Pilih tanggal dan waktu" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium">
                        @error('published_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <hr class="my-4">
                    
                    <button type="submit" name="action" value="publish" class="w-full px-6 py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition">
                        Update Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .ck-editor__editable_inline {
        min-height: 400px;
        border-radius: 0.75rem !important;
        border-color: #f3f4f6 !important;
    }
    .ck-toolbar {
        border-top-left-radius: 0.75rem !important;
        border-top-right-radius: 0.75rem !important;
        background-color: #f9fafb !important;
        border-color: #f3f4f6 !important;
    }
</style>
@endpush

@push('scripts')
<!-- Flatpickr for Modern DatePicker -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
<script>
    // Initialize Flatpickr
    flatpickr("#published_at", {
        enableTime: true,
        altInput: true,
        altFormat: "j F Y, H:i",
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        locale: "id",
        placeholder: "Pilih tanggal dipublikasikan"
    });

    // Initialize CKEditor
    let editorInstance;
    ClassicEditor
        .create(document.querySelector('#konten'), {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ]
            },
            language: 'id',
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            }
        })
        .then(editor => {
            editorInstance = editor;
            editor.ui.view.editable.element.style.minHeight = '500px';
        })
        .catch(error => {
            console.error(error);
        });

    // Auto-generate slug from judul
    document.getElementById('judul').addEventListener('input', function() {
        const judul = this.value;
        const slug = judul
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        document.getElementById('slug').value = slug;
    });

    // Character count for ringkasan
    const ringkasanTextarea = document.getElementById('ringkasan');
    const ringkasanCount = document.getElementById('ringkasanCount');
    
    ringkasanTextarea.addEventListener('input', function() {
        const length = this.value.length;
        ringkasanCount.textContent = length;
        
        if (length > 500) {
            ringkasanCount.classList.add('text-red-600');
        } else {
            ringkasanCount.classList.remove('text-red-600');
        }
    });

    // Image preview
    function previewImage(event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('uploadPlaceholder').classList.add('hidden');
                document.getElementById('previewContainer').classList.remove('hidden');
            };
            
            reader.readAsDataURL(file);
        }
    }

    // Form submission with action buttons
    document.querySelectorAll('button[name="action"]').forEach(button => {
        button.addEventListener('click', function(e) {
            const action = this.value;
            const statusSelect = document.getElementById('status');
            
            if (action === 'publish') {
                statusSelect.value = 'published';
            }
        });
    });

    // Client-side validation
    document.getElementById('beritaForm').addEventListener('submit', function(e) {
        const judul = document.getElementById('judul').value.trim();
        const konten = editorInstance ? editorInstance.getData() : '';
        
        if (!judul) {
            e.preventDefault();
            alert('Judul berita wajib diisi!');
            document.getElementById('judul').focus();
            return false;
        }
        
        if (!konten || konten.trim() === '') {
            e.preventDefault();
            alert('Konten berita wajib diisi!');
            editorInstance.focus();
            return false;
        }
    });
</script>
@endpush
@endsection
