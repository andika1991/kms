<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Artikel Pengetahuan') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Tombol Tambah Artikel --}}
        <div class="mb-6 flex justify-between items-center">
            <form method="GET" action="{{ route('kepalabagian.artikelpengetahuan.index') }}" class="w-full max-w-md">
                <div class="flex">
                    <input type="text" name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari judul artikel..." 
                        class="w-full rounded-l-md border-gray-300 dark:bg-gray-700 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700">
                        Cari
                    </button>
                </div>
            </form>

            <a href="{{ route('magang.berbagipengetahuan.create') }}"
               class="ml-4 inline-block px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded">
                + Tambah Artikel
            </a>
        </div>

        @if($artikels->count())
            <div class="grid grid-cols-1 gap-4">
                @foreach($artikels as $artikel)
                    <div class="bg-white dark:bg-gray-800 rounded shadow p-4 flex flex-col sm:flex-row">
                        
                        {{-- Thumbnail --}}
                        @if($artikel->thumbnail)
                            <div class="w-full sm:w-48 mb-4 sm:mb-0 sm:mr-4">
                                <img src="{{ asset('storage/' . $artikel->thumbnail) }}" 
                                     class="w-full h-32 object-cover rounded" 
                                     alt="Thumbnail">
                            </div>
                        @endif

                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                                {{ $artikel->judul }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                                Kategori: <span class="font-semibold">{{ $artikel->kategoriPengetahuan->nama_kategoripengetahuan ?? '-' }}</span>
                            </p>
                            <p class="text-gray-700 dark:text-gray-300 line-clamp-3">
                                {{ \Illuminate\Support\Str::limit(strip_tags($artikel->isi), 120) }}
                            </p>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <a href="{{ route('magang.berbagipengetahuan.show', $artikel->id) }}"
                                   class="inline-block px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded">
                                    Lihat Detail
                                </a>

                                <a href="{{ route('magang.berbagipengetahuan.edit', $artikel->id) }}"
                                   class="inline-block px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-black text-xs font-semibold rounded">
                                    Edit
                                </a>

                                <form action="{{ route('magang.berbagipengetahuan.destroy', $artikel->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-700 dark:text-gray-300">
                Belum ada artikel pengetahuan.
            </p>
        @endif
    </div>
</x-app-layout>
