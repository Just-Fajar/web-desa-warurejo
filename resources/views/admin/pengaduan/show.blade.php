@extends('admin.layouts.app')

@section('title', 'Detail Pengaduan')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <a href="{{ route('admin.pengaduan.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Pengaduan</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-sm font-semibold text-gray-600">Detail</span>
        </div>
    </li>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <a href="{{ route('admin.pengaduan.index') }}"
                    class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-primary-600 font-medium mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Daftar
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Detail Pengaduan</h1>
            </div>
            <div class="flex items-center gap-2">
                {{-- Status Badge --}}
                @php $badge = $pengaduan->status_badge; @endphp
                <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold {{ $badge['bg'] }} {{ $badge['text'] }} border {{ $badge['border'] }}">
                    {{ $badge['label'] }}
                </span>
                {{-- WA Button --}}
                <a href="https://wa.me/{{ ltrim(preg_replace('/^0/', '62', $pengaduan->nomor_wa), '+') }}"
                    target="_blank"
                    class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl font-semibold text-sm transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Hubungi via WA
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Data Pengaduan --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-5">{{ $pengaduan->judul }}</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama Pelapor</p>
                            <p class="text-sm font-bold text-gray-800">{{ $pengaduan->nama_pelapor }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nomor WhatsApp</p>
                            <p class="text-sm font-bold text-gray-800">{{ $pengaduan->nomor_wa }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Lokasi Kejadian</p>
                            <p class="text-sm font-bold text-gray-800">{{ $pengaduan->lokasi_kejadian }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal Masuk</p>
                            <p class="text-sm font-bold text-gray-800">{{ $pengaduan->created_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    {{-- Isi Pengaduan --}}
                    <div class="border-t border-gray-100 pt-5">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Isi Pengaduan</h3>
                        <div class="text-gray-700 leading-relaxed text-sm">
                            {!! nl2br(e($pengaduan->isi)) !!}
                        </div>
                    </div>
                </div>

                {{-- Lampiran --}}
                @if($pengaduan->lampiran)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Lampiran</h3>

                        @if($pengaduan->isImage())
                            <div class="rounded-xl overflow-hidden border border-gray-100">
                                <img src="{{ $pengaduan->lampiran_url }}" alt="Lampiran" class="w-full max-h-96 object-contain bg-gray-50">
                            </div>
                        @elseif($pengaduan->isPdf())
                            <a href="{{ $pengaduan->lampiran_url }}" target="_blank"
                                class="inline-flex items-center gap-3 bg-red-50 border border-red-100 rounded-xl px-5 py-4 hover:bg-red-100 transition-colors">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-pdf text-red-600"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">Buka Lampiran PDF</p>
                                    <p class="text-xs text-gray-500">Klik untuk membuka</p>
                                </div>
                            </a>
                        @endif
                    </div>
                @endif

                {{-- Alasan Penolakan --}}
                @if($pengaduan->status === 'Ditolak' && $pengaduan->alasan_penolakan)
                    <div class="bg-red-50 rounded-2xl border border-red-200 p-6">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                                <i class="fas fa-ban text-red-600"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-red-700 uppercase tracking-wider mb-2">Alasan Penolakan</h3>
                                <p class="text-red-800 leading-relaxed text-sm">{{ $pengaduan->alasan_penolakan }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Riwayat Balasan --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-5">Riwayat Balasan</h3>

                    @if($pengaduan->balasan->count() > 0)
                        <div class="space-y-4">
                            @foreach($pengaduan->balasan as $balasan)
                                <div class="bg-green-50/60 rounded-xl border border-green-100 p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-green-600 flex items-center justify-center">
                                                <i class="fas fa-shield-alt text-white text-xs"></i>
                                            </div>
                                            <span class="text-sm font-bold text-green-800">Admin Desa</span>
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $balasan->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                    @if($balasan->isi)
                                        <div class="text-sm text-gray-700 leading-relaxed pl-9">
                                            {!! nl2br(e($balasan->isi)) !!}
                                        </div>
                                    @endif
                                    @if($balasan->lampiran && $balasan->isImage())
                                        <div class="pl-9 mt-3">
                                            <div class="rounded-xl overflow-hidden border border-green-200 max-w-sm">
                                                <img src="{{ $balasan->lampiran_url }}" alt="Bukti balasan" class="w-full object-contain bg-green-50/30">
                                            </div>
                                            <p class="text-[11px] text-gray-400 mt-1">📎 Lampiran bukti dari admin</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-comments text-4xl mb-3 text-gray-200"></i>
                            <p class="font-medium">Belum ada balasan</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar: Form Balas --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-5">Balas Pengaduan</h3>

                    <form action="{{ route('admin.pengaduan.balas', $pengaduan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        {{-- Balasan --}}
                        <div>
                            <label for="isi" class="block text-sm font-semibold text-gray-700 mb-1.5">Isi Balasan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <textarea name="isi" id="isi" rows="5"
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all text-sm resize-y"
                                placeholder="Tulis balasan untuk pengaduan ini...">{{ old('isi') }}</textarea>
                            @error('isi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-1.5">Update Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status" required
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all text-sm font-medium cursor-pointer">
                                @foreach(\App\Models\Pengaduan::getStatusList() as $value => $label)
                                    <option value="{{ $value }}" {{ $pengaduan->status === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Alasan Penolakan (muncul saat status Ditolak) --}}
                        <div id="alasanPenolakanWrapper" class="hidden">
                            <label for="alasan_penolakan" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Alasan Penolakan <span class="text-gray-400 font-normal">(Opsional)</span>
                            </label>
                            <textarea name="alasan_penolakan" id="alasan_penolakan" rows="3"
                                class="w-full px-4 py-3 rounded-xl border-2 border-red-200 focus:border-red-500 focus:ring-2 focus:ring-red-100 transition-all text-sm resize-y bg-red-50/50"
                                placeholder="Jelaskan alasan penolakan (opsional)...">{{ old('alasan_penolakan', $pengaduan->alasan_penolakan) }}</textarea>
                            <p class="text-xs text-gray-400 mt-1">Alasan akan tampil di halaman publik jika diisi.</p>
                            @error('alasan_penolakan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Lampiran Bukti --}}
                        <div>
                            <label for="lampiran_balasan" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Lampiran Bukti <span class="text-gray-400 font-normal">(Opsional)</span>
                            </label>
                            <input type="file" name="lampiran_balasan" id="lampiran_balasan" accept=".jpg,.jpeg,.png"
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100">
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, JPEG, PNG. Maks 5MB. Bisa digunakan sebagai bukti penyelesaian.</p>
                            @error('lampiran_balasan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="w-full bg-primary-600 text-white py-3 rounded-xl font-bold text-sm hover:bg-primary-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Kirim Balasan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Toggle Alasan Penolakan field
            const statusSelect = document.getElementById('status');
            const alasanWrapper = document.getElementById('alasanPenolakanWrapper');

            function toggleAlasanPenolakan() {
                if (statusSelect.value === 'Ditolak') {
                    alasanWrapper.classList.remove('hidden');
                } else {
                    alasanWrapper.classList.add('hidden');
                }
            }

            // Run on load and on change
            statusSelect.addEventListener('change', toggleAlasanPenolakan);
            toggleAlasanPenolakan();
        </script>
    @endpush
@endsection
