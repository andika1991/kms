<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📄 Detail Dokumen
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 shadow-xl rounded-3xl p-8 transition-colors duration-500">

            {{-- Notifikasi Success --}}
            @if(session('success'))
                <div
                    class="mb-6 px-6 py-4 rounded-lg bg-green-100 text-green-800 font-semibold shadow-md border border-green-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Notifikasi Error --}}
            @if($errors->any())
                <div
                    class="mb-6 px-6 py-4 rounded-lg bg-red-100 text-red-800 font-semibold shadow-md border border-red-300">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 leading-tight max-w-[70%]">
                    {{ $dokumen->nama_dokumen }}
                </h1>

                <div class="flex space-x-3">
                     @if(Auth::id() === $dokumen->pengguna_id)
        <a href="{{ route('aksesdokumen.bagikan', $dokumen->id) }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-xl shadow-lg transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 12H3m0 0l3.293-3.293a1 1 0 011.414 0L12 12l-4.293 4.293a1 1 0 01-1.414 0L3 12z"/>
            </svg>
            Bagikan Dokumen
        </a>
    @endif

                    <a href="{{ route('magang.manajemendokumen.index') }}"
                       class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-semibold transition-shadow shadow-md">
                        &larr; Kembali
                    </a>
                </div>
            </div>

            <div class="space-y-6 text-gray-800 dark:text-gray-300">
                <p>
                    <span class="font-semibold text-gray-700 dark:text-gray-400">Kategori:</span>
                    {{ $dokumen->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                </p>

                <section class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg shadow-inner">
                    <h2 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">Deskripsi</h2>
                    <p class="whitespace-pre-line text-gray-900 dark:text-gray-100 leading-relaxed">
                        {!! nl2br(e($dokumen->deskripsi)) !!}
                    </p>
                </section>

                <p>
                    <span class="font-semibold text-gray-700 dark:text-gray-400">Uploader:</span>
                    {{ $dokumen->user->name ?? 'Tidak diketahui' }}
                </p>

                <section>
                    <h2 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">File Dokumen</h2>
                    @if ($dokumen->path_dokumen && \Illuminate\Support\Facades\Storage::disk('public')->exists($dokumen->path_dokumen))
                        <iframe src="{{ asset('storage/' . $dokumen->path_dokumen, true) }}"
                                class="w-full h-[700px] rounded-xl border border-gray-300 dark:border-gray-700 shadow-md"
                                style="border:none;"></iframe>
                    @else
                        <p class="text-red-500 font-semibold">Dokumen tidak ditemukan.</p>
                    @endif
                </section>
            </div>

        </div>
    </div>
</x-app-layout>
