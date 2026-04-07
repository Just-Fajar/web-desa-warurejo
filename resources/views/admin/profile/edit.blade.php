{{--
    ADMIN PROFILE EDIT
    
    Form edit profile admin (nama, email, password, foto)
    
    FEATURES:
    - Photo upload & preview
    - Update nama & email
    - Change password (dengan current password verification)
    - Delete photo option
    - Real-time validation
    
    FORM SECTIONS:
    
    1. PHOTO UPLOAD:
    - Display current avatar (or initial letter if no photo)
    - Upload new photo (max 2MB, JPG/PNG)
    - Crop & preview before save
    - Delete photo button (AJAX)
    
    2. BASIC INFO:
    - name: Nama lengkap admin (required)
    - email: Email address (unique, format validated)
    - phone: Nomor telepon (optional)
    
    3. CHANGE PASSWORD:
    - current_password: Password sekarang (verification)
    - password: Password baru (min 8 chars)
    - password_confirmation: Konfirmasi password baru
    
    VALIDATION:
    - Email unique (exclude current admin)
    - Email format valid
    - Current password must match
    - New password min 8 chars
    - Password confirmation must match
    
    AJAX FEATURES:
    - Photo upload (separate endpoint)
    - Photo delete (separate endpoint)
    - Real-time email check
    - Password strength indicator
    
    SECURITY:
    - Current password verification untuk change password
    - Password hashing (bcrypt)
    - CSRF protection
    
    ROUTES:
    - PUT /admin/profile/update (basic info)
    - POST /admin/profile/photo (upload photo)
    - DELETE /admin/profile/photo (delete photo)
    - PUT /admin/profile/password (change password)
    
    Controller: AdminProfileController
--}}
@extends('admin.layouts.app')

@section('title', 'Edit Profile')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('admin.profile.show') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600">
            Profile
        </a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-sm font-medium text-gray-500">Edit Profile</span>
    </li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    <div class="bg-white shadow-md rounded-lg border border-gray-200">
        
        <div class="border-b px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                <i class="fas fa-user-edit text-primary-600"></i>
                Edit Profile
            </h2>
        </div>

        <div class="p-6">
            {{-- Photo Upload Section --}}
            <div class="mb-8 pb-8 border-b">
                <h3 class="text-md font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-camera text-primary-600"></i>
                    Foto Profil
                </h3>

                <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                    {{-- Current Photo Display --}}
                    <div class="flex-shrink-0">
                        <div id="currentPhotoContainer" class="relative group">
                            @if($admin->avatar)
                                <img id="currentPhoto" 
                                     src="{{ asset('storage/' . $admin->avatar) }}" 
                                     alt="Foto Profil" 
                                     class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 shadow-md">
                            @else
                                <div id="currentPhoto" class="w-32 h-32 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center border-4 border-gray-200 shadow-md">
                                    <span class="text-4xl font-bold text-white">{{ substr($admin->name, 0, 1) }}</span>
                                </div>
                            @endif
                            
                            {{-- Delete Button (only shown if avatar exists) --}}
                            @if($admin->avatar)
                            <button type="button" 
                                    onclick="deletePhoto()"
                                    class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                    title="Hapus Foto">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    {{-- Upload Controls --}}
                    <div class="flex-1">
                        <div class="space-y-3">
                            {{-- File Input (Hidden) --}}
                            <input type="file" 
                                   id="photoInput" 
                                   accept="image/jpeg,image/jpg,image/png" 
                                   class="hidden">

                            {{-- Upload Button --}}
                            <button type="button" 
                                    onclick="document.getElementById('photoInput').click()"
                                    class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition shadow inline-flex items-center gap-2">
                                <i class="fas fa-upload"></i>
                                <span>{{ $admin->avatar ? 'Ubah Foto' : 'Upload Foto' }}</span>
                            </button>

                            {{-- Info Text --}}
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><i class="fas fa-info-circle text-primary-600 mr-1"></i> Format: JPG, JPEG, PNG</p>
                                <p><i class="fas fa-info-circle text-primary-600 mr-1"></i> Ukuran maksimal: 2 MB</p>
                                <p><i class="fas fa-info-circle text-primary-600 mr-1"></i> Resolusi optimal: 400 x 400 px</p>
                            </div>

                            {{-- Preview Section (Hidden by default) --}}
                            <div id="previewSection" class="hidden mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-4">
                                    <img id="previewImage" 
                                         src="" 
                                         alt="Preview" 
                                         class="w-20 h-20 rounded-full object-cover border-2 border-primary-400">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-700" id="fileName"></p>
                                        <p class="text-xs text-gray-500" id="fileSize"></p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" 
                                                onclick="uploadPhoto()"
                                                id="btnUpload"
                                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition">
                                            <i class="fas fa-check mr-1"></i>Upload
                                        </button>
                                        <button type="button" 
                                                onclick="cancelPreview()"
                                                class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white text-sm rounded-lg transition">
                                            <i class="fas fa-times mr-1"></i>Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Profile Form --}}
            <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $admin->name) }}"
                           required
                           class="w-full px-4 py-3 border rounded-lg focus:ring focus:ring-primary-200 @error('name') border-red-500 @enderror">

                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', $admin->email) }}"
                           required
                           class="w-full px-4 py-3 border rounded-lg focus:ring focus:ring-primary-200 @error('email') border-red-500 @enderror">

                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Button --}}
                <div class="pt-6 border-t flex items-center justify-between">
                    <a href="{{ route('admin.profile.show') }}"
                       class="px-5 py-3 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>

                    <button type="submit"
                            class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition shadow">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

