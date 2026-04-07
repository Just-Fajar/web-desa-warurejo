{{--
    ADMIN BERITA INDEX
    
    Halaman list semua berita dengan fitur CRUD
    
    FEATURES:
    - Statistics cards (Total/Published/Draft)
    - Search filter (judul/kategori/status)
    - Sorting (terbaru/terlama/A-Z)
    - Pagination (10 items per page)
    - Delete confirmation (SweetAlert2)
    - Quick view excerpt + thumbnail
    - Status badges (Published=green, Draft=yellow)
    
    SEARCH & FILTER:
    - Keyword: Search di judul
    - Kategori: Dropdown (Pengumuman/Event/Berita)
    - Status: Dropdown (Published/Draft)
    - Sort: Dropdown (Terbaru/Terlama/A-Z/Z-A)
    
    ACTIONS:
    - Edit: Navigate ke admin.berita.edit
    - Delete: POST ke admin.berita.destroy dengan confirmation
    
    DATA:
    $berita: Paginated collection dari BeritaRepository
    
    Route: /admin/berita
    Controller: App\Http\Controllers\Admin\BeritaController@index
--}}
@extends('admin.layouts.app')


@section('title', 'Kelola Berita')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Berita</h1>
            <p class="text-sm text-gray-600 mt-1">Manajemen berita dan artikel</p>
        </div>
        <a href="{{ route('admin.berita.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Berita
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Berita</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $berita->total() }}</h3>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Published</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $berita->where('status', 'published')->count() }}</h3>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- FITUR STATUS BELUM DIPAKAI - JANGAN HAPUS
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Draft</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $berita->where('status', 'draft')->count() }}</h3>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                               placeholder="Cari berita..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filter -->
                <div class="flex gap-2">
                    {{-- FITUR STATUS BELUM DIPAKAI - JANGAN HAPUS
                    <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Status</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
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
            <table class="min-w-full divide-y divide-gray-200" id="beritaTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Berita
                        </th>
                        {{-- FITUR STATUS BELUM DIPAKAI - JANGAN HAPUS
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        --}}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Views
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($berita as $item)
                        <tr class="hover:bg-gray-50 berita-row" data-status="{{ $item->status }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="berita-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500" value="{{ $item->id }}">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="shrink-0 h-16 w-16 mr-4">
                                        <img class="h-16 w-16 rounded object-cover" 
                                             src="{{ $item->gambar_utama_url }}" 
                                             alt="{{ $item->judul }}"
                                             onerror="this.src='{{ asset('images/default-berita.jpg') }}'">
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ Str::limit($item->judul, 50) }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ Str::limit($item->excerpt, 80) }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            oleh {{ $item->admin->name ?? 'Unknown' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            {{-- FITUR STATUS BELUM DIPAKAI - JANGAN HAPUS
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status === 'published')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Published
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ number_format($item->views ?? 0) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ optional($item->created_at)->format('d M Y') ?? '-' }}</div>
                                <div class="text-xs text-gray-400">{{ optional($item->created_at)->format('H:i') ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.berita.edit', $item->id) }}" 
                                       class="text-primary-600 hover:text-primary-900"
                                       title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST" class="inline delete-form">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                                <p class="text-lg font-medium mb-2">Belum ada berita</p>
                                <a href="{{ route('admin.berita.create') }}" class="text-primary-600 hover:text-primary-800">
                                    Tambah berita pertama →
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($berita->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $berita->links() }}
            </div>
        @endif
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
        const searchTerm = this.value.toLowerCase();
        filterTable();
    });

    // Status filter
    document.getElementById('statusFilter').addEventListener('change', function() {
        filterTable();
    });

    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('.berita-row');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const status = row.dataset.status;
            
            const matchSearch = text.includes(searchTerm);
            const matchStatus = !statusFilter || status === statusFilter;

            row.style.display = (matchSearch && matchStatus) ? '' : 'none';
        });
    }

    // Select All Checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.berita-checkbox');
        checkboxes.forEach(checkbox => {
            if (checkbox.closest('.berita-row').style.display !== 'none') {
                checkbox.checked = this.checked;
            }
        });
        updateBulkDeleteButton();
    });

    // Individual Checkboxes
    document.querySelectorAll('.berita-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkDeleteButton);
    });

    function updateBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.berita-checkbox:checked');
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
        const checkedBoxes = document.querySelectorAll('.berita-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);

        Swal.fire({
            title: 'Hapus Berita Terpilih?',
            html: `Anda akan menghapus <strong>${ids.length} berita</strong> yang dipilih.<br>Data yang dihapus tidak dapat dikembalikan!`,
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
        fetch('{{ route("admin.berita.bulk-delete") }}', {
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
                    text: `${ids.length} berita telah dihapus`,
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
                text: 'Terjadi kesalahan saat menghapus berita.',
                confirmButtonColor: '#EF4444'
            });
        });
    }

    // Note: Single delete now handled by global delete-form script in app.blade.php
    // Keeping deleteBerita function commented for reference
    /*
    function deleteBerita(id, judul) {
        Swal.fire({
            title: 'Hapus Berita?',
            html: `Anda akan menghapus berita:<br><strong class="text-red-600">${judul}</strong><br><br>Data yang dihapus tidak dapat dikembalikan!`,
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
                form.action = `/admin/berita/${id}`;
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection
