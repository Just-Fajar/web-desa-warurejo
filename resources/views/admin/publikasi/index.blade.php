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

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Publikasi</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Publikasi::count() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Published</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Publikasi::where('status', 'published')->count() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Draft</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Publikasi::where('status', 'draft')->count() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Dijadwalkan</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Publikasi::where('status', 'scheduled')->count() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 mb-6">
            <form method="GET" action="{{ route('admin.publikasi.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <!-- Search -->
                <div>
                    <input type="text" name="search" placeholder="Cari judul..." value="{{ request('search') }}"
                        class="w-full px-4 py-2.5 bg-white border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 text-sm font-semibold text-gray-900 placeholder-gray-500">
                </div>

                <!-- Kategori Filter -->
                <div>
                    <select name="kategori"
                        class="w-full px-4 py-2.5 bg-white border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900 text-sm font-semibold transition-all">
                        <option value="">Semua Kategori</option>
                        <option value="APBDes" {{ request('kategori') == 'APBDes' ? 'selected' : '' }}>APBDes</option>
                        <option value="RPJMDes" {{ request('kategori') == 'RPJMDes' ? 'selected' : '' }}>RPJMDes</option>
                        <option value="RKPDes" {{ request('kategori') == 'RKPDes' ? 'selected' : '' }}>RKPDes</option>
                    </select>
                </div>

                <!-- Tahun Filter -->
                <div>
                    <select name="tahun"
                        class="w-full px-4 py-2.5 bg-white border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900 text-sm font-semibold transition-all">
                        <option value="">Semua Tahun</option>
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status"
                        class="w-full px-4 py-2.5 bg-white border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900 text-sm font-semibold transition-all">
                        <option value="">Semua Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2.5 rounded-xl font-semibold transition text-sm flex items-center justify-center">
                        <i class="fas fa-search mr-1.5"></i>
                        Filter
                    </button>
                    <a href="{{ route('admin.publikasi.index') }}"
                        class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition flex items-center justify-center"
                        title="Reset Reset">
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
                <button onclick="bulkDelete()"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus Terpilih
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left w-12">
                                <!-- No Select All -->
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Judul
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tahun
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah
                                Lihat</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($publikasi as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <input type="checkbox"
                                        class="row-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-600"
                                        value="{{ $item->id }}">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-500 rounded-xl mr-4 shrink-0 shadow-sm border border-red-100">
                                            <i class="fas fa-file-pdf text-lg"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-800">{{ Str::limit($item->judul, 60) }}
                                            </div>
                                            @if($item->deskripsi)
                                                <div class="text-[11px] text-gray-500 mt-0.5 line-clamp-1">
                                                    {{ Str::limit($item->deskripsi, 60) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $kategoriColors = [
                                            'APBDes' => 'bg-sky-50 text-sky-600 border-sky-200',
                                            'RPJMDes' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                            'RKPDes' => 'bg-purple-50 text-purple-600 border-purple-200'
                                        ];
                                        $badgeClass = $kategoriColors[$item->kategori] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                                    @endphp
                                    <span
                                        class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-md border {{ $badgeClass }}">
                                        {{ $item->kategori }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-600">{{ $item->tahun }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->tanggal_publikasi)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <span
                                        class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ number_format($item->views) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @include('admin.partials._status_badge', ['status' => $item->status, 'publishedAt' => $item->published_at])
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('publikasi.show', $item->id) }}" target="_blank"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-500 hover:text-white transition-all duration-200"
                                            title="Lihat">
                                            <i class="fas fa-external-link-alt text-xs"></i>
                                        </a>
                                        <a href="{{ route('admin.publikasi.edit', $item->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-sky-50 text-sky-600 hover:bg-sky-500 hover:text-white transition-all duration-200"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.publikasi.destroy', $item->id) }}" method="POST"
                                            class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white transition-all duration-200 delete-btn"
                                                title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
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
                                    <a href="{{ route('admin.publikasi.create') }}"
                                        class="text-primary-600 hover:text-primary-700 mt-2 inline-block">
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