<script>
let selectedFile = null;

// Handle file selection
document.getElementById('photoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    
    if (!file) return;

    // Validate file type
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!validTypes.includes(file.type)) {
        Swal.fire({
            icon: 'error',
            title: 'Format Tidak Valid',
            text: 'Hanya file JPG, JPEG, dan PNG yang diperbolehkan',
            confirmButtonColor: '#ef4444'
        });
        return;
    }

    // Validate file size (2MB)
    if (file.size > 2 * 1024 * 1024) {
        Swal.fire({
            icon: 'error',
            title: 'Ukuran Terlalu Besar',
            text: 'Ukuran file maksimal 2 MB',
            confirmButtonColor: '#ef4444'
        });
        return;
    }

    selectedFile = file;

    // Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImage').src = e.target.result;
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatFileSize(file.size);
        document.getElementById('previewSection').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
});

// Upload photo
function uploadPhoto() {
    if (!selectedFile) return;

    const formData = new FormData();
    formData.append('photo', selectedFile);
    formData.append('_token', '{{ csrf_token() }}');

    // Disable upload button
    const btnUpload = document.getElementById('btnUpload');
    const originalContent = btnUpload.innerHTML;
    btnUpload.disabled = true;
    btnUpload.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Uploading...';

    fetch('{{ route("admin.profile.update-photo") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonColor: '#10b981',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then(() => {
                // Update current photo
                const currentPhoto = document.getElementById('currentPhoto');
                if (currentPhoto.tagName === 'IMG') {
                    currentPhoto.src = data.photo_url;
                } else {
                    // Replace placeholder with image
                    const container = document.getElementById('currentPhotoContainer');
                    container.innerHTML = `
                        <img id="currentPhoto" 
                             src="${data.photo_url}" 
                             alt="Foto Profil" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 shadow-md">
                        <button type="button" 
                                onclick="deletePhoto()"
                                class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                title="Hapus Foto">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    `;
                }
                
                // Reset preview
                cancelPreview();
                
                // Reload page to update header avatar
                setTimeout(() => location.reload(), 1000);
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message,
                confirmButtonColor: '#ef4444'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan saat mengupload foto',
            confirmButtonColor: '#ef4444'
        });
        console.error('Error:', error);
    })
    .finally(() => {
        btnUpload.disabled = false;
        btnUpload.innerHTML = originalContent;
    });
}

// Delete photo
function deletePhoto() {
    Swal.fire({
        title: 'Hapus Foto Profil?',
        text: 'Foto profil Anda akan dihapus',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus',
        cancelButtonText: 'Batal',
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("admin.profile.delete-photo") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#10b981',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    }).then(() => {
                        // Replace image with placeholder
                        const container = document.getElementById('currentPhotoContainer');
                        container.innerHTML = `
                            <div id="currentPhoto" class="w-32 h-32 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center border-4 border-gray-200 shadow-md">
                                <span class="text-4xl font-bold text-white">{{ substr($admin->name, 0, 1) }}</span>
                            </div>
                        `;
                        
                        // Reload page to update header avatar
                        setTimeout(() => location.reload(), 1000);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghapus foto',
                    confirmButtonColor: '#ef4444'
                });
                console.error('Error:', error);
            });
        }
    });
}

// Cancel preview
function cancelPreview() {
    selectedFile = null;
    document.getElementById('photoInput').value = '';
    document.getElementById('previewSection').classList.add('hidden');
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}
</script>

@endsection
