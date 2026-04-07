{{--
    PUBLIC POTENSI DETAIL
    
    Halaman detail potensi desa dengan informasi lengkap
    
    FEATURES:
    - Breadcrumb navigation (Home > Potensi > Nama)
    - Featured image full-width dengan overlay
    - Kategori badge (color coded)
    - Nama potensi prominent
    - Full HTML description (sanitized)
    - Lokasi lengkap
    - Contact info (telepon/WhatsApp)
    - WhatsApp quick contact button
    - View counter increment
    - Related potensi (same kategori)
    - Back to list button
    - Social share (optional)
    
    CONTENT SECTIONS:
    1. Featured Image: Large header dengan kategori badge
    2. Main Info: Nama, deskripsi, lokasi
    3. Contact Section: Telepon & WhatsApp dengan icons
    4. Related Potensi: 3 items same kategori
    
    KATEGORI COLORS:
    - pertanian: green-600
    - peternakan: amber-600
    - perikanan: blue-600
    - umkm: blue-600
    - wisata: purple-600
    - kerajinan: pink-600
    - lainnya: gray-600
    
    DESCRIPTION RENDERING:
    - {!! $potensi->deskripsi !!} - Raw HTML
    - Already sanitized di PotensiDesaService
    - Support images, lists, formatted text
    
    CONTACT FEATURES:
    - Telepon: tel: link untuk direct call
    - WhatsApp: wa.me link dengan pre-filled message
    - Click to call/chat functionality
    
    SEO OPTIMIZATION:
    - Dynamic title: {nama} - Potensi Desa Warurejo
    - Meta description dari excerpt
    - Open Graph tags
    - Structured data (Product/Service schema)
    
    RELATED POTENSI:
    - Same kategori filter
    - Exclude current item
    - Limit 3 items
    - Random or latest ordering
    
    TRACKING:
    - View counter auto-increment
    - Analytics tracking
    
    DATA:
    $potensi: PotensiDesa model dengan semua fields
    $relatedPotensi: Collection of same kategori items
    
    Route: /potensi/{slug}
    Controller: Public\PotensiController@show
--}}
@extends('public.layouts.app')

@section('title', $potensi->nama . ' - Potensi Desa Warurejo')

@section('content')
{{-- Breadcrumb --}}
<section class="bg-gray-100 py-3 sm:py-4">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center text-xs sm:text-sm text-gray-600">
            <a href="{{ route('home') }}" class="hover:text-green-600 shrink-0">Beranda</a>
            <svg class="w-3 h-3 sm:w-4 sm:h-4 mx-1.5 sm:mx-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <a href="{{ route('potensi.index') }}" class="hover:text-green-600 shrink-0">Potensi Desa</a>
            <svg class="w-3 h-3 sm:w-4 sm:h-4 mx-1.5 sm:mx-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="text-gray-800 font-semibold truncate">{{ Str::limit($potensi->nama, 50) }}</span>
        </div>
    </div>
</section>

