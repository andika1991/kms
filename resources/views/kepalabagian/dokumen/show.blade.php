<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Dokumen
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8 space-y-6 transition duration-300">

                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                        {{ $dokumen->nama_dokumen }}
                    </h1>

                    @if(Auth::id() === $dokumen->pengguna_id)
                        <a href="{{ route('aksesdokumen.bagikan', $dokumen->id) }}"
                           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-xl shadow-md transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12H3m0 0l3.293-3.293a1 1 0 011.414 0L12 12l-4.293 4.293a1 1 0 01-1.414 0L3 12z"/>
                            </svg>
                            Bagikan Dokumen
                        </a>
                    @endif
                </div>

                <div class="space-y-4 text-gray-700 dark:text-gray-300">
                    <p><strong>Kategori:</strong> {{ $dokumen->kategoriDokumen->nama_kategoridokumen ?? '-' }}</p>

                    <div>
                        <strong>Deskripsi:</strong>
                        <p class="mt-1 whitespace-pre-line">{!! nl2br(e($dokumen->deskripsi)) !!}</p>
                    </div>

                    <p><strong>Uploader:</strong> {{ $dokumen->user->name ?? 'Tidak diketahui' }}</p>
                </div>

                <div class="mt-6">
                    <strong class="block mb-2 text-gray-800 dark:text-gray-200">File Dokumen:</strong>
                    <div class="rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600 shadow-md">
                        <embed 
                            src="{{ asset('storage/' . $dokumen->path_dokumen) }}#zoom=120" 
                            type="application/pdf" 
                            width="100%" 
                            height="600px" 
                            class="rounded-lg">
                    </div>
                </div>

                <div class="flex justify-between items-center pt-6 border-t border-gray-300 dark:border-gray-600">
                    <a href="{{ route('magang.manajemendokumen.index') }}" 
                       class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold px-5 py-2 rounded-xl shadow-md transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Daftar Dokumen
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
