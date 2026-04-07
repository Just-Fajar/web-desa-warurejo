@extends('admin.layouts.app')

@section('title', 'Tambah Berita')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Berita Baru</h1>
            <p class="text-sm text-gray-600 mt-1">Buat berita atau artikel baru</p>
        </div>
        <a href="{{ route('admin.berita.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" id="beritaForm">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Judul -->
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Berita <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="judul" 
                           id="judul" 
                           value="{{ old('judul') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('judul')  @enderror"
                           placeholder="Masukkan judul berita"
                           required>
                    @error('judul')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug (Auto-generated) -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                        Slug <span class="text-xs text-gray-500">(Otomatis dibuatkan dari judul)</span>
                    </label>
                    <input type="text" 
                           name="slug" 
                           id="slug" 
                           value="{{ old('slug') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('slug')  @enderror"
                           placeholder="slug-otomatis"
                           readonly>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ringkasan -->
                <div>
                    <label for="ringkasan" class="block text-sm font-medium text-gray-700 mb-2">
                        Ringkasan/Excerpt
                    </label>
                    <textarea name="ringkasan" 
                              id="ringkasan" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('ringkasan')  @enderror"
                              placeholder="Ringkasan singkat berita (opsional, max 500 karakter)">{{ old('ringkasan') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">
                        <span id="ringkasanCount">0</span>/500 karakter
                    </p>
                    @error('ringkasan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gambar Utama -->
                <div>
                    <label for="gambar_utama" class="block text-sm font-medium text-gray-700 mb-2">
                        Gambar Utama
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary-400 transition">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="gambar_utama" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                    <span>Upload file</span>
                                    <input id="gambar_utama" 
                                           name="gambar_utama" 
                                           type="file" 
                                           class="sr-only" 
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB</p>
                        </div>
                    </div>
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" class="hidden mt-4">
                        <div class="relative inline-block">
                            <img id="preview" src="" alt="Preview" class="h-48 rounded-lg shadow-md">
                            <button type="button" 
                                    onclick="removeImage()"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @error('gambar_utama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konten (TinyMCE) -->
                <div>
                    <label for="konten" class="block text-sm font-medium text-gray-700 mb-2">
                        Konten Berita <span class="text-red-500">*</span>
                    </label>
                    <textarea name="konten" 
                            id="konten"
                            rows="10"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('konten') @enderror">{{ old('konten') }}</textarea>

                    @error('konten')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Row: Status & Tanggal Publikasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" 
                                id="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('status')  @enderror"
                                required>
                            {{-- <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option> --}}
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }} selected>Published</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Publikasi -->
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Publikasi <span class="text-xs text-gray-500">(Opsional)</span>
                        </label>
                        <input type="datetime-local" 
                               name="published_at" 
                               id="published_at" 
                               value="{{ old('published_at') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('published_at')  @enderror">
                        @error('published_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Kosongkan untuk menggunakan waktu saat ini</p>
                    </div>
                </div>

            </div>

            <!-- Form Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <a href="{{ route('admin.berita.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                    Batal
                </a>
                <div class="flex gap-2">
                    {{-- <button type="submit" 
                            name="action" 
                            value="draft"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                        Simpan sebagai Draft
                    </button> --}}
                    <button type="submit" 
                            name="action" 
                            value="publish"
                            class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                        Publish Berita
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
<script>
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

    // Update initial count
    ringkasanCount.textContent = ringkasanTextarea.value.length;

    // Image preview
    function previewImage(input) {
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Remove image
    function removeImage() {
        document.getElementById('gambar_utama').value = '';
        document.getElementById('imagePreview').classList.add('hidden');
        document.getElementById('preview').src = '';
    }

    // Form submission with action buttons
    document.querySelectorAll('button[name="action"]').forEach(button => {
        button.addEventListener('click', function(e) {
            const action = this.value;
            const statusSelect = document.getElementById('status');
            
            // if (action === 'draft') {
            //     statusSelect.value = 'draft';
            // } else
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

<script>
document.getElementById('gambar').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('imagePreview');
            img.src = e.target.result;
            img.style.display = 'block';

            document.getElementById('uploadPlaceholder').classList.add('d-none');
            document.getElementById('previewContainer').classList.remove('d-none');
        }
        reader.readAsDataURL(file);
    }
});

function resetUpload() {
    const img = document.getElementById('imagePreview');
    img.src = '';
    img.style.display = 'none';

    document.getElementById('gambar').value = '';
    document.getElementById('uploadPlaceholder').classList.remove('d-none');
    document.getElementById('previewContainer').classList.add('d-none');
}
</script>

@endpush
@endsection