{{-- Potensi Detail --}}
<section class="py-6 sm:py-8 md:py-12 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            
            {{-- Main Content Card --}}
            <article class="bg-white rounded-lg shadow-lg overflow-hidden mb-6 sm:mb-8">
                {{-- Featured Image --}}
                @if($potensi->gambar_url)
                    <div class="relative h-56 sm:h-64 md:h-80 lg:h-96 overflow-hidden">
                        <img 
                            src="{{ $potensi->gambar_url }}" 
                            alt="{{ $potensi->nama }}"
                            class="w-full h-full object-cover"
                        >
                        <div class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent"></div>
                        
                        {{-- Category Badge --}}
                        <div class="absolute top-3 sm:top-4 md:top-6 right-3 sm:right-4 md:right-6">
                            @php
                                $kategoriColors = [
                                    'pertanian' => 'bg-green-600',
                                    'peternakan' => 'bg-amber-600',
                                    'umkm' => 'bg-blue-600',
                                    'wisata' => 'bg-purple-600',
                                    'lainnya' => 'bg-gray-600',
                                ];
                                $bgColor = $kategoriColors[$potensi->kategori ?? 'lainnya'] ?? 'bg-gray-600';
                            @endphp
                            <span class="px-2.5 sm:px-3 md:px-4 py-1.5 sm:py-2 {{ $bgColor }} text-white text-xs sm:text-sm font-bold rounded-full uppercase shadow-lg">
                                {{ $potensi->kategori ?? 'Lainnya' }}
                            </span>
                        </div>
                    </div>
                @endif

                <div class="p-4 sm:p-6 md:p-8">
                    {{-- Title --}}
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4 sm:mb-6">
                        {{ $potensi->nama }}
                    </h1>

                    {{-- Meta Info --}}
                    <div class="flex flex-wrap gap-6 mb-8 pb-8 border-b border-gray-200">
                        @if($potensi->lokasi)
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">{{ $potensi->lokasi }}</span>
                            </div>
                        @endif

                        @if(isset($potensi->luas_area))
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2zm0 6a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z" clip-rule="evenodd"/>
                                </svg>
                                <span>Luas Area: <strong>{{ $potensi->luas_area }}</strong></span>
                            </div>
                        @endif

                        @if(isset($potensi->kapasitas_produksi))
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"/>
                                    <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"/>
                                    <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"/>
                                </svg>
                                <span>Kapasitas: <strong>{{ $potensi->kapasitas_produksi }}</strong></span>
                            </div>
                        @endif
                    </div>

                    {{-- Description --}}
                    <div class="prose prose-lg max-w-none mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Deskripsi</h2>
                        <div class="text-gray-700 leading-relaxed">
                            {!! nl2br(e($potensi->deskripsi)) !!}
                        </div>
                    </div>

                    {{-- Keunggulan --}}
                    @if(isset($potensi->keunggulan) && $potensi->keunggulan)
                        <div class="mb-8 p-6 bg-green-50 rounded-lg border-l-4 border-green-500">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Keunggulan
                            </h2>
                            <div class="text-gray-800 leading-relaxed">
                                {!! nl2br(e($potensi->keunggulan)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Kontak Person --}}
                    @if((isset($potensi->kontak) && $potensi->kontak) || (isset($potensi->whatsapp) && $potensi->whatsapp))
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">Informasi Kontak</h2>
                            
                            @if(isset($potensi->kontak) && $potensi->kontak)
                                <div class="flex items-center text-gray-700 mb-3">
                                    <svg class="w-5 h-5 mr-3 text-green-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    <span class="font-semibold">{{ $potensi->kontak }}</span>
                                </div>
                            @endif
                            
                            @if(isset($potensi->whatsapp) && $potensi->whatsapp)
                                <a href="https://wa.me/62{{ $potensi->whatsapp }}?text=Halo,%20saya%20tertarik%20dengan%20{{ urlencode($potensi->nama) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition shadow-lg font-semibold">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                    Chat via WhatsApp: +62{{ $potensi->whatsapp }}
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Share Buttons --}}
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <p class="text-gray-700 font-semibold mb-4">Bagikan:</p>
                        <div class="flex flex-wrap gap-3">
                            <a 
                                href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('potensi.show', $potensi->slug)) }}" 
                                target="_blank"
                                class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                            >
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                Facebook
                            </a>
                            <a 
                                href="https://twitter.com/intent/tweet?url={{ urlencode(route('potensi.show', $potensi->slug)) }}&text={{ urlencode($potensi->nama) }}" 
                                target="_blank"
                                class="flex items-center px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition"
                            >
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                                Twitter
                            </a>
                            <a 
                                href="https://wa.me/?text={{ urlencode($potensi->nama . ' - ' . route('potensi.show', $potensi->slug)) }}" 
                                target="_blank"
                                class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                            >
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Back Button --}}
            <div class="mt-8 text-center">
                <a 
                    href="{{ route('potensi.index') }}" 
                    class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar Potensi
                </a>
            </div>

        </div>
    </div>
</section>
@endsection
