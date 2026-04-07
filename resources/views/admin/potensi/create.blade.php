{{--
    ADMIN POTENSI CREATE
    
    Form tambah potensi desa (ekonomi, wisata, UMKM)
    
    FEATURES:
    - Rich form dengan TinyMCE editor
    - Image upload dengan preview
    - Kategori selection (7 kategori)
    - Auto slug generation dari nama
    - Contact info fields (telepon, WhatsApp)
    - Order priority (urutan)
    - Active/draft toggle
    
    FORM FIELDS:
    - nama: Nama potensi (auto-generate slug)
    - kategori: Dropdown (pertanian/peternakan/perikanan/umkm/wisata/kerajinan/lainnya)
    - deskripsi: Rich text editor (TinyMCE)
    - gambar: Image upload (max 5MB, JPG/PNG/WEBP)
    - lokasi: Alamat/lokasi potensi
    - kontak: Nomor telepon
    - whatsapp: Nomor WhatsApp untuk quick contact
    - urutan: Order priority (default auto-increment)
    - is_active: Boolean toggle (default true)
    
    VALIDATION:
    - Nama required, max 255 chars
    - Slug unique (auto-check)
    - Kategori must be in enum
    - Gambar max 5MB, image format
    - WhatsApp numeric only
    
    JAVASCRIPT:
    - TinyMCE initialization
    - Image preview before upload
    - Auto-format phone numbers
    - Form validation
    
    Route: POST /admin/potensi/store
    Controller: AdminPotensiController@store
--}}
@extends('admin.layouts.app')

@section('title', 'Tambah Potensi Desa')

@section('content')
<div class="container-fluid px-4"> 
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Tambah Potensi Desa</h1>
            <p class="text-muted small mb-0">Tambah informasi potensi yang dimiliki desa.</p>
        </div>

        <a href="{{ route('admin.potensi.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

