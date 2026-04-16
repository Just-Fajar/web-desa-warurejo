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
<div class="bg-white shadow-sm border border-gray-100 rounded-3xl p-5 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
            <label class="text-gray-500 text-sm font-semibold mb-2 block">Cari Galeri: <span id="filterCount" class="text-primary-600"></span></label>
            <div class="relative">
                <input type="text" 
                       id="searchInput" 
                       placeholder="Ketik judul..." 
                       class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm">
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
        
        <div>
            <label class="text-gray-500 text-sm font-semibold mb-2 block">Filter Kategori:</label>
            <select id="filterKategori" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-600 text-sm font-semibold transition-all">
                <option value="">Semua Kategori</option>
                <option value="kegiatan">Kegiatan</option>
                <option value="infrastruktur">Infrastruktur</option>
                <option value="budaya">Budaya</option>
                <option value="umkm">UMKM</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>
    </div>
</div>

<!-- Bulk Actions -->
<div id="bulk-actions" class="bg-rose-50 border border-rose-100 rounded-2xl p-4 mb-6 hidden">
    <div class="flex items-center justify-between">
        <span class="text-rose-700 font-semibold">
            <span id="selectedCount">0</span> galeri dipilih
        </span>
        <button id="bulkDeleteBtn" class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-xl font-semibold transition text-sm flex items-center">
            <i class="fas fa-trash mr-2"></i>
            Hapus Terpilih
        </button>
    </div>
</div>

<!-- Grid Galeri -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="galeriGrid">
    @forelse($galeri as $item)
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden galeri-item hover:-translate-y-1 hover:shadow-md transition-all duration-300 flex flex-col" 
         data-judul="{{ strtolower(strip_tags($item->judul)) }}"
         data-kategori="{{ $item->kategori }}" 
         data-status="{{ $item->is_active }}">

        <!-- Thumbnail -->
        <div class="relative h-48 overflow-hidden bg-gray-50 flex-shrink-0 group">
            <!-- Checkbox -->
            <div class="absolute top-3 left-3 z-10">
                <input type="checkbox" class="galeri-checkbox w-5 h-5 rounded border-2 border-white bg-white/50 backdrop-blur-sm text-primary-600 focus:ring-primary-500 shadow-sm transition-all" value="{{ $item->id }}">
            </div>
            @if($item->images && $item->images->count() > 0)
                <img src="{{ $item->images->first()->image_url }}" 
                     alt="{{ $item->judul }}"
                     class="w-full h-full object-cover"
                     onerror="this.style.display='none'; this.parentElement.classList.add('bg-gray-100')">
                
                {{-- Multiple Images Badge --}}
                @if($item->images->count() > 1)
                    <div class="absolute top-3 right-3">
                        <span class="px-2.5 py-1 bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold tracking-wider rounded-lg border border-white/20">
                            <i class="fas fa-images mr-1"></i>{{ $item->images->count() }} FOTO
                        </span>
                    </div>
                @endif
            @else
                <div class="w-full h-full flex items-center justify-center border-b border-gray-100">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            @endif
        </div>

        <!-- Body -->
        <div class="p-5 flex-1 flex flex-col">
            <h3 class="font-bold text-gray-800 text-sm mb-3 leading-tight">{{ Str::limit($item->judul, 50) }}</h3>

            <!-- Badges -->
            <div class="flex flex-wrap gap-2 mb-3">
                @php
                    $kategoriColors = [
                        'kegiatan' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                        'infrastruktur' => 'bg-amber-50 text-amber-600 border-amber-200',
                        'budaya' => 'bg-purple-50 text-purple-600 border-purple-200',
                        'umkm' => 'bg-blue-50 text-blue-600 border-blue-200',
                        'lainnya' => 'bg-rose-50 text-rose-600 border-rose-200'
                    ];
                    
                    $kategoriLabel = match($item->kategori) {
                        'kegiatan' => 'Kegiatan',
                        'infrastruktur' => 'Infrastruktur',
                        'budaya' => 'Budaya',
                        'umkm' => 'UMKM',
                        'lainnya' => 'Lainnya',
                        default => ucfirst($item->kategori)
                    };
                    $badgeClass = $kategoriColors[$item->kategori] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                @endphp
                <span class="px-2.5 py-1 text-[10px] uppercase font-bold rounded-md border {{ $badgeClass }}">{{ $kategoriLabel }}</span>
            </div>

            <div class="mt-auto">
                <div class="flex items-center text-xs text-gray-400 font-medium mb-3">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-3 border-t border-gray-50 bg-gray-50/50 flex gap-2">
            <a href="{{ route('admin.galeri.edit', $item->id) }}" 
               class="flex-1 flex items-center justify-center bg-sky-50 text-sky-600 hover:bg-sky-500 hover:text-white py-2 rounded-xl text-sm font-medium transition-all duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg> Edit
            </a>

            <form action="{{ route('admin.galeri.destroy', $item->id) }}" method="POST" class="flex-1 delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full flex items-center justify-center bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white py-2 rounded-xl text-sm font-medium transition-all duration-200">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> Hapus
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
        const searchInput = document.getElementById('searchInput');
        const kategoriFilter = document.getElementById('filterKategori');
        
        const searchValue = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const filterCategory = kategoriFilter ? kategoriFilter.value.toLowerCase() : '';
        
        let visibleCount = 0;

        document.querySelectorAll('.galeri-item').forEach(function(item) {
            const itemJudul = item.dataset.judul.toLowerCase();
            const itemKategori = item.dataset.kategori.toLowerCase();

            let showItem = true;

            // Filter by search
            if (searchValue && !itemJudul.includes(searchValue)) {
                showItem = false;
            }
            
            // Filter by kategori
            if (filterCategory && itemKategori !== filterCategory) {
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
    const searchInputEl = document.getElementById('searchInput');
    if (searchInputEl) searchInputEl.addEventListener('keyup', filterGaleri);
    
    const filterKategoriEl = document.getElementById('filterKategori');
    if (filterKategoriEl) filterKategoriEl.addEventListener('change', filterGaleri);
    
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
    }, 5000);

    // Individual Checkboxes
    document.querySelectorAll('.galeri-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkDeleteButton);
    });

    function updateBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.galeri-checkbox:checked');
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCount = document.getElementById('selectedCount');
        
        if (checkedBoxes.length > 0) {
            bulkActions.classList.remove('hidden');
            selectedCount.textContent = checkedBoxes.length;
        } else {
            bulkActions.classList.add('hidden');
        }
    }

    // Bulk Delete
    document.getElementById('bulkDeleteBtn').addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.galeri-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);

        Swal.fire({
            title: 'Hapus Galeri Terpilih?',
            html: `Anda akan menghapus <strong>${ids.length} galeri</strong> yang dipilih.<br>Data yang dihapus tidak dapat dikembalikan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    html: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch('{{ route("admin.galeri.bulk-delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ ids: ids })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Dihapus!',
                            text: `${ids.length} galeri telah dihapus`,
                            confirmButtonColor: '#10B981'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'Terjadi kesalahan saat menghapus data.', 'error');
                });
            }
        });
    });
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
