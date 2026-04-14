@extends('public.layouts.app')

@section('title', 'Visi & Misi - Desa Warurejo')

@section('content')

{{-- Hero Section --}}
<section class="pt-32 pb-8 bg-gray-50 border-b border-gray-100 relative overflow-hidden">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-rose-100 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-72 h-72 bg-rose-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl scroll-reveal">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-rose-100/80 text-rose-700 text-sm font-bold tracking-wide uppercase mb-4 border border-rose-200 shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-pulse"></span>
                PROFIL DESA
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 mb-4 tracking-tight">
                Visi & <span class="text-rose-600">Misi</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-600 max-w-2xl leading-relaxed font-medium">
                Visi dan Misi Desa Warurejo dalam Mewujudkan Desa Maju, Modern, dan Sejahtera.
            </p>
        </div>
    </div>
</section>

{{-- Main Content --}}
<section class="py-12 sm:py-16 md:py-24 bg-white relative">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">

        {{-- VISI HEADER --}}
        <div class="flex flex-col items-center justify-center mb-12 sm:mb-16 scroll-reveal">
            <div class="flex items-center gap-4 mb-6">
                <div class="h-0.5 w-12 sm:w-16 bg-primary-600"></div>
                <span class="text-primary-600 font-bold uppercase tracking-wider text-sm sm:text-base">Visi & Misi</span>
                <div class="h-0.5 w-12 sm:w-16 bg-primary-600"></div>
            </div>
            
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 text-center leading-tight mb-6 sm:mb-8 max-w-4xl">
                "Menciptakan Desa Warurejo Menjadi Desa Yang Mandiri Berbasis Pertanian Dan Perdagangan Untuk Mencapai Masyarakat Yang Sejahtera Dan Mandiri"
            </h2>
            
            <p class="text-gray-500 italic text-center max-w-3xl mx-auto text-base sm:text-lg md:text-xl leading-relaxed">
                "Visi merupakan pandangan jauh ke depan, kemana dan bagaimana Desa Warurejo harus dibawa dan berkarya agar konsisten, antisipatif, inovatif serta produktif dalam melayani masyarakat."
            </p>
        </div>

        {{-- MISI CARD --}}
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-gray-100 overflow-hidden scroll-reveal mb-12 sm:mb-16">
            <div class="border-b border-gray-50 p-6 sm:p-8 flex items-center justify-between bg-gray-50/30">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900">Misi & Fokus Kerja</h3>
                </div>
            </div>
            
            <div class="p-6 sm:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                    {{-- Item 1 --}}
                    <div class="flex items-start gap-4 hover:-translate-y-1 transition duration-300">
                        <div class="bg-primary-50 text-primary-600 p-1.5 sm:p-2 rounded-full shrink-0 mt-0.5">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm sm:text-base mb-1">Bina Keagamaan</h4>
                            <p class="text-gray-500 text-xs sm:text-sm leading-relaxed">Mewujudkan dan mengembangkan kegiatan keagamaan untuk menambah keimanan dan ketaqwaan.</p>
                        </div>
                    </div>

                    {{-- Item 2 --}}
                    <div class="flex items-start gap-4 hover:-translate-y-1 transition duration-300">
                        <div class="bg-primary-50 text-primary-600 p-1.5 sm:p-2 rounded-full shrink-0 mt-0.5">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm sm:text-base mb-1">Kerukunan Warga</h4>
                            <p class="text-gray-500 text-xs sm:text-sm leading-relaxed">Mendorong usaha-usaha kerukunan antar dan intern warga masyarakat dalam suasana saling menghargai.</p>
                        </div>
                    </div>

                    {{-- Item 3 --}}
                    <div class="flex items-start gap-4 hover:-translate-y-1 transition duration-300">
                        <div class="bg-primary-50 text-primary-600 p-1.5 sm:p-2 rounded-full shrink-0 mt-0.5">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm sm:text-base mb-1">Peningkatan Pertanian</h4>
                            <p class="text-gray-500 text-xs sm:text-sm leading-relaxed">Membangun hasil pertanian dengan penataan pengairan, perbaikan jalan sawah, dan pola tanam baik.</p>
                        </div>
                    </div>

                    {{-- Item 4 --}}
                    <div class="flex items-start gap-4 hover:-translate-y-1 transition duration-300">
                        <div class="bg-primary-50 text-primary-600 p-1.5 sm:p-2 rounded-full shrink-0 mt-0.5">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm sm:text-base mb-1">Tata Kelola Pemerintahan</h4>
                            <p class="text-gray-500 text-xs sm:text-sm leading-relaxed">Menata Pemerintahan Desa yang kompak dan bertanggung jawab dalam mengemban amanat masyarakat.</p>
                        </div>
                    </div>

                    {{-- Item 5 --}}
                    <div class="flex items-start gap-4 hover:-translate-y-1 transition duration-300">
                        <div class="bg-primary-50 text-primary-600 p-1.5 sm:p-2 rounded-full shrink-0 mt-0.5">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm sm:text-base mb-1">Pelayanan Prima</h4>
                            <p class="text-gray-500 text-xs sm:text-sm leading-relaxed">Meningkatkan kualitas pelayanan kepada masyarakat secara terpadu, cepat, dan sungguh-sungguh.</p>
                        </div>
                    </div>

                    {{-- Item 6 --}}
                    <div class="flex items-start gap-4 hover:-translate-y-1 transition duration-300">
                        <div class="bg-primary-50 text-primary-600 p-1.5 sm:p-2 rounded-full shrink-0 mt-0.5">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm sm:text-base mb-1">Pengelolaan Air & Irigasi</h4>
                            <p class="text-gray-500 text-xs sm:text-sm leading-relaxed">Mencari dan menambah debet air untuk mencukupi kebutuhan vital di sektor pertanian desa.</p>
                        </div>
                    </div>

                    {{-- Item 7 --}}
                    <div class="flex items-start gap-4 hover:-translate-y-1 transition duration-300">
                        <div class="bg-primary-50 text-primary-600 p-1.5 sm:p-2 rounded-full shrink-0 mt-0.5">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm sm:text-base mb-1">Pemberdayaan Petani</h4>
                            <p class="text-gray-500 text-xs sm:text-sm leading-relaxed">Menumbuh kembangkan Kelompok Tani dan Gapoktan bersama HIPPA dalam memfasilitasi petani.</p>
                        </div>
                    </div>

                    {{-- Item 8 --}}
                    <div class="flex items-start gap-4 hover:-translate-y-1 transition duration-300">
                        <div class="bg-primary-50 text-primary-600 p-1.5 sm:p-2 rounded-full shrink-0 mt-0.5">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm sm:text-base mb-1">Pengembangan UMKM</h4>
                            <p class="text-gray-500 text-xs sm:text-sm leading-relaxed">Mendorong serta menumbuhkembangkan potensi usaha kecil dan menengah untuk memutar roda ekonomi asli desa.</p>
                        </div>
                    </div>

                    {{-- Item 9 --}}
                    <div class="flex items-start gap-4 hover:-translate-y-1 md:col-span-2 lg:col-span-2 transition duration-300">
                        <div class="bg-primary-50 text-primary-600 p-1.5 sm:p-2 rounded-full shrink-0 mt-0.5">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm sm:text-base mb-1">Kemajuan Pendidikan</h4>
                            <p class="text-gray-500 text-xs sm:text-sm leading-relaxed">Membangun bidang pendidikan formal maupun informal yang mudah diakses dan mampu menghasilkan insan intelektual, inovatif dan enterpreneur unggul.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Makna Filosofis Visi --}}
        <div class="mb-12 sm:mb-16 scroll-reveal">
            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 text-center mb-8">Visi Desa</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Convert the 6 points into small neat cards -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:shadow-md transition">
                    <h4 class="font-bold text-primary-700 mb-2">1. Menciptakan</h4>
                    <p class="text-sm text-gray-600 leading-relaxed">Upaya dan peran pemerintah dalam mewujudkan Desa Warurejo yang mandiri berbasis pertanian dan perdagangan demi masyarakat sejahtera.</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:shadow-md transition">
                    <h4 class="font-bold text-primary-700 mb-2">2. Desa Warurejo</h4>
                    <p class="text-sm text-gray-600 leading-relaxed">Satu kesatuan masyarakat hukum dengan segala potensi dan sumber dayanya dalam sistem pemerintahan.</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:shadow-md transition">
                    <h4 class="font-bold text-primary-700 mb-2">3. Mandiri</h4>
                    <p class="text-sm text-gray-600 leading-relaxed">Mampu menjalani kehidupan dengan kemampuan diri sendiri sebagai prasyarat utama untuk meraih berbagai keberhasilan.</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:shadow-md transition">
                    <h4 class="font-bold text-primary-700 mb-2">4. Pertanian</h4>
                    <p class="text-sm text-gray-600 leading-relaxed">Kondisi masyarakat mayoritas petani yang mengelola lahan pertanian sebagai sumber mata pencaharian pokok desa.</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:shadow-md transition">
                    <h4 class="font-bold text-primary-700 mb-2">5. Perdagangan</h4>
                    <p class="text-sm text-gray-600 leading-relaxed">Sektor jasa yang menunjang kegiatan ekonomi antar anggota masyarakat untuk melesatkan pertumbuhan perekonomian.</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:shadow-md transition">
                    <h4 class="font-bold text-primary-700 mb-2">6. Warurejo MANDIRI</h4>
                    <p class="text-sm text-gray-600 leading-relaxed">Akronim nilai yang dipegang teguh oleh desa yakni: <span class="font-semibold text-gray-800">Maju, Aman, Nyaman, Dinamis, dan Religius</span>.</p>
                </div>
            </div>
        </div>

        {{-- Komitmen Kami --}}
        <div class="bg-[#f0f7ff] border-l-[6px] border-[#3b82f6] p-6 sm:p-8 rounded-xl scroll-reveal mt-12 max-w-5xl mx-auto shadow-sm">
            <h3 class="text-xl sm:text-2xl font-extrabold text-[#111827] mb-4 tracking-tight">Komitmen Kami</h3>
            <p class="text-base sm:text-lg text-gray-600 leading-relaxed">
                Visi dan misi ini menjadi pedoman dalam setiap langkah pembangunan Desa Warurejo. 
                Dengan semangat kebersamaan dan budaya gotong royong, kami berkomitmen mewujudkan Desa Warurejo 
                yang lebih maju, mandiri, dan sejahtera bagi seluruh masyarakat.
            </p>
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

.scroll-reveal-stagger {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.6s ease-out, transform 0.6s ease-out;
}

.scroll-reveal-stagger.revealed {
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
    document.querySelectorAll('.scroll-reveal, .scroll-reveal-stagger').forEach(el => {
        observer.observe(el);
    });
});
</script>

@endsection
