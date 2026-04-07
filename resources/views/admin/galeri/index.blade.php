{{--
    ADMIN GALERI INDEX
    
    List semua galeri dengan management features
    
    FEATURES:
    - Grid view galeri dengan thumbnails
    - Statistics cards (Total/Aktif/Tidak Aktif)
    - Search filter (judul)
    - Kategori filter dropdown
    - Status filter (Aktif/Tidak Aktif)
    - Sort options (Terbaru/Terlama/A-Z/Z-A)
    - Pagination (12 items per page)
    - Quick toggle active status (AJAX)
    - Bulk delete dengan checkbox selection
    - View counter display
    
    GRID LAYOUT:
    - Responsive cards (1/2/3/4 columns)
    - Thumbnail image preview
    - Category badge dengan color coding
    - Active/Inactive status badge
    - Quick actions (Edit/Delete/Toggle)
    
    BULK ACTIONS:
    - Select all checkbox
    - Bulk delete selected items
    - Confirmation modal (SweetAlert2)
    
    AJAX FEATURES:
    - Toggle active status tanpa reload
    - Delete confirmation
    - Success/error toasts
    
    DATA:
    $galeri: Paginated collection dari GaleriRepository
    
    Route: /admin/galeri
    Controller: AdminGaleriController@index
--}}
@extends('admin.layouts.app')

@section('title', 'Kelola Galeri')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <!-- Revised Header Section (Tailwind style matching your request) -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kelola Galeri</h1>
        <p class="text-gray-500 text-sm">Manajemen foto dan video galeri desa</p>
    </div>
    <a href="{{ route('admin.galeri.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg shadow font-medium flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Galeri
    </a>
</div>

<!-- Flash Messages -->
@if(session('success'))
<div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
    <span><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="text-green-700">&times;</button>
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
    <span><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="text-red-700">&times;</button>
</div>
@endif

<!-- Filter Section -->
<div class="bg-white shadow rounded-lg p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
            <label class="text-gray-600 text-sm">Cari Galeri: <span id="filterCount" class="text-purple-600 font-semibold"></span></label>
            <input type="text" 
                   id="searchInput" 
                   placeholder="Cari judul..." 
                   class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>
        
        <div>
            <label class="text-gray-600 text-sm">Filter Kategori:</label>
            <select id="filterKategori" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Kategori</option>
                <option value="kegiatan">Kegiatan</option>
                <option value="infrastruktur">Infrastruktur</option>
                <option value="budaya">Budaya</option>
                <option value="umkm">UMKM</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>

        {{-- FITUR STATUS BELUM DIPAKAI - JANGAN HAPUS
        <div>
            <label class="text-gray-600 text-sm">Filter Status:</label>
            <select id="filterStatus" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Status</option>
                <option value="1">Aktif</option>
                <option value="0">Non-Aktif</option>
            </select>
        </div>
        --}}
    </div>
</div>

