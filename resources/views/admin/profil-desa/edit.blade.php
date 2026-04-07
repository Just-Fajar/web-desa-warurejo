{{--
    ADMIN PROFIL DESA EDIT
    
    Form edit profil & konten desa (single record, no create/delete)
    
    FEATURES:
    - Edit informasi desa (nama, visi, misi, sejarah)
    - Upload/update images (header banner, struktur organisasi)
    - Contact info (alamat, telepon, email)
    - Social media links (Facebook, Instagram, YouTube)
    - Map embed code (Google Maps)
    - Rich text editor untuk konten panjang
    
    FORM SECTIONS:
    
    1. BASIC INFO:
    - nama_desa: Nama desa (required)
    - kecamatan: Nama kecamatan
    - kabupaten: Nama kabupaten
    - provinsi: Nama provinsi
    - kode_pos: Kode pos
    
    2. CONTENT:
    - visi: Visi desa (textarea)
    - misi: Misi desa (textarea, support numbered list)
    - sejarah: Sejarah desa (TinyMCE rich text)
    - sambutan_kepala_desa: Sambutan (TinyMCE)
    
    3. IMAGES:
    - gambar_header: Banner homepage (1920x600px recommended)
    - gambar_struktur: Bagan struktur organisasi (1920x1080px)
    - Max 5MB per image
    - Preview current & new image
    
    4. CONTACT:
    - alamat: Alamat lengkap
    - telepon: Nomor telepon
    - email: Email desa
    - website: URL website (optional)
    
    5. SOCIAL MEDIA:
    - facebook_url: Facebook page URL
    - instagram_url: Instagram profile URL
    - youtube_url: YouTube channel URL
    
    6. MAP:
    - maps_embed: Google Maps iframe embed code
    - latitude: Koordinat latitude (optional)
    - longitude: Koordinat longitude (optional)
    
    VALIDATION:
    - Nama desa required
    - Email format validation
    - Images max 5MB, image format
    - URL format untuk social media
    
    JAVASCRIPT:
    - TinyMCE initialization
    - Image preview
    - URL validation
    - Maps embed preview
    
    NOTE:
    Profil desa hanya 1 record, tidak ada create/delete
    Seeder create default record jika belum ada
    
    Route: PUT /admin/profil-desa/update
    Controller: ProfilDesaController@update
--}}
@extends('admin.layouts.app')

@section('title', 'Edit Profil Desa')

