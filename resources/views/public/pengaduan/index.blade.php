@extends('public.layouts.app')

@section('title', 'Forum Pengaduan')

@section('content')

    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-br from-primary-700 via-primary-600 to-primary-800 pt-32 pb-20 overflow-hidden">
        {{-- Deco shapes --}}
        <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-20 w-96 h-96 bg-primary-400/10 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <p class="text-primary-200 text-sm font-semibold tracking-[0.2em] uppercase mb-4">Transparansi Publik</p>
                <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">Forum Pengaduan</h1>
                <p class="text-primary-100 text-lg md:text-xl leading-relaxed mb-8">
                    Sampaikan aspirasi, keluhan, atau laporan Anda secara terbuka.<br class="hidden md:block">
                    Kami berkomitmen untuk merespons setiap pengaduan yang masuk.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <button onclick="document.getElementById('formModal').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 bg-white text-primary-700 px-6 py-3 rounded-full font-bold hover:bg-primary-50 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Pengaduan
                    </button>
                    <a href="https://wa.me/62085168687700?text=Halo%20Admin%20Desa%20Warurejo,%20saya%20ingin%20menyampaikan%20pengaduan" target="_blank"
                        class="inline-flex items-center gap-2 bg-green-500 text-white px-6 py-3 rounded-full font-bold hover:bg-green-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Pengaduan atau tanya jawab Private via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats Mini --}}
    <section class="bg-white border-b border-gray-100">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 -mt-8 relative z-20 gap-3 md:gap-4">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 text-center">
                    <div class="text-2xl font-extrabold text-gray-800">{{ $totalPengaduan }}</div>
                    <div class="text-xs font-semibold text-gray-500 mt-1">Total Laporan</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg border border-yellow-100 p-4 text-center">
                    <div class="text-2xl font-extrabold text-yellow-600">{{ $totalMenunggu }}</div>
                    <div class="text-xs font-semibold text-gray-500 mt-1">Menunggu</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-4 text-center">
                    <div class="text-2xl font-extrabold text-blue-600">{{ $totalDiproses }}</div>
                    <div class="text-xs font-semibold text-gray-500 mt-1">Diproses</div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg border border-green-100 p-4 text-center">
                    <div class="text-2xl font-extrabold text-green-600">{{ $totalSelesai }}</div>
                    <div class="text-xs font-semibold text-gray-500 mt-1">Selesai</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Filter & List --}}
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="container mx-auto px-4">

            {{-- Filter Chips --}}
            <div class="flex flex-wrap gap-2 mb-8">
                <a href="{{ route('pengaduan.index') }}"
                    class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ !request('status') ? 'bg-primary-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:border-primary-300 hover:text-primary-600' }}">
                    Semua
                </a>
                @foreach(['Menunggu' => 'yellow', 'Diproses' => 'blue', 'Selesai' => 'green', 'Ditolak' => 'red'] as $status => $color)
                    <a href="{{ route('pengaduan.index', ['status' => $status]) }}"
                        class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ request('status') === $status ? 'bg-' . $color . '-500 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:border-' . $color . '-300 hover:text-' . $color . '-600' }}">
                        {{ $status }}
                    </a>
                @endforeach
            </div>

            {{-- Pengaduan List --}}
            <div class="space-y-4">
                @forelse($pengaduan as $item)
                    <a href="{{ route('pengaduan.show', $item->id) }}"
                        class="block bg-white rounded-2xl p-5 md:p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:border-primary-100 transition-all duration-300 group ring-1 ring-black/5 hover:ring-primary-500/20">

                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    {{-- Status Badge --}}
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $item->status_badge['bg'] }} {{ $item->status_badge['text'] }} border {{ $item->status_badge['border'] }}">
                                        {{ $item->status_badge['label'] }}
                                    </span>
                                    {{-- Jumlah Balasan --}}
                                    @if($item->balasan_count > 0)
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full border border-green-100">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            {{ $item->balasan_count }} balasan
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-lg font-bold text-gray-800 group-hover:text-primary-600 transition-colors mb-2 line-clamp-1">
                                    {{ $item->judul }}
                                </h3>

                                <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                                    {{ Str::limit(strip_tags($item->isi), 200) }}
                                </p>

                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $item->nama_sensor }}
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $item->lokasi_kejadian }}
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $item->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Arrow --}}
                            <div class="hidden md:flex items-center">
                                <span class="w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center group-hover:bg-primary-600 group-hover:text-white transition-all duration-300 transform group-hover:translate-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                        <svg class="w-20 h-20 mx-auto text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="text-lg font-bold text-gray-400 mb-2">Belum ada pengaduan</h3>
                        <p class="text-gray-400 text-sm mb-6">Jadilah yang pertama menyampaikan aspirasi Anda.</p>
                        <button onclick="document.getElementById('formModal').classList.remove('hidden')"
                            class="inline-flex items-center gap-2 bg-primary-600 text-white px-6 py-3 rounded-full font-bold hover:bg-primary-700 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Pengaduan Pertama
                        </button>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($pengaduan->hasPages())
                <div class="mt-8">
                    {{ $pengaduan->links() }}
                </div>
            @endif
        </div>
    </section>

    {{-- ===========================
         MODAL FORM BUAT PENGADUAN
    ============================ --}}
    <div id="formModal" class="fixed inset-0 z-[110] hidden">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="document.getElementById('formModal').classList.add('hidden')"></div>

        {{-- Modal Content --}}
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto relative">

                {{-- Close Button --}}
                <button onclick="document.getElementById('formModal').classList.add('hidden')"
                    class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 hover:text-gray-700 transition-colors z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Header --}}
                <div class="p-6 pb-0">
                    <h2 class="text-2xl font-extrabold text-gray-800 mb-1">Buat Pengaduan</h2>
                    <p class="text-sm text-gray-500">Sampaikan aspirasi atau keluhan Anda. Data pribadi akan disensor di tampilan publik.</p>
                </div>

                {{-- Form --}}
                <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                    @csrf

                    {{-- Nama Pelapor --}}
                    <div>
                        <label for="nama_pelapor" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Pelapor <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pelapor" id="nama_pelapor" value="{{ old('nama_pelapor') }}" required
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all text-sm font-medium"
                            placeholder="Nama lengkap Anda">
                        @error('nama_pelapor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nomor WA --}}
                    <div>
                        <label for="nomor_wa" class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" name="nomor_wa" id="nomor_wa" value="{{ old('nomor_wa') }}" required
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all text-sm font-medium"
                            placeholder="08xxx atau +628xxx">
                        <p class="text-xs text-gray-400 mt-1">Tidak ditampilkan di publik, hanya untuk keperluan admin.</p>
                        @error('nomor_wa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Judul --}}
                    <div>
                        <label for="judul" class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Pengaduan <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all text-sm font-medium"
                            placeholder="Ringkas dan jelas">
                        @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Isi --}}
                    <div>
                        <label for="isi" class="block text-sm font-semibold text-gray-700 mb-1.5">Isi Pengaduan <span class="text-red-500">*</span></label>
                        <textarea name="isi" id="isi" rows="5" required
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all text-sm font-medium resize-y"
                            placeholder="Jelaskan pengaduan Anda secara detail...">{{ old('isi') }}</textarea>
                        @error('isi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Lokasi --}}
                    <div>
                        <label for="lokasi_kejadian" class="block text-sm font-semibold text-gray-700 mb-1.5">Lokasi Kejadian <span class="text-red-500">*</span></label>
                        <input type="text" name="lokasi_kejadian" id="lokasi_kejadian" value="{{ old('lokasi_kejadian') }}" required
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all text-sm font-medium"
                            placeholder="Contoh: RT 02/RW 01, Dusun Krajan">
                        @error('lokasi_kejadian') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Lampiran --}}
                    <div>
                        <label for="lampiran" class="block text-sm font-semibold text-gray-700 mb-1.5">Lampiran <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="file" name="lampiran" id="lampiran" accept=".jpg,.jpeg,.png,.pdf"
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100">
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, JPEG, PNG, atau PDF. Maksimal 5MB.</p>
                        @error('lampiran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full bg-primary-600 text-white py-3.5 rounded-xl font-bold text-sm hover:bg-primary-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Kirim Pengaduan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Auto-open modal if validation errors --}}
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('formModal').classList.remove('hidden');
            });
        </script>
    @endif

    {{-- Success message via SweetAlert --}}
    @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    iconColor: '#22c55e',
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: 'OK',
                    timer: 4000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

@endsection
