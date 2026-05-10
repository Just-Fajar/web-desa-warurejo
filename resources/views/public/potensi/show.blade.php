@extends('public.layouts.app')

@section('title', $potensi->nama . ' - Potensi Desa Warurejo')

@section('content')
    @php $colors = $kategoriBadgeColors[$potensi->kategori] ?? ['bg' => '#F1EFE8', 'text' => '#444441']; @endphp

    {{-- Breadcrumb --}}
    <section class="bg-gray-100 pt-24 pb-3 sm:pt-28 sm:pb-4 lg:pt-32">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center flex-wrap text-xs sm:text-sm text-gray-600 scroll-reveal overflow-hidden min-w-0">
                <a href="{{ route('home') }}" class="hover:text-emerald-600 shrink-0">Beranda</a>
                <svg class="w-3 h-3 sm:w-4 sm:h-4 mx-1.5 sm:mx-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
                <a href="{{ route('potensi.index') }}" class="hover:text-emerald-600 shrink-0">Potensi Desa</a>
                <svg class="w-3 h-3 sm:w-4 sm:h-4 mx-1.5 sm:mx-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-gray-800 font-semibold truncate min-w-0">{{ Str::limit($potensi->nama, 40) }}</span>
            </div>
        </div>
    </section>

    {{-- Article Content --}}
    <section class="py-6 sm:py-8 md:py-12 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-[1100px]">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

                {{-- Main Article (Left Column) --}}
                <div class="lg:col-span-8">
                    <article class="bg-white scroll-reveal">

                        {{-- Title & Header Info --}}
                        <h1
                            class="text-[28px] sm:text-[32px] md:text-[38px] font-bold text-[#003366] mb-4 leading-tight tracking-tight">
                            {{ $potensi->nama }}
                        </h1>

                        <div
                            class="flex flex-wrap items-center text-[13px] sm:text-[14px] text-gray-500 mb-6 font-medium gap-y-1">
                            <span class="text-gray-800">{{ $potensi->admin->name ?? 'Administrator' }}</span>
                            <span class="mx-2 text-gray-300">-</span>
                            <span class="font-semibold"
                                style="color: {{ $colors['text'] }}">{{ ucfirst($potensi->kategori) }}</span>
                            <span class="mx-2 text-gray-300">|</span>
                            <span>{{ \Carbon\Carbon::parse($potensi->published_at ?? $potensi->created_at)->locale('id')->isoFormat('dddd, D MMM Y HH:mm') }}
                                WIB</span>
                        </div>

                        {{-- Featured Image --}}
                        <div class="relative w-full mb-3">
                            <img src="{{ $potensi->gambar_url }}" alt="{{ $potensi->nama }}"
                                class="w-full object-cover rounded-sm" loading="lazy">
                        </div>
                        <div class="text-[11px] sm:text-[12px] text-gray-500 italic mb-8 border-b border-gray-100 pb-4">
                            Foto: {{ $potensi->nama }} (Dok. Desa Warurejo)
                        </div>

                        {{-- Content --}}
                        <div class="prose prose-sm sm:prose-base lg:prose-lg max-w-none text-[#333] leading-relaxed break-words overflow-hidden mb-8">
                            <span class="font-bold">Madiun</span> - {!! $potensi->deskripsi !!}
                        </div>
                        {{-- Informasi Potensi --}}
                        @if($potensi->lokasi || $potensi->info_utama || $potensi->nama_pengelola || $potensi->whatsapp)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-8">
                                <h3 class="text-xl font-bold text-[#003366] mb-5 border-b-2 border-[#003366] pb-2 inline-block">
                                    Informasi Potensi
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 divide-y sm:divide-y-0 divide-gray-200 text-sm">
                                    @if($potensi->lokasi)
                                        <div class="flex items-start gap-3 pt-4 sm:pt-0">
                                            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shrink-0 border border-gray-200">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="block text-gray-500 font-semibold mb-0.5">Lokasi</span>
                                                <span class="font-bold text-gray-900">{{ $potensi->lokasi }}</span>
                                                @if($potensi->link_maps)
                                                    @php $mapsUrl = preg_match('/src="([^"]+)"/', $potensi->link_maps, $matches) ? $matches[1] : $potensi->link_maps; @endphp
                                                    <div class="mt-1">
                                                        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener"
                                                            class="inline-flex items-center text-[#003366] hover:text-blue-700 text-xs font-semibold transition-colors">
                                                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/aa/Google_Maps_icon_%282020%29.svg" class="w-3 h-3 mr-1.5" alt="Google Maps">
                                                            Lihat di Google Maps
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if($potensi->info_utama)
                                        <div class="flex items-start gap-3 pt-4 sm:pt-0">
                                            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shrink-0 border border-gray-200">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="block text-gray-500 font-semibold mb-0.5">Info Utama</span>
                                                <span class="font-bold text-gray-900">{{ $potensi->info_utama }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if($potensi->nama_pengelola)
                                        <div class="flex items-start gap-3 pt-4 sm:pt-0 sm:mt-4 sm:border-t sm:border-gray-200">
                                            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shrink-0 border border-gray-200">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="block text-gray-500 font-semibold mb-0.5">Pengelola</span>
                                                <span class="font-bold text-gray-900">{{ $potensi->nama_pengelola }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if($potensi->whatsapp)
                                        <div class="flex items-start gap-3 pt-4 sm:pt-0 sm:mt-4 sm:border-t sm:border-gray-200">
                                            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shrink-0 border border-gray-200">
                                                <svg class="w-4 h-4 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="block text-gray-500 font-semibold mb-0.5">Kontak WhatsApp</span>
                                                <a href="{{ $potensi->whatsapp_link }}" target="_blank" rel="noopener"
                                                    class="font-bold text-gray-900 hover:text-emerald-600 transition-colors">
                                                    {{ $potensi->whatsapp_formatted }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Foto Galeri Potensi --}}
                        @if($potensi->fotoGaleri && $potensi->fotoGaleri->count() > 0)
                            <div class="mt-10 mb-8">
                                <h3 class="text-xl font-bold text-[#003366] mb-4">Galeri Foto</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @foreach($potensi->fotoGaleri as $index => $foto)
                                        @if($index < 6)
                                            <div class="relative rounded-lg overflow-hidden cursor-pointer group h-32 sm:h-40"
                                                onclick="openLightbox({{ $index }})">
                                                <img src="{{ $foto->foto_url }}" alt="Galeri {{ $index + 1 }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition"></div>
                                                @if($index === 5 && $potensi->fotoGaleri->count() > 6)
                                                    <div
                                                        class="absolute inset-0 bg-black/60 flex items-center justify-center backdrop-blur-sm">
                                                        <span
                                                            class="text-white text-xl sm:text-2xl font-bold">+{{ $potensi->fotoGaleri->count() - 6 }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Social Share Buttons --}}
                        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center gap-4">
                            <span class="text-sm font-bold text-[#003366]">Bagikan:</span>
                            <div class="flex items-center gap-2">
                                <!-- WhatsApp -->
                                <a href="https://wa.me/?text={{ urlencode($potensi->nama . ' - ' . url()->current()) }}"
                                    target="_blank"
                                    class="w-10 h-10 rounded-full flex items-center justify-center bg-[#25D366] text-white hover:opacity-80 transition hover:-translate-y-1 hover:shadow-md"
                                    title="Bagikan ke WhatsApp">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                    </svg>
                                </a>
                                <!-- Facebook -->
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                    target="_blank"
                                    class="w-10 h-10 rounded-full flex items-center justify-center bg-[#1877F2] text-white hover:opacity-80 transition hover:-translate-y-1 hover:shadow-md"
                                    title="Bagikan ke Facebook">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </a>
                                <!-- X (Twitter) -->
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($potensi->nama) }}"
                                    target="_blank"
                                    class="w-10 h-10 rounded-full flex items-center justify-center bg-black text-white hover:opacity-80 transition hover:-translate-y-1 hover:shadow-md"
                                    title="Bagikan ke X">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                    </svg>
                                </a>
                                <!-- Instagram -->
                                <a href="#"
                                    onclick="event.preventDefault(); navigator.clipboard.writeText(window.location.href); alert('Tautan halaman ini tersalin ke clipboard! Silahkan buka aplikasi Instagram Anda.');"
                                    class="w-10 h-10 rounded-full flex items-center justify-center bg-linear-to-tr from-[#f09433] via-[#dc2743] to-[#bc1888] text-white hover:opacity-80 transition hover:-translate-y-1 hover:shadow-md"
                                    title="Bagikan ke Instagram (Salin Tautan)">
                                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                                    </svg>
                                </a>
                                <!-- Telegram -->
                                <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($potensi->nama) }}"
                                    target="_blank"
                                    class="w-10 h-10 rounded-full flex items-center justify-center bg-[#0088cc] text-white hover:opacity-80 transition hover:-translate-y-1 hover:shadow-md"
                                    title="Bagikan ke Telegram">
                                    <svg class="w-5 h-5 ml-[-2px]" fill="currentColor" viewBox="0 0 448 512">
                                        <path
                                            d="M446.7 98.6l-67.6 318.8c-5.1 22.5-18.4 28.1-37.3 17.5l-103-75.9-49.7 47.8c-5.5 5.5-10.1 10.1-20.7 10.1l7.4-104.9 190.9-172.5c8.3-7.4-1.8-11.5-12.9-4.1L117.8 284 16.2 252.2c-22.1-6.9-22.5-22.1 4.6-32.7L418.2 66.4c18.4-6.9 34.5 4.1 28.5 32.2z" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                    </article>

                    {{-- Rekomendasi Text List --}}
                    @if(isset($relatedPotensi) && $relatedPotensi->count() > 0)
                        <div class="mt-12 pt-8 border-t-[3px] border-[#003366]">
                            <h2 class="text-xl font-bold text-[#003366] mb-6">Rekomendasi Potensi</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                                @foreach($relatedPotensi->take(4) as $rekomendasi)
                                    <div class="border-b border-gray-200 pb-3">
                                        <a href="{{ route('potensi.show', $rekomendasi->slug) }}" class="flex group">
                                            @if($rekomendasi->gambar_url)
                                                <div class="w-20 h-16 shrink-0 bg-gray-100 rounded-md overflow-hidden mr-3">
                                                    <img src="{{ $rekomendasi->gambar_url }}"
                                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <h3
                                                    class="text-[14px] font-bold text-gray-900 group-hover:text-[#003366] leading-snug line-clamp-3">
                                                    {{ $rekomendasi->nama }}
                                                </h3>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Potensi Lainnya Grid --}}
                    @if(isset($relatedPotensi) && $relatedPotensi->count() > 0)
                        <div class="mt-10 pt-8 border-t border-gray-200 mb-10">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-bold text-[#003366]">Potensi Lainnya</h2>
                                <a href="{{ route('potensi.index') }}"
                                    class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">Selengkapnya
                                    &rarr;</a>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                @foreach($relatedPotensi->shuffle()->take(3) as $lainnya)
                                    <a href="{{ route('potensi.show', $lainnya->slug) }}" class="group block">
                                        <div class="w-full h-36 bg-gray-200 rounded-lg overflow-hidden mb-3">
                                            @if($lainnya->gambar_url)
                                                <img src="{{ $lainnya->gambar_url }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                            @endif
                                        </div>
                                        @php $lainnyaColors = $kategoriBadgeColors[$lainnya->kategori] ?? ['bg' => '#F1EFE8', 'text' => '#444441']; @endphp
                                        <p class="text-[13px] mb-1 font-semibold" style="color: {{ $lainnyaColors['text'] }}">
                                            {{ ucfirst($lainnya->kategori) }}</p>
                                        <h3
                                            class="text-[15px] font-bold text-gray-900 group-hover:text-[#003366] leading-tight line-clamp-2">
                                            {{ $lainnya->nama }}
                                        </h3>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar (Right Column) --}}
                <div class="lg:col-span-4 mt-8 lg:mt-0">
                    <div class="sticky top-24">

                        {{-- Potensi Terkait Sidebar --}}
                        @if(isset($relatedPotensi) && $relatedPotensi->count() > 0)
                            <div class="mb-8">
                                <h2 class="text-xl font-bold text-[#003366] mb-5 border-b-2 border-[#003366] pb-2 inline-block">
                                    Potensi Terkait</h2>
                                <div class="space-y-4">
                                    @foreach($relatedPotensi->take(5) as $index => $terkait)
                                        <a href="{{ route('potensi.show', $terkait->slug) }}" class="flex group">
                                            <div
                                                class="text-3xl font-extrabold text-gray-300 mr-4 mt-1 italic group-hover:text-emerald-600 transition">
                                                #{{ $index + 1 }}
                                            </div>
                                            <div>
                                                <h3
                                                    class="text-[15px] font-bold text-gray-900 group-hover:text-[#003366] leading-snug">
                                                    {{ $terkait->nama }}
                                                </h3>
                                            </div>
                                        </a>
                                        @if(!$loop->last)
                                            <div class="border-b border-gray-100"></div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="mt-6 text-center">
                                    <a href="{{ route('potensi.index') }}"
                                        class="inline-block bg-gray-100 hover:bg-gray-200 text-emerald-600 font-semibold text-sm py-2 px-6 rounded-md transition">Lihat
                                        Selengkapnya &rarr;</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Lightbox (for Gallery) --}}
    @if($potensi->fotoGaleri && $potensi->fotoGaleri->count() > 0)
        <div id="lightbox" class="fixed inset-0 z-50 bg-black/90 hidden items-center justify-center"
            onclick="closeLightbox(event)">
            <button onclick="closeLightbox()"
                class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 z-10">&times;</button>
            <button onclick="prevSlide()" class="absolute left-4 text-white text-4xl hover:text-gray-300 z-10">&#8249;</button>
            <button onclick="nextSlide()" class="absolute right-4 text-white text-4xl hover:text-gray-300 z-10">&#8250;</button>
            <img id="lightboxImg" src="" class="max-w-[90vw] max-h-[85vh] object-contain rounded-lg shadow-2xl">
            <div class="absolute bottom-4 text-white text-sm" id="lightboxCounter"></div>
        </div>
    @endif

    <style>
        /* Detik.com inspired typography */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', sans-serif;
        }

        .prose img, .prose iframe {
            max-width: 100% !important;
            height: auto;
        }
        
        .prose table {
            display: block;
            width: 100%;
            overflow-x: auto;
        }

        .scroll-reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .scroll-reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observerOptions = { root: null, threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.scroll-reveal').forEach(el => {
                observer.observe(el);
            });
        });

        // Gallery Lightbox Logic
        @if($potensi->fotoGaleri && $potensi->fotoGaleri->count() > 0)
            const galeriImages = @json($potensi->fotoGaleri->pluck('foto_url'));
            let currentSlide = 0;
            function openLightbox(i) { currentSlide = i; document.getElementById('lightbox').classList.remove('hidden'); document.getElementById('lightbox').classList.add('flex'); updateLightbox(); document.body.style.overflow = 'hidden'; }
            function closeLightbox(e) { if (e && e.target !== e.currentTarget && !e.target.closest('button')) return; document.getElementById('lightbox').classList.add('hidden'); document.getElementById('lightbox').classList.remove('flex'); document.body.style.overflow = ''; }
            function nextSlide() { currentSlide = (currentSlide + 1) % galeriImages.length; updateLightbox(); }
            function prevSlide() { currentSlide = (currentSlide - 1 + galeriImages.length) % galeriImages.length; updateLightbox(); }
            function updateLightbox() { document.getElementById('lightboxImg').src = galeriImages[currentSlide]; document.getElementById('lightboxCounter').textContent = (currentSlide + 1) + ' / ' + galeriImages.length; }
            document.addEventListener('keydown', function (e) { if (document.getElementById('lightbox').classList.contains('hidden')) return; if (e.key === 'Escape') closeLightbox(); if (e.key === 'ArrowRight') nextSlide(); if (e.key === 'ArrowLeft') prevSlide(); });
        @endif
    </script>
@endsection