<!-- Grid Galeri -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="galeriGrid">
    @forelse($galeri as $item)
    <div class="border rounded-lg shadow bg-white overflow-hidden galeri-item hover:shadow-xl transition-shadow" 
         data-judul="{{ strtolower(strip_tags($item->judul)) }}"
         data-kategori="{{ $item->kategori }}" 
         data-status="{{ $item->is_active }}">

        <!-- Thumbnail -->
        <div class="relative h-48 overflow-hidden bg-gray-200">
            @if($item->images && $item->images->count() > 0)
                <img src="{{ $item->images->first()->image_url }}" 
                     alt="{{ $item->judul }}"
                     class="w-full h-full object-cover"
                     onerror="this.style.display='none'; this.parentElement.classList.add('bg-gray-300')">
                
                {{-- Multiple Images Badge --}}
                @if($item->images->count() > 1)
                    <div class="absolute top-2 left-2">
                        <span class="px-2 py-1 bg-black/70 text-white text-xs font-semibold rounded">
                            <i class="fas fa-images mr-1"></i>{{ $item->images->count() }} Foto
                        </span>
                    </div>
                @endif
            @else
                <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                    <i class="fas fa-image text-gray-500 text-4xl"></i>
                </div>
            @endif
        </div>

        <!-- Body -->
        <div class="p-4">
            <h3 class="font-semibold text-gray-800 text-sm mb-2">{{ Str::limit($item->judul, 50) }}</h3>

            <!-- Badges -->
            <div class="flex justify-between items-center mb-2">
                @php
                    $badgeClass = match($item->kategori) {
                        'kegiatan' => 'bg-green-600',
                        'infrastruktur' => 'bg-blue-500',
                        'budaya' => 'bg-yellow-500',
                        'umkm' => 'bg-purple-600',
                        'lainnya' => 'bg-gray-600',
                        default => 'bg-gray-500'
                    };
                    
                    $kategoriLabel = match($item->kategori) {
                        'kegiatan' => 'Kegiatan',
                        'infrastruktur' => 'Infrastruktur',
                        'budaya' => 'Budaya',
                        'umkm' => 'UMKM',
                        'lainnya' => 'Lainnya',
                        default => ucfirst($item->kategori)
                    };
                @endphp
                <span class="text-white text-xs px-2 py-1 rounded {{ $badgeClass }}">{{ $kategoriLabel }}</span>

                {{-- FITUR STATUS BELUM DIPAKAI - JANGAN HAPUS
                <span class="text-white text-xs px-2 py-1 rounded {{ $item->is_active ? 'bg-green-600' : 'bg-gray-500' }}">
                    {{ $item->is_active ? 'Aktif' : 'Non-Aktif' }}
                </span>
                --}}
            </div>

            <p class="text-xs text-gray-500 mb-2">
                <i class="fas fa-calendar mr-1"></i>
                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
            </p>

            @if($item->deskripsi)
            <p class="text-xs text-gray-700">{{ Str::limit($item->deskripsi, 60) }}</p>
            @endif
        </div>

        <!-- Footer -->
        <div class="border-t p-3 flex gap-2">
            {{-- FITUR STATUS BELUM DIPAKAI - JANGAN HAPUS
            <button type="button"
                    onclick="toggleActive({{ $item->id }}, this)"
                    class="flex-1 {{ $item->is_active ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-500 hover:bg-gray-600' }} text-white py-2 rounded text-sm transition-colors"
                    title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                <i class="fas fa-{{ $item->is_active ? 'eye' : 'eye-slash' }}"></i>
            </button>
            --}}

            <a href="{{ route('admin.galeri.edit', $item->id) }}" 
               class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded text-center text-sm">
                <i class="fas fa-edit"></i>
            </a>

            <form action="{{ route('admin.galeri.destroy', $item->id) }}" method="POST" class="flex-1 delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded text-sm">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>

    </div>
    @empty
    <div class="col-span-4 text-center py-10">
        <i class="fas fa-images text-5xl text-gray-400 mb-3"></i>
        <h3 class="text-gray-600 text-lg font-medium">Belum ada galeri</h3>
        <p class="text-gray-500">Tambahkan foto pertama Anda</p>
        <a href="{{ route('admin.galeri.create') }}" class="mt-3 inline-block bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg shadow">
            <i class="fas fa-plus mr-1"></i> Tambah Galeri
        </a>
    </div>
    @endforelse
</div>
@endsection

