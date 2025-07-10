<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manajemen Dokumen
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Tombol Tambah + Search --}}
        <div class="flex justify-between mb-6">
            <form action="{{ route('kepalabagian.manajemendokumen.index') }}" method="GET" class="flex w-full max-w-md">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari nama dokumen..." 
                    class="w-full rounded-l border-gray-300 dark:bg-gray-700 dark:text-gray-200 focus:ring focus:ring-blue-500"
                >
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r">
                    Cari
                </button>
            </form>

            <a href="{{ route('kepalabagian.manajemendokumen.create') }}"
               class="ml-4 inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                + Tambah Dokumen
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($dokumen->count())
            <div class="grid grid-cols-1 gap-4">
                @foreach($dokumen as $item)
                    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow flex flex-col sm:flex-row">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                                {{ $item->nama_dokumen }}
                            </h3>

                            <p class="text-gray-600 dark:text-gray-300 text-sm">
                                Kategori: <span class="font-semibold">{{ $item->kategoriDokumen->nama_kategoridokumen ?? '-' }}</span>
                            </p>

                            <p class="text-gray-700 dark:text-gray-300 mt-2">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 100) }}
                            </p>
                        </div>

                        <div class="flex gap-2 mt-4 sm:mt-0 sm:ml-4">
                            <a href="{{ route('kepalabagian.manajemendokumen.show', $item->id) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 text-sm rounded">
                                Lihat
                            </a>

                            <a href="{{ route('kepalabagian.manajemendokumen.edit', $item->id) }}"
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 text-sm rounded">
                                Edit
                            </a>

                            <form action="{{ route('kepalabagian.manajemendokumen.destroy', $item->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus dokumen ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 text-sm rounded">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-700 dark:text-gray-300">
                Belum ada dokumen yang tersedia.
            </p>
        @endif

    </div>
</x-app-layout>
