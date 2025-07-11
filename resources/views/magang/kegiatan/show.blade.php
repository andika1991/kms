<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Kegiatan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
                    {{ $kegiatan->nama_kegiatan }}
                </h3>

                <p class="mb-2 text-gray-700 dark:text-gray-300">
                    <strong>Kategori:</strong> {{ ucfirst($kegiatan->kategori_kegiatan) }}
                </p>

                <p class="mb-4 text-gray-700 dark:text-gray-300">
                    <strong>Deskripsi:</strong> {{ $kegiatan->deskripsi_kegiatan }}
                </p>

                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-2">Foto Kegiatan:</h4>

                @if ($kegiatan->fotokegiatan->count())
                    <div class="flex flex-wrap gap-4">
                        @foreach ($kegiatan->fotokegiatan as $foto)
                            <div>
                                <img src="{{ asset('storage/' . $foto->path_foto) }}"
                                     class="h-32 w-32 object-cover rounded border" alt="Foto Kegiatan">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-300">Belum ada foto kegiatan.</p>
                @endif

                <div class="mt-6">
                    <a href="{{ route('magang.kegiatan.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