{{-- Flash Message --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

    <!-- Form Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Potensi</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.potensi.store') }}" method="POST" enctype="multipart/form-data" id="potensiForm">
                @csrf

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-8">
                        <!-- Nama Potensi -->
                        <div class="mb-4">
    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
        Nama Potensi <span class="text-red-600">*</span>
    </label>

    <input type="text"
           id="nama"
           name="nama"
           value="{{ old('nama') }}"
           placeholder="Contoh: Pertanian Padi Organik"
           required
           class="w-full px-4 py-2 border rounded-lg 
                  border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                  @error('nama')  @enderror">

    @error('nama')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror

    <small class="text-gray-500 text-xs">Slug akan dibuat otomatis dari nama potensi</small>
</div>

                        <!-- Deskripsi Singkat -->
<div class="mb-4">
    <label for="deskripsi_singkat" class="block text-sm font-medium text-gray-700 mb-1">
        Deskripsi Singkat <span class="text-red-600">*</span>
    </label>

    <textarea id="deskripsi_singkat"
              name="deskripsi_singkat"
              rows="3"
              maxlength="500"
              placeholder="Ringkasan singkat tentang potensi ini (max 500 karakter)"
              required
              class="w-full px-4 py-2 border rounded-lg 
                     border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                     @error('deskripsi_singkat') @enderror">{{ old('deskripsi_singkat') }}</textarea>

    @error('deskripsi_singkat')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror

    <small class="text-gray-500 text-xs"><span id="charCount">0</span>/500 karakter</small>
</div>

<!-- Deskripsi Lengkap -->
<div class="mb-4">
    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
        Deskripsi Lengkap <span class="text-red-600">*</span>
    </label>

    <textarea id="deskripsi"
              name="deskripsi"
              rows="15"
              class="w-full px-4 py-2 border rounded-lg 
                     border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                     @error('deskripsi') @enderror">{{ old('deskripsi') }}</textarea>

    @error('deskripsi')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>


                    <!-- Right Column -->
                    <div class="col-md-4">
                       <!-- Gambar -->
<div class="mb-3">
    <label for="gambar" class="form-label" style="font-weight: 600; color: #374151;">
        Gambar <span class="text-danger">*</span>
    </label>

    <input type="file"
        class="form-control @error('gambar') is-invalid @enderror"
        id="gambar"
        name="gambar"
        accept="image/*"
        required
        style="
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            transition: all .2s;
        "
        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.3)'"
        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
    >

    @error('gambar')
        <div class="text-danger mt-1" style="font-size: 14px;">{{ $message }}</div>
    @enderror

    <small class="text-muted d-block mt-1">Format: JPG, PNG, WEBP. Max: 2MB</small>

    <!-- Image Preview -->
    <div id="imagePreview"
        class="mt-3"
        style="
            display: none;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        ">
        <img id="preview"
            src=""
            alt="Preview"
            class="img-thumbnail"
            style="max-width: 100%; border-radius: 8px;"
        >

        <button type="button" class="btn btn-sm btn-danger mt-2" id="removeImage" style="border-radius: 6px;">
            <i class="fas fa-times"></i> Hapus
        </button>
    </div>
</div>

<!-- Kategori -->
<div class="mb-3">
    <label for="kategori" class="form-label" style="font-weight: 600; color: #374151;">
        Kategori <span class="text-danger">*</span>
    </label>

    <select
        class="form-select @error('kategori') is-invalid @enderror"
        id="kategori"
        name="kategori"
        required
        style="
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            transition: all .2s;
        "
        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.3)'"
        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
    >
        <option value="">-- Pilih Kategori --</option>
        <option value="pertanian" {{ old('kategori') == 'pertanian' ? 'selected' : '' }}>Pertanian</option>
        <option value="peternakan" {{ old('kategori') == 'peternakan' ? 'selected' : '' }}>Peternakan</option>
        <option value="perikanan" {{ old('kategori') == 'perikanan' ? 'selected' : '' }}>Perikanan</option>
        <option value="umkm" {{ old('kategori') == 'umkm' ? 'selected' : '' }}>UMKM</option>
        <option value="wisata" {{ old('kategori') == 'wisata' ? 'selected' : '' }}>Wisata</option>
        <option value="kerajinan" {{ old('kategori') == 'kerajinan' ? 'selected' : '' }}>Kerajinan</option>
        <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
    </select>

    @error('kategori')
        <div class="text-danger mt-1" style="font-size: 14px;">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">
        Lokasi <span class="text-red-600">*</span>
    </label>
    <input type="text"
           id="lokasi"
           name="lokasi"
           value="{{ old('lokasi') }}"
           placeholder="Contoh: Dusun Krajan"
           required
           class="w-full px-4 py-2 border rounded-lg 
                  border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                  @error('lokasi') @enderror">
    @error('lokasi')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>



<div class="mb-4">
    <label for="kontak" class="block text-sm font-medium text-gray-700 mb-1">
        Email <small class="text-gray-500">(Opsional)</small>
    </label>
    <input type="text"
           id="kontak"
           name="kontak"
           value="{{ old('kontak') }}"
           placeholder="Contoh: warurejo@gmail.com"
           class="w-full px-4 py-2 border rounded-lg 
                  border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                  @error('kontak') @enderror">
    @error('kontak')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>


<div class="mb-4">
    <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-1">
        Nomor WhatsApp <small class="text-gray-500">(Opsional)</small>
    </label>

    <div class="flex">
        <span class="px-4 py-2 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg text-gray-700 font-medium">
            +62
        </span>
        <input type="text"
               id="whatsapp"
               name="whatsapp"
               value="{{ old('whatsapp') }}"
               placeholder="8123456789"
               maxlength="15"
               pattern="[0-9]*"
               class="w-full px-4 py-2 border border-gray-300 rounded-r-lg
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                      @error('whatsapp') @enderror">
    </div>

    @error('whatsapp')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror

    <p class="text-xs text-gray-500 mt-1">
        Masukkan nomor tanpa awalan 0 atau +62. Contoh: 8123456789
    </p>
</div>



                       <!-- Status (di-comment, belum dibutuhkan) -->
{{-- <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Status Publikasi
    </label>
    
    <div class="flex items-center">
        <input type="checkbox" 
               id="is_active" 
               name="is_active" 
               value="1"
               {{ old('is_active', '1') == '1' ? 'checked' : '' }}
               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
        <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
            Aktifkan potensi ini (tampilkan di halaman public)
        </label>
    </div>
    
    @error('is_active')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    
    <small class="text-gray-500 text-xs mt-1 block">Jika dicentang, potensi ini akan ditampilkan di halaman publik</small>
</div> --}}
<input type="hidden" name="is_active" value="1">

<div class="border-t pt-4 mt-6">
    <div class="flex justify-between items-center">

        <!-- Tombol Batal -->
        <a href="{{ route('admin.potensi.index') }}"
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center gap-2">
            <i class="fas fa-times"></i> Batal
        </a>

        <div class="flex gap-3">

            <!-- Simpan & Publikasikan -->
            <button type="submit"
                    name="action"
                    value="publish"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition
                           flex items-center gap-2">
                <i class="fas fa-paper-plane"></i> Simpan & Publikasikan
            </button>
        </div>

    </div>
</div>


            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
@endpush

@push('scripts')
<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>

<script>
$(document).ready(function() {
    // Initialize CKEditor
    let editorInstance;
    ClassicEditor
        .create(document.querySelector('#deskripsi'), {
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
            editor.ui.view.editable.element.style.minHeight = '400px';
            window.editorInstance = editor;
        })
        .catch(error => {
            console.error(error);
        });

    // Character counter for deskripsi_singkat
    $('#deskripsi_singkat').on('input', function() {
        var length = $(this).val().length;
        $('#charCount').text(length);
        
        if (length > 500) {
            $('#charCount').addClass('text-danger');
        } else {
            $('#charCount').removeClass('text-danger');
        }
    });

    // Trigger on page load
    $('#deskripsi_singkat').trigger('input');

    // Image Preview
    $('#gambar').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB)
            if (file.size > 2048 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB');
                $(this).val('');
                return;
            }

            // Validate file type
            if (!file.type.match('image.*')) {
                alert('File harus berupa gambar!');
                $(this).val('');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result);
                $('#imagePreview').show();
            }
            reader.readAsDataURL(file);
        }
    });

    // Remove Image
    $('#removeImage').on('click', function() {
        $('#gambar').val('');
        $('#imagePreview').hide();
        $('#preview').attr('src', '');
    });

    // WhatsApp Number Validation
    $('#whatsapp').on('input', function() {
        // Only allow numbers
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Remove leading 0 or +62 if user enters it
        if (this.value.startsWith('0')) {
            this.value = this.value.substring(1);
        }
        if (this.value.startsWith('62')) {
            this.value = this.value.substring(2);
        }
        
        // Limit to 15 characters
        if (this.value.length > 15) {
            this.value = this.value.substring(0, 15);
        }
    });

    // Form Submit - Set status based on button clicked
    $('button[name="action"]').on('click', function() {
        var action = $(this).val();
        if (action === 'draft') {
            $('#status').val('draft');
        } else if (action === 'publish') {
            $('#status').val('published');
        }
    });

    // Form Validation
    $('#potensiForm').on('submit', function(e) {
        // Update CKEditor content to textarea
        if (window.editorInstance) {
            $('#deskripsi').val(window.editorInstance.getData());
        }
        
        // Validate required fields
        var nama = $('#nama').val().trim();
        var deskripsi = window.editorInstance ? window.editorInstance.getData().trim() : '';
        var deskripsi_singkat = $('#deskripsi_singkat').val().trim();
        var kategori = $('#kategori').val();
        var lokasi = $('#lokasi').val().trim();
        var gambar = $('#gambar').val();

        if (!nama || !deskripsi || !deskripsi_singkat || !kategori || !lokasi || !gambar) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi (bertanda *)');
            return false;
        }

        // Validate deskripsi_singkat length
        if (deskripsi_singkat.length > 500) {
            e.preventDefault();
            alert('Deskripsi singkat maksimal 500 karakter!');
            return false;
        }

        return true;
    });
});
</script>
@endpush
