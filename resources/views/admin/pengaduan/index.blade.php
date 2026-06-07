@extends('admin.layouts.app')

@section('title', 'Kelola Pengaduan')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-sm font-semibold text-gray-600">Pengaduan</span>
        </div>
    </li>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Kelola Pengaduan</h1>
                <p class="text-sm text-gray-600 mt-1">Manajemen pengaduan masuk dari warga</p>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Pengaduan::count() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-bullhorn text-blue-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Menunggu</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Pengaduan::where('status', 'Menunggu')->count() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-sky-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Diproses</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Pengaduan::where('status', 'Diproses')->count() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-sky-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-spinner text-sky-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Selesai</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Pengaduan::where('status', 'Selesai')->count() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Ditolak</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Pengaduan::where('status', 'Ditolak')->count() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bulk Actions --}}
        <div id="bulk-actions" class="bg-rose-50 border border-rose-100 rounded-2xl p-4 mb-4 hidden">
            <div class="flex items-center justify-between">
                <span class="text-rose-700 font-semibold">
                    <span id="selectedCount">0</span> pengaduan dipilih
                </span>
                <button id="bulkDeleteBtn"
                    class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-xl font-semibold transition text-sm flex items-center">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus Terpilih
                </button>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Table Header --}}
            <div class="p-5 border-b border-gray-100 bg-white">
                <form method="GET" action="{{ route('admin.pengaduan.index') }}" class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari pengaduan..."
                                class="w-full pl-11 pr-4 py-2.5 bg-white border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 text-sm font-semibold text-gray-900 placeholder-gray-500">
                            <svg class="w-5 h-5 text-gray-600 absolute left-4 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <select name="status" id="statusFilter"
                            class="bg-white border-2 border-gray-300 text-gray-900 font-semibold text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full px-4 py-2.5 outline-none cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-white border-2 border-gray-300 hover:bg-gray-50 text-gray-700 hover:text-gray-900 text-sm font-semibold rounded-xl transition shrink-0 cursor-pointer">
                            Cari
                        </button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('admin.pengaduan.index') }}"
                                class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition flex items-center shrink-0">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100" id="pengaduanTable">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left w-12"></th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pengaduan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pelapor</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No. WA</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($pengaduan as $item)
                            <tr class="hover:bg-slate-50/50 pengaduan-row transition-colors duration-150"
                                data-status="{{ $item->status }}">
                                <td class="px-6 py-4">
                                    <input type="checkbox"
                                        class="pengaduan-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                        value="{{ $item->id }}">
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-bold text-gray-800">
                                            {{ Str::limit($item->judul, 50) }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-0.5 line-clamp-1">
                                            {{ Str::limit(strip_tags($item->isi), 80) }}
                                        </div>
                                        <div class="text-[11px] text-gray-400 mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $item->lokasi_kejadian }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                                    {{ $item->nama_pelapor }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <a href="https://wa.me/{{ ltrim(preg_replace('/^0/', '62', $item->nomor_wa), '+') }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1 text-green-600 hover:text-green-700 font-medium">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                        {{ $item->nomor_wa }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php $badge = $item->status_badge; @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $badge['bg'] }} {{ $badge['text'] }} border {{ $badge['border'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="font-medium text-gray-600">{{ $item->created_at->format('d M Y') }}</div>
                                    <div class="text-[11px] text-gray-400 mt-0.5">{{ $item->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.pengaduan.show', $item->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-sky-50 text-sky-600 hover:bg-sky-500 hover:text-white transition-all duration-200"
                                            title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.pengaduan.destroy', $item->id) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white transition-all duration-200 delete-btn"
                                                title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-lg font-medium mb-2">Belum ada pengaduan masuk</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pengaduan->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $pengaduan->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
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

                // Checkboxes
                document.querySelectorAll('.pengaduan-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', updateBulkDeleteButton);
                });

                function updateBulkDeleteButton() {
                    const checkedBoxes = document.querySelectorAll('.pengaduan-checkbox:checked');
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
                const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
                if (bulkDeleteBtn) {
                    bulkDeleteBtn.addEventListener('click', function() {
                        const checkedBoxes = document.querySelectorAll('.pengaduan-checkbox:checked');
                        const ids = Array.from(checkedBoxes).map(cb => cb.value);

                        Swal.fire({
                            title: 'Hapus Pengaduan Terpilih?',
                            html: `Anda akan menghapus <strong>${ids.length} pengaduan</strong> yang dipilih.<br>Data yang dihapus tidak dapat dikembalikan!`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#EF4444',
                            cancelButtonColor: '#6B7280',
                            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus Semua!',
                            cancelButtonText: 'Batal',
                            showClass: { popup: 'animate__animated animate__fadeInDown animate__faster' },
                            hideClass: { popup: 'animate__animated animate__fadeOutUp animate__faster' }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'Menghapus...',
                                    html: 'Mohon tunggu sebentar',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: () => Swal.showLoading()
                                });

                                fetch('{{ route("admin.pengaduan.bulk-delete") }}', {
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
                                            text: data.message,
                                            confirmButtonColor: '#10B981',
                                            showClass: { popup: 'animate__animated animate__bounceIn' }
                                        }).then(() => window.location.reload());
                                    } else {
                                        Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message, confirmButtonColor: '#EF4444' });
                                    }
                                })
                                .catch(() => {
                                    Swal.fire({ icon: 'error', title: 'Error!', text: 'Terjadi kesalahan.', confirmButtonColor: '#EF4444' });
                                });
                            }
                        });
                    });
                }
            });
        </script>
    @endpush
@endsection