@push('styles')
<style>
    .galeri-item {
        transition: all 0.3s ease;
    }
    .galeri-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endpush

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    function filterGaleri() {
        const searchValue = document.getElementById('searchInput').value.toLowerCase().trim();
        const kategoriFilter = document.getElementById('filterKategori').value.toLowerCase();
        const statusFilter = document.getElementById('filterStatus').value;
        
        let visibleCount = 0;

        document.querySelectorAll('.galeri-item').forEach(function(item) {
            const itemJudul = item.dataset.judul.toLowerCase();
            const itemKategori = item.dataset.kategori.toLowerCase();
            const itemStatus = item.dataset.status.toString();

            let showItem = true;

            // Filter by search
            if (searchValue && !itemJudul.includes(searchValue)) {
                showItem = false;
            }
            
            // Filter by kategori
            if (kategoriFilter && itemKategori !== kategoriFilter) {
                showItem = false;
            }
            
            // Filter by status
            if (statusFilter && itemStatus !== statusFilter) {
                showItem = false;
            }

            if (showItem) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Update counter
        const totalItems = document.querySelectorAll('.galeri-item').length;
        document.getElementById('filterCount').textContent = `(${visibleCount}/${totalItems})`;
    }

    // Add event listeners
    document.getElementById('searchInput').addEventListener('keyup', filterGaleri);
    document.getElementById('filterKategori').addEventListener('change', filterGaleri);
    document.getElementById('filterStatus').addEventListener('change', filterGaleri);
    
    // Initialize counter
    filterGaleri();

    // Single Delete Confirmation
    document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const currentForm = this;
            const galeriCard = currentForm.closest('.galeri-item');
            const galeriTitle = galeriCard ? galeriCard.dataset.judul : 'galeri ini';

            Swal.fire({
                title: 'Hapus Galeri?',
                html: `Anda akan menghapus galeri:<br><strong class="text-red-600">${galeriTitle}</strong><br><br>Data yang dihapus tidak dapat dikembalikan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
                cancelButtonText: 'Batal',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp animate__faster'
                },
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'rounded-lg px-4 py-2',
                    cancelButton: 'rounded-lg px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    currentForm.submit();
                }
            });
        });
    });

    // Auto-hide alerts
    setTimeout(function() {
        document.querySelectorAll('.bg-green-100, .bg-red-100').forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});

// Toggle Active Function
function toggleActive(galeriId, button) {
    const currentCard = button.closest('.galeri-item');
    const statusBadge = currentCard.querySelector('.text-white.text-xs.px-2.py-1.rounded.bg-green-600, .text-white.text-xs.px-2.py-1.rounded.bg-gray-500');
    const currentStatus = currentCard.dataset.status === '1';
    
    Swal.fire({
        title: currentStatus ? 'Nonaktifkan Galeri?' : 'Aktifkan Galeri?',
        text: currentStatus ? 'Galeri tidak akan ditampilkan di website' : 'Galeri akan ditampilkan di website',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: currentStatus ? '#6B7280' : '#10B981',
        cancelButtonColor: '#6B7280',
        confirmButtonText: currentStatus ? '<i class="fas fa-eye-slash mr-2"></i>Ya, Nonaktifkan' : '<i class="fas fa-eye mr-2"></i>Ya, Aktifkan',
        cancelButtonText: 'Batal',
        showClass: {
            popup: 'animate__animated animate__fadeInDown animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp animate__faster'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/galeri/${galeriId}/toggle-active`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button style and icon
                    if (data.is_active) {
                        button.classList.remove('bg-gray-500', 'hover:bg-gray-600');
                        button.classList.add('bg-green-600', 'hover:bg-green-700');
                        button.innerHTML = '<i class="fas fa-eye"></i>';
                        button.title = 'Nonaktifkan';
                    } else {
                        button.classList.remove('bg-green-600', 'hover:bg-green-700');
                        button.classList.add('bg-gray-500', 'hover:bg-gray-600');
                        button.innerHTML = '<i class="fas fa-eye-slash"></i>';
                        button.title = 'Aktifkan';
                    }
                    
                    // Update status badge
                    if (statusBadge) {
                        statusBadge.textContent = data.is_active ? 'Aktif' : 'Non-Aktif';
                        if (data.is_active) {
                            statusBadge.classList.remove('bg-gray-500');
                            statusBadge.classList.add('bg-green-600');
                        } else {
                            statusBadge.classList.remove('bg-green-600');
                            statusBadge.classList.add('bg-gray-500');
                        }
                    }
                    
                    // Update data attribute for filter
                    currentCard.dataset.status = data.is_active ? '1' : '0';
                    
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Terjadi kesalahan saat mengubah status', 'error');
            });
        }
    });
}
</script>
@endpush
