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
         <embed 
    src="{{ asset('storage/' . $dokumen->path_dokumen) }}#zoom=150" 
    type="application/pdf" 
    width="100%" 
    height="600px">


            <div class="mt-6">
                <a href="{{ route('magang.manajemendokumen.index') }}" 
                   class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                    Kembali ke daftar dokumen
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
