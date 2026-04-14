@extends('public.layouts.app')

@section('title', 'Sejarah Desa - Desa Warurejo')

@section('content')
{{-- Hero Section --}}
<section class="pt-32 pb-8 bg-gray-50 border-b border-gray-100 relative overflow-hidden">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-orange-100 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-72 h-72 bg-orange-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl scroll-reveal">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-orange-100/80 text-orange-700 text-sm font-bold tracking-wide uppercase mb-4 border border-orange-200 shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-600 animate-pulse"></span>
                PROFIL DESA
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 mb-4 tracking-tight">
                Sejarah <span class="text-orange-600">Desa</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-600 max-w-2xl leading-relaxed font-medium">
                Perjalanan Sejarah dan Perkembangan Desa Warurejo dari Masa ke Masa.
            </p>
        </div>
    </div>
</section>

{{-- Sejarah Content --}}
<section class="py-12 sm:py-16 md:py-24 bg-white relative">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">

        {{-- SEJARAH HEADER --}}
        <div class="flex flex-col items-center justify-center mb-12 sm:mb-16 scroll-reveal">
            <div class="flex items-center gap-4 mb-6">
                <div class="h-0.5 w-12 sm:w-16 bg-primary-600"></div>
                <span class="text-primary-600 font-bold uppercase tracking-wider text-sm sm:text-base">Sejarah</span>
                <div class="h-0.5 w-12 sm:w-16 bg-primary-600"></div>
            </div>
            
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 text-center leading-tight mb-6 sm:mb-8 max-w-4xl">
                Jejak Perjalanan & Perkembangan<br />Desa Warurejo
            </h2>
            
            <p class="text-gray-500 italic text-center max-w-3xl mx-auto text-base sm:text-lg md:text-xl leading-relaxed">
                "Menggali masa lalu sebagai fondasi kokoh untuk melangkah dan membangun masa depan desa yang lebih maju serta sejahtera."
            </p>
        </div>

        {{-- MAIN TIMELINE/CONTENT --}}
        <div class="relative space-y-12 sm:space-y-16">
            {{-- Abstract Line for Timeline (hidden on small screens, shown on md+) --}}
            <div class="hidden md:block absolute left-[39px] top-4 bottom-4 w-px bg-gray-200"></div>

            {{-- 1. Asal Usul Nama --}}
            <div class="relative scroll-reveal group">
                <div class="md:flex gap-8">
                    <div class="hidden md:flex shrink-0 w-20 flex-col items-center">
                        <div class="w-12 h-12 bg-white border-2 border-primary-600 rounded-full flex items-center justify-center z-10 group-hover:bg-primary-600 group-hover:text-white transition duration-300 text-primary-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 bg-gray-50/50 hover:bg-white border border-gray-100 rounded-2xl p-6 sm:p-8 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition duration-300">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 inline-flex items-center gap-3">
                            <span class="md:hidden w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center text-sm">1</span>
                            Asal Usul Nama & Pembentukan
                        </h3>
                        <p class="text-gray-600 leading-relaxed mb-4">Nama desa <strong class="text-primary-700">Warurejo</strong> kemungkinan besar berasal dari kombinasi dua kata dalam bahasa Jawa:</p>
                        
                        <div class="grid sm:grid-cols-2 gap-4 mb-4">
                            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                <span class="block font-bold text-primary-800 text-lg mb-1">Waru</span>
                                <span class="text-sm text-gray-600">Merujuk pada Pohon Waru yang mungkin dahulu banyak tumbuh. Sering dikaitkan dengan tempat teduh dan pertemuan.</span>
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                <span class="block font-bold text-primary-800 text-lg mb-1">Rejo</span>
                                <span class="text-sm text-gray-600">Memiliki arti ramai, makmur, atau tanah yang subur.</span>
                            </div>
                        </div>

                        <div class="bg-primary-50 rounded-xl p-4 border border-primary-100">
                            <p class="italic text-primary-900 text-sm">
                                Dengan demikian, Warurejo dapat diartikan sebagai <strong>"Tempat yang makmur yang ditandai dengan adanya Pohon Waru"</strong>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Pembukaan Lahan --}}
            <div class="relative scroll-reveal group">
                <div class="md:flex gap-8">
                    <div class="hidden md:flex shrink-0 w-20 flex-col items-center">
                        <div class="w-12 h-12 bg-white border-2 border-primary-600 rounded-full flex items-center justify-center z-10 group-hover:bg-primary-600 group-hover:text-white transition duration-300 text-primary-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 bg-gray-50/50 hover:bg-white border border-gray-100 rounded-2xl p-6 sm:p-8 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition duration-300">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 inline-flex items-center gap-3">
                            <span class="md:hidden w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center text-sm">2</span>
                            Periode Awal & Pembukaan Lahan
                        </h3>
                        <p class="text-gray-600 leading-relaxed mb-4">Seperti desa-desa di sekitar Balerejo (yang dikenal sebagai daerah dataran rendah dan persawahan yang subur), Warurejo diperkirakan dibuka pada masa-masa:</p>
                        
                        <ul class="space-y-3 text-gray-600 text-sm sm:text-base">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-primary-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <div><strong class="text-gray-900 block">Masa Mataram Islam</strong> Ketika pengaruh Mataram meluas ke wilayah timur, terjadi pembukaan lahan baru untuk pemukiman.</div>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-primary-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <div><strong class="text-gray-900 block">Awal Kolonial</strong> Pembukaan lahan semakin intensif seiring kebutuhan komoditas pertanian.</div>
                            </li>
                        </ul>
                        
                        <p class="text-gray-600 text-sm mt-4 leading-relaxed">
                            Penduduk pertama kemungkinan besar adalah pahlawan babat alas yang membuka hutan demi sumber air di sekitar Sungai Madiun.
                        </p>
                    </div>
                </div>
            </div>

            {{-- 3. Periode Kemerdekaan --}}
            <div class="relative scroll-reveal group">
                <div class="md:flex gap-8">
                    <div class="hidden md:flex shrink-0 w-20 flex-col items-center">
                        <div class="w-12 h-12 bg-white border-2 border-primary-600 rounded-full flex items-center justify-center z-10 group-hover:bg-primary-600 group-hover:text-white transition duration-300 text-primary-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 bg-gray-50/50 hover:bg-white border border-gray-100 rounded-2xl p-6 sm:p-8 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition duration-300">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 inline-flex items-center gap-3">
                            <span class="md:hidden w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center text-sm">3</span>
                            Kolonial hingga Kemerdekaan
                        </h3>
                        
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="p-4 bg-white rounded-xl border border-gray-100">
                                <span class="block font-bold text-gray-900 mb-1">Basis Pertanian</span>
                                <span class="text-sm text-gray-600 block">Warurejo berkembang pesat menjadi produsen padi yang andal di daerahnya.</span>
                            </div>
                            <div class="p-4 bg-white rounded-xl border border-gray-100">
                                <span class="block font-bold text-gray-900 mb-1">Pembentukan Struktur</span>
                                <span class="text-sm text-gray-600 block">Status admistratif resmi terbentuk sejak Inlandsche Gemeente Ordonnantie.</span>
                            </div>
                            <div class="p-4 bg-white rounded-xl border border-gray-100 sm:col-span-2">
                                <span class="block font-bold text-gray-900 mb-1">Dinamika Kemerdekaan</span>
                                <span class="text-sm text-gray-600 block">Kuat menahan berbagai goncangan pasca 1945 berbekal kohesi sosial masyarakat Madiun.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Para Pendiri / Pemimpin --}}
            <div class="relative scroll-reveal group">
                <div class="md:flex gap-8">
                    <div class="hidden md:flex shrink-0 w-20 flex-col items-center">
                        <div class="w-12 h-12 bg-white border-2 border-primary-600 rounded-full flex items-center justify-center z-10 group-hover:bg-primary-600 group-hover:text-white transition duration-300 text-primary-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 bg-white border border-gray-100 rounded-2xl p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition duration-300">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6 inline-flex items-center gap-3">
                            <span class="md:hidden w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center text-sm">4</span>
                            Tokoh Pendiri & Daftar Pemimpin
                        </h3>
                        
                        <p class="text-sm text-gray-500 mb-6 font-medium">Tokoh perintis yang berjasa membimbing keberlanjutan dan kepemimpinan desa:</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-6">
                            <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-100">
                                <div class="text-xs text-primary-600 font-bold mb-0.5">Pendiri (Tahun 1830)</div>
                                <div class="font-bold text-gray-900">Dipo Gati</div>
                            </div>
                            <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-100">
                                <div class="text-xs text-gray-500 font-bold mb-0.5">Pemimpin ke-2</div>
                                <div class="font-bold text-gray-900">Dipo Semito</div>
                            </div>
                            <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-100">
                                <div class="text-xs text-gray-500 font-bold mb-0.5">Pemimpin ke-3</div>
                                <div class="font-bold text-gray-900">Dipo Yono</div>
                            </div>
                            <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-100">
                                <div class="text-xs text-gray-500 font-bold mb-0.5">Pemimpin ke-4</div>
                                <div class="font-bold text-gray-900">Dipo Sono</div>
                            </div>
                            <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-100">
                                <div class="text-xs text-gray-500 font-bold mb-0.5">Pemimpin ke-5</div>
                                <div class="font-bold text-gray-900">Tohjoyo</div>
                            </div>
                            <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-100">
                                <div class="text-xs text-gray-500 font-bold mb-0.5">Pemimpin ke-6</div>
                                <div class="font-bold text-gray-900">Wonotiko</div>
                            </div>
                            <div class="bg-primary-50 rounded-xl px-4 py-3 border border-primary-100 sm:col-span-2 lg:col-span-3">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="text-xs text-primary-700 font-bold mb-0.5">Kepala Desa (1959 - 1985)</div>
                                        <div class="font-bold text-gray-900">Wongso Wijoyo / Wongso Saikun</div>
                                    </div>
                                    <div class="bg-white p-2 rounded-lg ext-xs font-semibold text-primary-700">Era Transisi</div>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-gray-400 italic text-center">Para tokoh ini adalah pahlawan lokal di masa silam. Sejarah mereka terus dilestarikan dan menjadi tonggak kemakmuran Warurejo selamanya.</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<style>
/* Scroll-triggered animations */
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
// Scroll-triggered animation observer
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        root: null,
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
            }
        });
    }, observerOptions);

    // Observe all elements with scroll-reveal classes
    document.querySelectorAll('.scroll-reveal').forEach(el => {
        observer.observe(el);
    });
});
</script>

@endsection