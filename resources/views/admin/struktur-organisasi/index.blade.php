@extends('admin.layouts.app')

@section('title', 'Kelola Struktur Organisasi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Struktur Organisasi</h1>
            <p class="text-sm text-gray-500 mt-1">Manajemen anggota struktur organisasi desa</p>
        </div>
        <a href="{{ route('admin.struktur-organisasi.create') }}" 
           class="inline-flex items-center px-5 py-2.5 bg-primary-600 font-semibold text-white rounded-xl shadow-sm hover:bg-primary-700 transition focus:ring-4 focus:ring-primary-500/20">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Anggota
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 hover:-translate-y-1 transition duration-300">
            <div class="flex items-center justify-between xl:flex-col xl:items-start xl:gap-3 2xl:flex-row 2xl:items-center">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Anggota</p>
                    <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $strukturOrganisasi->total() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50/80 text-blue-600 rounded-2xl flex items-center justify-center shrink-0 border border-blue-100/50">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm9 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 hover:-translate-y-1 transition duration-300">
            <div class="flex items-center justify-between xl:flex-col xl:items-start xl:gap-3 2xl:flex-row 2xl:items-center">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Aktif</p>
                    <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $strukturOrganisasi->where('is_active', true)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50/80 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0 border border-emerald-100/50">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-.997-6l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 hover:-translate-y-1 transition duration-300">
            <div class="flex items-center justify-between xl:flex-col xl:items-start xl:gap-3 2xl:flex-row 2xl:items-center">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pimpinan</p>
                    <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $strukturOrganisasi->whereIn('level', ['kepala', 'sekretaris'])->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-amber-50/80 text-amber-600 rounded-2xl flex items-center justify-center shrink-0 border border-amber-100/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 hover:-translate-y-1 transition duration-300">
            <div class="flex items-center justify-between xl:flex-col xl:items-start xl:gap-3 2xl:flex-row 2xl:items-center">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Staff</p>
                    <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $strukturOrganisasi->whereIn('level', ['staff_kaur', 'staff_kasi'])->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-50/80 text-purple-600 rounded-2xl flex items-center justify-center shrink-0 border border-purple-100/50">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14 14.252v2.091l-4-2V12.25L4 15.244v-3.085l6-2.992 4-2 6 2.99-6 2.99zm-4-4.887L6.002 11.36 10 13.355l3.998-1.996L10 9.365zM22 17v-4.148l-1.996 1V17H22zM12 2L2 6.99v4.06l10 4.981 10-4.981V6.99L12 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div id="bulk-actions" class="bg-rose-50 border border-rose-100 rounded-2xl p-4 mb-4 hidden">
        <div class="flex items-center justify-between">
            <span class="text-rose-700 font-semibold">
                <span id="selectedCount">0</span> anggota dipilih
            </span>
            <button id="bulkDeleteBtn" class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-xl font-semibold transition text-sm flex items-center">
                <i class="fas fa-trash mr-2"></i>
                Hapus Terpilih
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Table Header with Filters -->
        <div class="p-5 border-b border-gray-100 bg-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Search -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <input type="text" 
                               id="searchInput"
                               placeholder="Cari nama atau jabatan..." 
                               class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-200 text-sm">
                        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex gap-2">
                    <select id="levelFilter" class="px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-600 text-sm font-semibold transition-all duration-200">
                        <option value="">Semua Level</option>
                        @foreach($levels as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>

                    <select id="statusFilter" class="px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-600 text-sm font-semibold transition-all duration-200">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100" id="strukturOrganisasiTable">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th scope="col" class="w-12 px-6 py-4 text-left">
                            <!-- No Select All -->
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Foto
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Nama & Jabatan
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Level
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Urutan
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($strukturOrganisasi as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-150" data-level="{{ $item->level }}" data-status="{{ $item->is_active ? '1' : '0' }}">
                        <td class="px-6 py-4">
                            <input type="checkbox" 
                                   class="item-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                   value="{{ $item->id }}">
                        </td>
                        <td class="px-6 py-4">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-50 shadow-sm border border-gray-100">
                                <img src="{{ $item->foto_url }}" 
                                     alt="{{ $item->nama }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-bold text-gray-800">{{ $item->nama }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $item->jabatan }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-[11px] font-bold uppercase rounded-full border 
                                @if($item->level == 'kepala') bg-purple-50 text-purple-600 border-purple-200
                                @elseif($item->level == 'sekretaris') bg-blue-50 text-blue-600 border-blue-200
                                @elseif($item->level == 'kaur') bg-amber-50 text-amber-600 border-amber-200
                                @elseif($item->level == 'staff_kaur') bg-orange-50 text-orange-600 border-orange-200
                                @elseif($item->level == 'kasi') bg-emerald-50 text-emerald-600 border-emerald-200
                                @else bg-gray-50 text-gray-600 border-gray-200
                                @endif">
                                {{ $item->level_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-600">
                            {{ $item->urutan }}
                        </td>
                        <td class="px-6 py-4">
                            @if($item->is_active)
                                <span class="px-2 inline-flex text-[11px] font-bold leading-5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2 inline-flex text-[11px] font-bold leading-5 rounded-full bg-rose-50 text-rose-600 border border-rose-100">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.struktur-organisasi.edit', $item->id) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-sky-50 text-sky-600 hover:bg-sky-500 hover:text-white transition-all duration-200"
                                   title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.struktur-organisasi.destroy', $item->id) }}" 
                                      method="POST" 
                                      class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white transition-all duration-200 delete-btn"
                                            title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada anggota</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan anggota struktur organisasi baru.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.struktur-organisasi.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Anggota
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($strukturOrganisasi->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $strukturOrganisasi->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const levelFilter = document.getElementById('levelFilter');
    const statusFilter = document.getElementById('statusFilter');
    const selectAll = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCount = document.getElementById('selectedCount');
    const deleteForms = document.querySelectorAll('.delete-form');

    // Search functionality
    searchInput.addEventListener('input', filterTable);
    levelFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedLevel = levelFilter.value;
        const selectedStatus = statusFilter.value;
        const rows = document.querySelectorAll('#strukturOrganisasiTable tbody tr');

        rows.forEach(row => {
            if (row.querySelector('td[colspan]')) return; // Skip empty state row

            const text = row.textContent.toLowerCase();
            const level = row.dataset.level;
            const status = row.dataset.status;

            const matchesSearch = text.includes(searchTerm);
            const matchesLevel = !selectedLevel || level === selectedLevel;
            const matchesStatus = !selectedStatus || status === selectedStatus;

            row.style.display = matchesSearch && matchesLevel && matchesStatus ? '' : 'none';
        });
    }

    // Individual checkbox
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkDeleteButton);
    });

    function updateBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const bulkActions = document.getElementById('bulk-actions');
        if (checkedBoxes.length > 0) {
            bulkActions.classList.remove('hidden');
            selectedCount.textContent = checkedBoxes.length;
        } else {
            bulkActions.classList.add('hidden');
        }
    }

    // Bulk delete
    bulkDeleteBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);

        if (ids.length === 0) return;

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Yakin ingin menghapus ${ids.length} anggota yang dipilih?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("admin.struktur-organisasi.bulk-delete") }}', {
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
                        Swal.fire('Berhasil!', data.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'Terjadi kesalahan saat menghapus data', 'error');
                });
            }
        });
    });

    // Delete confirmation
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Yakin ingin menghapus anggota ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Success/Error messages
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endpush
@endsection
