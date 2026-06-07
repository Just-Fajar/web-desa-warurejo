@extends('public.layouts.app')

@section('title', $publikasi->judul)

@section('content')
    <!-- Header Section -->
    <section class="pt-32 pb-8 bg-gray-50 border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto scroll-reveal">
                <nav class="text-sm mb-6">
                    <ol class="flex items-center space-x-2 text-gray-500 font-medium">
                        <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Beranda</a></li>
                        <li><svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg></li>
                        <li><a href="{{ route('publikasi.index') }}"
                                class="hover:text-primary-600 transition-colors">Publikasi</a></li>
                        <li><svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg></li>
                        <li class="text-primary-600 font-semibold">{{ $publikasi->kategori }}</li>
                    </ol>
                </nav>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 tracking-tight">
                    {{ $publikasi->judul }}</h1>
                <div class="flex flex-wrap items-center gap-4 text-gray-500 text-sm font-medium">
                    <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-lg border border-gray-100 shadow-sm">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $publikasi->tanggal_publikasi->format('d F Y') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Main Content -->
                <div class="lg:w-3/4">
                    <!-- Document Info -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="px-3 py-1 bg-primary-100 text-primary-800 text-sm font-semibold rounded-full">
                                {{ $publikasi->kategori }}
                            </span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full">
                                Tahun {{ $publikasi->tahun }}
                            </span>
                        </div>

                        @if($publikasi->deskripsi)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h3>
                                <p class="text-gray-600 leading-relaxed">{{ $publikasi->deskripsi }}</p>
                            </div>
                        @endif


                    </div>

                    <!-- PDF Preview (View Only) -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden select-none" oncontextmenu="return false;">
                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-2">
                            <h3 class="text-lg font-semibold text-gray-900">Preview Dokumen (Hanya Lihat)</h3>
                            
                            <!-- PDF.js Controls -->
                            <div class="flex items-center gap-2" id="pdf-controls" style="display: none;">
                                <button id="zoom-out-btn" class="p-1.5 hover:bg-gray-200 rounded-lg text-gray-600 transition" title="Perkecil">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <span id="zoom-percent" class="text-sm font-semibold text-gray-700 min-w-[50px] text-center">125%</span>
                                <button id="zoom-in-btn" class="p-1.5 hover:bg-gray-200 rounded-lg text-gray-600 transition" title="Perbesar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                                <span class="h-4 w-px bg-gray-300 mx-1"></span>
                                <span id="pdf-page-indicator" class="text-sm text-gray-600 font-medium">Halaman 1 / 1</span>
                            </div>
                        </div>
                        <div class="p-4 bg-gray-100 relative">
                            <!-- Loading State -->
                            <div id="pdf-loading" class="flex flex-col items-center justify-center py-20">
                                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary-600"></div>
                                <span class="mt-4 text-sm text-gray-500 font-medium">Memuat pratinjau dokumen...</span>
                            </div>

                            <!-- PDF Canvas Container (Scrollable) -->
                            <div id="pdf-viewer-container" class="w-full h-[800px] overflow-y-auto flex flex-col items-center gap-6 p-4 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-transparent" style="display: none;">
                                <!-- Canvases will be generated here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:w-1/4">
                    <!-- Related Documents -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Dokumen Terkait</h3>

                        @if($relatedPublikasi->count() > 0)
                            <div class="space-y-4">
                                @foreach($relatedPublikasi as $doc)
                                    <a href="{{ route('publikasi.show', $doc->id) }}"
                                        class="flex gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                                        <div class="shrink-0">
                                            <div
                                                class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 line-clamp-2 group-hover:text-primary-600">
                                                {{ $doc->judul }}
                                            </p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs text-gray-500">{{ $doc->tahun }}</span>

                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <a href="{{ route('publikasi.index', ['kategori' => $publikasi->kategori]) }}"
                                class="block mt-4 text-center text-sm text-primary-600 hover:text-primary-700 font-medium">
                                Lihat Semua {{ $publikasi->kategori }} →
                            </a>
                        @else
                            <p class="text-sm text-gray-500 text-center py-4">Belum ada dokumen terkait</p>
                        @endif
                    </div>

                    <!-- Back Button -->
                    <a href="{{ route('publikasi.index', ['kategori' => $publikasi->kategori]) }}"
                        class="block w-full px-4 py-3 bg-white border border-gray-100 shadow-sm hover:bg-gray-50 text-gray-700 text-center font-bold rounded-2xl transition-all hover:-translate-y-1">
                        ← Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <!-- PDF.js library -->
    <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@3.4.120/build/pdf.min.js"></script>
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('DOMContentLoaded', function () {
            // Configure PDF.js Worker using Blob to avoid cross-origin restrictions
            const workerUrl = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.4.120/build/pdf.worker.min.js';
            const workerBlob = new Blob(['importScripts("' + workerUrl + '");'], { type: 'application/javascript' });
            pdfjsLib.GlobalWorkerOptions.workerSrc = URL.createObjectURL(workerBlob);

            const url = '{{ $publikasi->file_url }}';
            const container = document.getElementById('pdf-viewer-container');
            const loadingEl = document.getElementById('pdf-loading');
            const controlsEl = document.getElementById('pdf-controls');
            const pageIndicator = document.getElementById('pdf-page-indicator');
            const zoomPercent = document.getElementById('zoom-percent');
            const zoomInBtn = document.getElementById('zoom-in-btn');
            const zoomOutBtn = document.getElementById('zoom-out-btn');

            let pdfDoc = null;
            let currentScale = 1.25; // default scale
            const minScale = 0.5;
            const maxScale = 2.5;

            // Load the PDF document
            pdfjsLib.getDocument(url).promise.then(function (pdf) {
                pdfDoc = pdf;
                renderPdf();
            }).catch(function (error) {
                console.error('Error loading PDF:', error);
                loadingEl.innerHTML = `
                    <div class="text-center text-red-500 py-10">
                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="font-semibold text-base">Gagal memuat dokumen</p>
                        <p class="text-sm text-gray-500 mt-1">Silakan refresh halaman atau hubungi administrator.</p>
                    </div>
                `;
            });

            async function renderPdf() {
                container.innerHTML = '';
                
                // Render pages sequentially for performance
                for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
                    try {
                        const page = await pdfDoc.getPage(pageNum);
                        const viewport = page.getViewport({ scale: currentScale });

                        // Create page wrapper
                        const pageWrapper = document.createElement('div');
                        pageWrapper.className = 'relative shadow-md border border-gray-200 bg-white mx-auto rounded select-none';
                        pageWrapper.style.width = '100%';
                        pageWrapper.style.maxWidth = `${viewport.width}px`;
                        pageWrapper.style.aspectRatio = `${viewport.width} / ${viewport.height}`;

                        // Create canvas
                        const canvas = document.createElement('canvas');
                        canvas.className = 'w-full h-full block pointer-events-none';
                        
                        const context = canvas.getContext('2d');
                        
                        // Retina support / High DPI
                        const dpr = window.devicePixelRatio || 1;
                        canvas.width = viewport.width * dpr;
                        canvas.height = viewport.height * dpr;
                        context.scale(dpr, dpr);

                        pageWrapper.appendChild(canvas);
                        container.appendChild(pageWrapper);

                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        
                        await page.render(renderContext).promise;
                    } catch (err) {
                        console.error('Error rendering page ' + pageNum + ':', err);
                    }
                }
                
                // Show viewer, hide loading
                loadingEl.style.display = 'none';
                container.style.display = 'flex';
                controlsEl.style.display = 'flex';
                
                updatePageIndicator();
            }

            function updatePageIndicator() {
                const wrapperChildren = container.children;
                if (!wrapperChildren.length) return;
                
                let activePage = 1;
                const containerTop = container.getBoundingClientRect().top;
                
                for (let i = 0; i < wrapperChildren.length; i++) {
                    const child = wrapperChildren[i];
                    const rect = child.getBoundingClientRect();
                    
                    // If page top has passed the top region of scroll view
                    if (rect.top - containerTop <= 100) {
                        activePage = i + 1;
                    }
                }
                
                pageIndicator.textContent = `Halaman ${activePage} / ${pdfDoc.numPages}`;
            }

            // Scroll listener to update page counter
            container.addEventListener('scroll', updatePageIndicator);

            // Zoom in
            zoomInBtn.addEventListener('click', function () {
                if (currentScale < maxScale) {
                    currentScale = Math.min(maxScale, currentScale + 0.25);
                    zoomPercent.textContent = `${Math.round(currentScale * 100)}%`;
                    
                    // Show loading state while re-rendering
                    container.style.display = 'none';
                    loadingEl.style.display = 'flex';
                    
                    renderPdf();
                }
            });

            // Zoom out
            zoomOutBtn.addEventListener('click', function () {
                if (currentScale > minScale) {
                    currentScale = Math.max(minScale, currentScale - 0.25);
                    zoomPercent.textContent = `${Math.round(currentScale * 100)}%`;
                    
                    // Show loading state while re-rendering
                    container.style.display = 'none';
                    loadingEl.style.display = 'flex';
                    
                    renderPdf();
                }
            });

            // Block Drag & Drop on canvases
            container.addEventListener('dragstart', function (e) {
                e.preventDefault();
            });

            // Anti-save / Anti-print keyboard shortcuts
            window.addEventListener('keydown', function (e) {
                const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;
                const isSave = (isMac ? e.metaKey : e.ctrlKey) && e.key.toLowerCase() === 's';
                const isPrint = (isMac ? e.metaKey : e.ctrlKey) && e.key.toLowerCase() === 'p';
                const isInspect = (isMac ? e.metaKey : e.ctrlKey) && e.key.toLowerCase() === 'u';

                if (isSave) {
                    e.preventDefault();
                    alert('Dokumen ini dilindungi dan tidak diperbolehkan untuk disimpan.');
                }
                if (isPrint) {
                    e.preventDefault();
                    alert('Dokumen ini dilindungi dan tidak diperbolehkan untuk dicetak.');
                }
                if (isInspect) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush