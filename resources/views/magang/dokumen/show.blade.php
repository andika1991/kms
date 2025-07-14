<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Dokumen
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6">

            <h1 class="text-2xl font-bold mb-4">{{ $dokumen->nama_dokumen }}</h1>

            <p class="mb-2">
                <strong>Kategori:</strong>
                {{ $dokumen->kategoriDokumen->nama_kategoridokumen ?? '-' }}
            </p>

            <p class="mb-4">
                <strong>Deskripsi:</strong><br>
                {!! nl2br(e($dokumen->deskripsi)) !!}
            </p>

            <p class="mb-4">
                <strong>Uploader:</strong> {{ $dokumen->user->name ?? 'Tidak diketahui' }}
            </p>

            <p class="mb-4">
                <strong>File Dokumen:</strong><br>

                @if ($dokumen->path_dokumen && \Illuminate\Support\Facades\Storage::disk('public')->exists($dokumen->path_dokumen))
                    {{-- Container for the PDF --}}
                    <div id="pdf-viewer-container" class="border border-gray-300 rounded overflow-hidden" style="min-height: 400px; position: relative;">
                        <canvas id="pdf-canvas" class="w-full"></canvas>
                        <div id="pdf-loading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 text-gray-700 text-lg" style="display: none;">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memuat dokumen...
                        </div>
                    </div>

                    {{-- PDF.js library --}}
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            const pdfUrl = "{{ asset('storage/' . $dokumen->path_dokumen) }}";
                            const canvas = document.getElementById('pdf-canvas');
                            const context = canvas.getContext('2d');
                            const loadingIndicator = document.getElementById('pdf-loading');
                            const pdfViewerContainer = document.getElementById('pdf-viewer-container');

                            // Show loading indicator
                            loadingIndicator.style.display = 'flex';

                            // Set worker source for PDF.js
                            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

                            // Fetch and render the PDF
                            const loadingTask = pdfjsLib.getDocument(pdfUrl);
                            loadingTask.promise.then(pdf => {
                                // Hide loading indicator
                                loadingIndicator.style.display = 'none';

                                // Get the first page
                                pdf.getPage(1).then(page => {
                                    const scale = 1.5; // You can adjust this scale
                                    const viewport = page.getViewport({ scale });

                                    // Set canvas dimensions
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;

                                    // Render the page on the canvas
                                    const renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };
                                    page.render(renderContext);
                                }).catch(pageError => {
                                    console.error("Error rendering PDF page:", pageError);
                                    alert('Gagal menampilkan halaman PDF. ' + (pageError.message || ''));
                                    loadingIndicator.style.display = 'none';
                                });
                            }).catch(pdfError => {
                                console.error("Error loading PDF document:", pdfError);
                                alert('Gagal memuat PDF. Pastikan file PDF valid dan dapat diakses. Coba buka file langsung di tab baru. ' + (pdfError.message || ''));
                                loadingIndicator.style.display = 'none';
                                // Optionally hide the canvas or show an error message on the canvas itself
                                canvas.style.display = 'none';
                            });

                            // Add a resize observer to adjust canvas size if the container changes
                            const resizeObserver = new ResizeObserver(entries => {
                                for (let entry of entries) {
                                    if (entry.target === pdfViewerContainer) {
                                        const containerWidth = entry.contentRect.width;
                                        // Re-render PDF if necessary to fit the new width
                                        // This part can be more complex if you need full responsiveness
                                        // For simplicity, we'll just set canvas width to container width
                                        canvas.style.width = containerWidth + 'px';
                                        // You might need to re-render the page with a new scale here
                                    }
                                }
                            });
                            resizeObserver.observe(pdfViewerContainer);
                        });
                    </script>

                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Jika PDF tidak muncul,
                        <a href="{{ asset('storage/' . $dokumen->path_dokumen) }}" target="_blank" class="text-blue-500 hover:text-blue-700 underline">
                            klik di sini untuk membuka dokumen di tab baru
                        </a>.
                    </p>

                @else
                    <p class="text-red-600 dark:text-red-400">Dokumen tidak ditemukan atau belum diunggah.</p>
                    @if ($dokumen->path_dokumen)
                        <p class="text-sm text-gray-500">URL yang dicari: {{ asset('storage/' . $dokumen->path_dokumen) }}</p>
                    @endif
                @endif
            </p>

            <div class="mt-6">
                <a href="{{ route('magang.manajemendokumen.index') }}"
                   class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                    Kembali ke daftar dokumen
                </a>
            </div>

        </div>
    </div>
</x-app-layout>