@section('breadcrumb')
    <li class="inline-flex items-center text-gray-500">
        <i class="fas fa-chevron-right mx-2"></i>
        <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600">Dashboard</a>
    </li>
    <li class="inline-flex items-center text-gray-500">
        <i class="fas fa-chevron-right mx-2"></i>
        <span>Edit Profil Desa</span>
    </li>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Left Column -->
        <div class="flex-1 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Sukses!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            <!-- Instructions -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded shadow-sm">
                <h3 class="font-semibold text-blue-700 mb-2"><i class="fas fa-lightbulb mr-2"></i>Petunjuk Upload Gambar</h3>
                <ul class="list-disc pl-5 text-gray-700 text-sm space-y-1">
                    <li><strong>Gambar Header:</strong> Banner homepage (1920x600px disarankan)</li>
                    <li><strong>Struktur Organisasi:</strong> Bagan struktur organisasi desa (1920x1080px disarankan)</li>
                    <li>Format: JPEG, PNG, WEBP, Maks 5MB per gambar</li>
                </ul>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.profil-desa.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid md:grid-cols-2 gap-6">

                    <!-- Gambar Header -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h4 class="font-semibold text-gray-700 mb-3"><i class="fas fa-image mr-2"></i>Gambar Header</h4>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-primary-500" id="dropzone-header">
                            @if($profil->gambar_header)
                                <img src="{{ Storage::url($profil->gambar_header) }}" class="mx-auto mb-3 rounded shadow" style="max-height:200px;">
                                <p class="text-green-600 text-sm font-medium"><i class="fas fa-check-circle mr-1"></i>Gambar saat ini</p>
                            @else
                                <i class="fas fa-image text-gray-400 text-6xl mb-2"></i>
                                <p class="text-gray-500 text-sm">Belum ada gambar header</p>
                            @endif
                            <input type="file" name="gambar_header" id="gambar_header" class="hidden" accept="image/*">
                            <p class="text-xs text-gray-400 mt-2">Klik atau drop gambar di sini</p>
                        </div>
                        @error('gambar_header')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        <div id="preview_header" class="mt-3 hidden">
                            <p class="font-medium text-gray-700 mb-1">Preview:</p>
                            <img id="preview_header_img" src="" class="mx-auto rounded shadow">
                        </div>
                    </div>

                    <!-- Struktur Organisasi -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h4 class="font-semibold text-gray-700 mb-3"><i class="fas fa-sitemap mr-2"></i>Struktur Organisasi</h4>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-green-500" id="dropzone-struktur">
                            @if($profil->struktur_organisasi)
                                <img src="{{ Storage::url($profil->struktur_organisasi) }}" class="mx-auto mb-3 rounded shadow" style="max-height:200px;">
                                <p class="text-green-600 text-sm font-medium"><i class="fas fa-check-circle mr-1"></i>Struktur saat ini</p>
                            @else
                                <i class="fas fa-sitemap text-gray-400 text-6xl mb-2"></i>
                                <p class="text-gray-500 text-sm">Belum ada struktur organisasi</p>
                            @endif
                            <input type="file" name="struktur_organisasi" id="struktur_organisasi" class="hidden" accept="image/*">
                            <p class="text-xs text-gray-400 mt-2">Klik atau drop gambar di sini</p>
                        </div>
                        @error('struktur_organisasi')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        <div id="preview_struktur" class="mt-3 hidden">
                            <p class="font-medium text-gray-700 mb-1">Preview:</p>
                            <img id="preview_struktur_img" src="" class="mx-auto rounded shadow">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center mt-4">
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 flex items-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg shadow hover:from-indigo-600 hover:to-blue-500 flex items-center">
                        <i class="fas fa-save mr-2"></i>Simpan Gambar
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Column -->
        <div class="w-full md:w-1/3 space-y-6">

            <!-- Info Panel -->
            <div class="bg-white rounded-lg shadow p-4">
                <h5 class="font-semibold text-gray-700 mb-3"><i class="fas fa-info-circle text-blue-500 mr-2"></i>Informasi Sistem</h5>
                <div class="text-sm text-gray-600 space-y-2">
                    <p><span class="font-medium">Framework:</span> Laravel 12.x</p>
                    <p><span class="font-medium">PHP Version:</span> {{ phpversion() }}</p>
                    <p><span class="font-medium">Environment:</span> <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">{{ config('app.env') }}</span></p>
                    <p><span class="font-medium">Debug Mode:</span> <span class="px-2 py-1 {{ config('app.debug') ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }} rounded-full text-xs">{{ config('app.debug') ? 'ON' : 'OFF' }}</span></p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-4 space-y-3">
                <h5 class="font-semibold text-gray-700 mb-3"><i class="fas fa-bolt text-yellow-500 mr-2"></i>Quick Actions</h5>
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 border border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 flex items-center"><i class="fas fa-chart-line mr-2"></i>Dashboard</a>
                <a href="{{ route('admin.profil-desa.edit') }}" class="block px-4 py-2 border border-green-500 text-green-600 rounded-lg hover:bg-green-50 flex items-center"><i class="fas fa-building mr-2"></i>Edit Profil Desa</a>
                <a href="{{ route('home') }}" target="_blank" class="block px-4 py-2 border border-indigo-500 text-indigo-600 rounded-lg hover:bg-indigo-50 flex items-center"><i class="fas fa-external-link-alt mr-2"></i>Lihat Website</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Header preview
    const headerInput = document.getElementById('gambar_header');
    const headerPreview = document.getElementById('preview_header');
    const headerImg = document.getElementById('preview_header_img');
    document.getElementById('dropzone-header').addEventListener('click', ()=> headerInput.click());
    headerInput.addEventListener('change', (e)=>{
        const file = e.target.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = e=>{
                headerImg.src = e.target.result;
                headerPreview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // Struktur preview
    const strukturInput = document.getElementById('struktur_organisasi');
    const strukturPreview = document.getElementById('preview_struktur');
    const strukturImg = document.getElementById('preview_struktur_img');
    document.getElementById('dropzone-struktur').addEventListener('click', ()=> strukturInput.click());
    strukturInput.addEventListener('change', (e)=>{
        const file = e.target.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = e=>{
                strukturImg.src = e.target.result;
                strukturPreview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
