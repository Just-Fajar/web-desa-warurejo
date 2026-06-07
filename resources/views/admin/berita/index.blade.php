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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Berita
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Berita</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Berita::count() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Published</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Berita::where('status', 'published')->count() }}
                        </h3>
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
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Berita::where('status', 'draft')->count() }}</h3>
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
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Berita::where('status', 'scheduled')->count() }}</h3>
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

        <!-- Bulk Actions -->
        <div id="bulk-actions" class="bg-rose-50 border border-rose-100 rounded-2xl p-4 mb-4 hidden">
            <div class="flex items-center justify-between">
                <span class="text-rose-700 font-semibold">
                    <span id="selectedCount">0</span> berita dipilih
                </span>
                <button id="bulkDeleteBtn"
                    class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-xl font-semibold transition text-sm flex items-center">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus Terpilih
                </button>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Table Header with Bulk Actions -->
            <div class="p-5 border-b border-gray-100 bg-white">
                <form method="GET" action="{{ route('admin.berita.index') }}" class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Search -->
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <input type="text" name="search" id="searchInput" placeholder="Cari berita..." value="{{ request('search') }}"
                                class="w-full pl-11 pr-4 py-2.5 bg-white border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 text-sm font-semibold text-gray-900 placeholder-gray-500">
                            <svg class="w-5 h-5 text-gray-600 absolute left-4 top-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Filter -->
                    <div class="flex gap-2">
                        <select name="status" id="statusFilter" class="bg-white border-2 border-gray-300 text-gray-900 font-semibold text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full px-4 py-2.5 outline-none cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                        </select>
                        <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-white border-2 border-gray-300 hover:bg-gray-50 text-gray-700 hover:text-gray-900 text-sm font-semibold rounded-xl transition shrink-0 cursor-pointer">
                            Cari
                        </button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition shrink-0">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100" id="beritaTable">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left w-12">
                                <!-- No select all -->
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Berita
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Views
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($berita as $item)
                            <tr class="hover:bg-slate-50/50 berita-row transition-colors duration-150"
                                data-status="{{ $item->status }}">
                                <td class="px-6 py-4">
                                    <input type="checkbox"
                                        class="berita-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                        value="{{ $item->id }}">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <a href="{{ route('admin.berita.show', $item->id) }}"
                                            class="shrink-0 h-16 w-16 mr-4 relative rounded-xl overflow-hidden shadow-sm border border-gray-100 block hover:opacity-80 transition-opacity">
                                            <img class="h-16 w-16 object-cover" src="{{ $item->gambar_utama_url }}"
                                                alt="{{ $item->judul }}"
                                                data-fallback="{{ asset('images/default-berita.jpg') }}">
                                        </a>
                                        <div>
                                            <div class="text-sm font-bold text-gray-800">
                                                <a href="{{ route('admin.berita.show', $item->id) }}" class="hover:text-primary-600 transition-colors">
                                                    {{ Str::limit($item->judul, 60) }}
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5 line-clamp-1">
                                                {{ Str::limit($item->excerpt, 80) }}
                                            </div>
                                            <div
                                                class="text-[11px] text-gray-400 mt-1 flex items-center bg-gray-50 w-max px-2 py-0.5 rounded-md border border-gray-100">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                {{ $item->admin->name ?? 'Unknown' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <i class="far fa-eye mr-1"></i>
                                    {{ number_format($item->views ?? 0) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="font-medium text-gray-600">
                                        {{ optional($item->created_at)->format('d M Y') ?? '-' }}</div>
                                    <div class="text-[11px] text-gray-400 mt-0.5">
                                        {{ optional($item->created_at)->format('H:i') ?? '-' }} WIB</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @include('admin.partials._status_badge', ['status' => $item->status, 'publishedAt' => $item->published_at])
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.berita.show', $item->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-all duration-200"
                                            title="Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.berita.edit', $item->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-sky-50 text-sky-600 hover:bg-sky-500 hover:text-white transition-all duration-200"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST"
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
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                    <p class="text-lg font-medium mb-2">Belum ada berita</p>
                                    <a href="{{ route('admin.berita.create') }}"
                                        class="text-primary-600 hover:text-primary-800">
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
                    {{ $berita->appends(request()->query())->links() }}
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
        <script @nonce>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('searchInput');
                const statusFilter = document.getElementById('statusFilter');

                // Keep cursor at the end of the search input on reload
                if (searchInput && searchInput.value) {
                    searchInput.focus();
                    const val = searchInput.value;
                    searchInput.value = '';
                    searchInput.value = val;
                }

                // Individual Checkboxes
                document.querySelectorAll('.berita-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', updateBulkDeleteButton);
                });

                function updateBulkDeleteButton() {
                    const checkedBoxes = document.querySelectorAll('.berita-checkbox:checked');
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
            document.getElementById('bulkDeleteBtn').addEventListener('click', function () {
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
            */
        </script>
    @endpush
@endsection