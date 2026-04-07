{{--
    ADMIN PUBLIKASI INDEX
    
    List management dokumen publikasi desa
    
    FEATURES:
    - Statistics cards (Total/Per Kategori)
    - Search filter (judul)
    - Kategori filter (APBDes/RPJMDes/RKPDes/Lainnya)
    - Tahun filter (dropdown years)
    - Status filter (Aktif/Tidak Aktif)
    - Sort options (Terbaru/Terlama/A-Z)
    - Pagination (10 items per page)
    - Bulk delete dengan checkbox
    - Download counter per dokumen
    
    TABLE COLUMNS:
    - Judul dokumen
    - Kategori badge (color coded)
    - Tahun
    - File info (size, format)
    - Downloads counter
    - Tanggal terbit
    - Status badge
    - Actions (View/Download/Edit/Delete)
    
    QUICK ACTIONS:
    - View PDF (new tab)
    - Download PDF (force download)
    - Edit dokumen
    - Delete dengan confirmation
    
    BULK ACTIONS:
    - Select all checkbox
    - Bulk delete selected
    - Confirmation modal
    
    DATA:
    $publikasi: Paginated collection
    $totalByKategori: Statistics per kategori
    
    Route: /admin/publikasi
    Controller: AdminPublikasiController@index
--}}
@extends('admin.layouts.app')

@section('title', 'Kelola Publikasi')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Kelola Publikasi</h1>
            <p class="text-gray-600">Kelola dokumen publikasi desa (APBDes, RPJMDes, RKPDes)</p>
        </div>
        <a href="{{ route('admin.publikasi.create') }}" 
           class="mt-4 md:mt-0 bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-lg font-semibold transition duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Upload Publikasi
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.publikasi.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <input type="text" 
                       name="search" 
                       placeholder="Cari judul..." 
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent">
            </div>

            <!-- Kategori Filter -->
            <div>
                <select name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                    <option value="">Semua Kategori</option>
                    <option value="APBDes" {{ request('kategori') == 'APBDes' ? 'selected' : '' }}>APBDes</option>
                    <option value="RPJMDes" {{ request('kategori') == 'RPJMDes' ? 'selected' : '' }}>RPJMDes</option>
                    <option value="RKPDes" {{ request('kategori') == 'RKPDes' ? 'selected' : '' }}>RKPDes</option>
                </select>
            </div>

            <!-- Tahun Filter -->
            <div>
                <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                    <option value="">Semua Tahun</option>
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            {{-- FITUR STATUS BELUM DIPAKAI - JANGAN HAPUS
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            --}}

            <!-- Filter Button -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-search mr-1"></i>
                    Filter
                </button>
                <a href="{{ route('admin.publikasi.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div id="bulk-actions" class="bg-primary-50 border border-primary-200 rounded-lg p-4 mb-4 hidden">
        <div class="flex items-center justify-between">
            <span class="text-primary-700 font-semibold">
                <span id="selected-count">0</span> publikasi dipilih
            </span>
            <button onclick="bulkDelete()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                <i class="fas fa-trash mr-2"></i>
                Hapus Terpilih
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                        {{-- FITUR STATUS BELUM DIPAKAI - JANGAN HAPUS
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        --}}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Lihat</th>
                        {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Unduh</th> --}}
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($publikasi as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="row-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-600" value="{{ $item->id }}">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <i class="fas fa-file-pdf text-red-500 text-xl mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $item->judul }}</div>
                                    @if($item->deskripsi)
                                        <div class="text-xs text-gray-500">{{ Str::limit($item->deskripsi, 50) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $item->kategori === 'APBDes' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $item->kategori === 'RPJMDes' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $item->kategori === 'RKPDes' ? 'bg-purple-100 text-purple-800' : '' }}">
                                {{ $item->kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->tahun }}</td>
                        {{-- FITUR STATUS BELUM DIPAKAI - JANGAN HAPUS
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $item->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        --}}
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($item->tanggal_publikasi)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <i class="far fa-eye text-gray-400 mr-1"></i>
                            {{ number_format($item->views) }}
                        </td>
                        {{-- <td class="px-6 py-4 text-sm text-gray-900">
                            <i class="fas fa-download text-gray-400 mr-1"></i>
                            {{ number_format($item->jumlah_download) }}
                        </td> --}}
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('publikasi.show', $item->id) }}" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800 transition"
                                   title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.publikasi.edit', $item->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-800 transition"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.publikasi.destroy', $item->id) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-red-600 hover:text-red-800 transition delete-btn" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                            <p class="text-lg">Tidak ada publikasi</p>
                            <a href="{{ route('admin.publikasi.create') }}" class="text-primary-600 hover:text-primary-700 mt-2 inline-block">
                                Upload publikasi pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($publikasi->hasPages())
    <div class="mt-6">
        {{ $publikasi->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Select All Checkbox
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    updateBulkActions();
});

// Individual Checkbox
document.querySelectorAll('.row-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkActions);
});

function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    if (checkedBoxes.length > 0) {
        bulkActions.classList.remove('hidden');
        selectedCount.textContent = checkedBoxes.length;
    } else {
        bulkActions.classList.add('hidden');
    }
}

function bulkDelete() {
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    const ids = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Tidak Ada Pilihan',
            text: 'Silakan pilih publikasi yang ingin dihapus terlebih dahulu',
            confirmButtonColor: '#3B82F6'
        });
        return;
    }
    
    Swal.fire({
        title: 'Hapus Publikasi?',
        html: `Anda akan menghapus <strong>${ids.length} publikasi</strong> yang dipilih.<br>Data yang dihapus tidak dapat dikembalikan!`,
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
            
            fetch('{{ route("admin.publikasi.bulk-delete") }}', {
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
                        text: `${ids.length} publikasi telah dihapus`,
                        confirmButtonColor: '#10B981',
                        showClass: {
                            popup: 'animate__animated animate__bounceIn'
                        }
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan saat menghapus',
                        confirmButtonColor: '#EF4444'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghapus',
                    confirmButtonColor: '#EF4444'
                });
            });
        }
    });
}

// Single Delete
function deletePublikasi(id, judul) {
    Swal.fire({
        title: 'Hapus Publikasi?',
        html: `Anda akan menghapus publikasi:<br><strong class="text-red-600">${judul}</strong><br><br>Data yang dihapus tidak dapat dikembalikan!`,
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
            form.action = `/admin/publikasi/${id}`;
            form.submit();
        }
    });
}
</script>
@endpush
@endsection
