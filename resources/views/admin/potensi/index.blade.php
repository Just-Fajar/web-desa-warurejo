{{--
    ADMIN POTENSI INDEX
    
    List management potensi desa
    
    FEATURES:
    - Statistics cards (Total/Aktif/Per Kategori)
    - Search filter (nama)
    - Kategori filter (7 kategori)
    - Status filter (Aktif/Tidak Aktif)
    - Sort options (Urutan/Terbaru/Terlama/A-Z)
    - Pagination (10 items per page)
    - Drag & drop reorder (sortable)
    - Bulk delete dengan checkbox
    - Quick view preview
    
    TABLE COLUMNS:
    - Urutan (sortable handle)
    - Gambar thumbnail
    - Nama potensi
    - Kategori badge
    - Kontak (telepon/WhatsApp)
    - Views counter
    - Status badge
    - Actions (Edit/Delete)
    
    SORTABLE FEATURE:
    - Drag & drop rows untuk ubah urutan
    - AJAX save urutan baru
    - Visual feedback saat drag
    
    BULK ACTIONS:
    - Select all checkbox
    - Bulk delete confirmation
    
    DATA:
    $potensi: Paginated collection dari PotensiDesaRepository
    $totalByKategori: Array count per kategori
    
    Route: /admin/potensi
    Controller: AdminPotensiController@index
--}}
@extends('admin.layouts.app')

@section('title', 'Kelola Potensi Desa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Potensi Desa</h1>
            <p class="text-sm text-gray-600 mt-1">Manajemen data potensi desa</p>
        </div>
        <a href="{{ route('admin.potensi.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Potensi
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
        <div class="flex">
            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <div class="flex">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span class="text-red-800">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Potensi</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $potensi->count() }}</h3>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Aktif</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $potensi->where('is_active', true)->count() }}</h3>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- FITUR STATUS BELUM DIPAKAI - JANGAN HAPUS
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-gray-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Tidak Aktif</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $potensi->where('is_active', false)->count() }}</h3>
                </div>
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
            </div>
        </div>
        --}}
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Table Header with Bulk Actions -->
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               id="searchInput"
                               placeholder="Cari potensi desa..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filter -->
                <div class="flex gap-2">
                    <select id="kategoriFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Kategori</option>
                        <option value="pertanian">Pertanian</option>
                        <option value="peternakan">Peternakan</option>
                        <option value="perikanan">Perikanan</option>
                        <option value="umkm">UMKM</option>
                        <option value="wisata">Wisata</option>
                        <option value="kerajinan">Kerajinan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>

                    {{-- FITUR STATUS BELUM DIPAKAI - JANGAN HAPUS
                    <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                    --}}

                    <!-- Bulk Delete Button -->
                    <button type="button" 
                            id="bulkDeleteBtn"
                            class="hidden px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Dipilih (<span id="selectedCount">0</span>)
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="potensiTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Potensi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Lokasi
                        </th>
                        {{-- FITUR STATUS BELUM DIPAKAI - JANGAN HAPUS
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        --}}
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($potensi as $item)
                        <tr class="hover:bg-gray-50 potensi-row" data-kategori="{{ $item->kategori }}" data-status="{{ $item->is_active ? 'active' : 'inactive' }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="potensi-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500" value="{{ $item->id }}">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="shrink-0 h-16 w-16 mr-4">
                                        <img class="h-16 w-16 rounded object-cover" 
                                             src="{{ $item->gambar_url }}" 
                                             alt="{{ $item->nama }}"
                                             onerror="this.src='{{ asset('images/default-potensi.jpg') }}'">
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ Str::limit($item->nama, 50) }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ Str::limit(strip_tags($item->deskripsi), 80) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $kategoriColors = [
                                        'pertanian' => 'bg-green-100 text-green-800',
                                        'peternakan' => 'bg-amber-100 text-amber-800',
                                        'perikanan' => 'bg-blue-100 text-blue-800',
                                        'umkm' => 'bg-purple-100 text-purple-800',
                                        'wisata' => 'bg-pink-100 text-pink-800',
                                        'kerajinan' => 'bg-indigo-100 text-indigo-800',
                                        'lainnya' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $bgColor = $kategoriColors[$item->kategori] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $bgColor }}">
                                    {{ ucfirst($item->kategori) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ Str::limit($item->lokasi ?? '-', 30) }}
                                </div>
                            </td>
                            {{-- FITUR STATUS BELUM DIPAKAI - JANGAN HAPUS
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.potensi.edit', $item->id) }}" 
                                       class="text-primary-600 hover:text-primary-900"
                                       title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.potensi.destroy', $item->id) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-600 hover:text-red-900 delete-btn" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                                <p class="text-lg font-medium mb-2">Belum ada potensi desa</p>
                                <a href="{{ route('admin.potensi.create') }}" class="text-primary-600 hover:text-primary-800">
                                    Tambah potensi pertama →
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        filterTable();
    });

    // Kategori filter
    document.getElementById('kategoriFilter').addEventListener('change', function() {
        filterTable();
    });

    // Status filter
    document.getElementById('statusFilter').addEventListener('change', function() {
        filterTable();
    });

    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const kategoriFilter = document.getElementById('kategoriFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('.potensi-row');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const kategori = row.dataset.kategori;
            const status = row.dataset.status;
            
            const matchSearch = text.includes(searchTerm);
            const matchKategori = !kategoriFilter || kategori === kategoriFilter;
            const matchStatus = !statusFilter || status === statusFilter;

            row.style.display = (matchSearch && matchKategori && matchStatus) ? '' : 'none';
        });
    }

    // Select All Checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.potensi-checkbox');
        checkboxes.forEach(checkbox => {
            if (checkbox.closest('.potensi-row').style.display !== 'none') {
                checkbox.checked = this.checked;
            }
        });
        updateBulkDeleteButton();
    });

    // Individual Checkboxes
    document.querySelectorAll('.potensi-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkDeleteButton);
    });

    function updateBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.potensi-checkbox:checked');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const selectedCount = document.getElementById('selectedCount');
        
        if (checkedBoxes.length > 0) {
            bulkDeleteBtn.classList.remove('hidden');
            selectedCount.textContent = checkedBoxes.length;
        } else {
            bulkDeleteBtn.classList.add('hidden');
        }
    }

    // Bulk Delete
    document.getElementById('bulkDeleteBtn').addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.potensi-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);

        Swal.fire({
            title: 'Hapus Potensi Terpilih?',
            html: `Anda akan menghapus <strong>${ids.length} potensi</strong> yang dipilih.<br>Data yang dihapus tidak dapat dikembalikan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus Semua!',
            cancelButtonText: 'Batal',
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            }
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
                bulkDelete(ids);
            }
        });
    });

    function bulkDelete(ids) {
        fetch('{{ route("admin.potensi.bulk-delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Dihapus!',
                    text: `${ids.length} potensi telah dihapus`,
                    confirmButtonColor: '#10B981',
                    showClass: {
                        popup: 'animate__animated animate__bounceIn'
                    }
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message,
                    confirmButtonColor: '#EF4444'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menghapus potensi.',
                confirmButtonColor: '#EF4444'
            });
        });
    }

    // Single Delete
    function deletePotensi(id, nama) {
        Swal.fire({
            title: 'Hapus Potensi?',
            html: `Anda akan menghapus potensi:<br><strong class="text-red-600">${nama}</strong><br><br>Data yang dihapus tidak dapat dikembalikan!`,
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
                const form = document.getElementById('deleteForm');
                form.action = `/admin/potensi/${id}`;
                form.submit();
            }
        });
    }

    // Auto hide alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endpush
@endsection
