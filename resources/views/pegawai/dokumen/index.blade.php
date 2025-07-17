<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manajemen Dokumen
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Flash Message --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- Search & Tambah --}}
        <div class="flex justify-between mb-6">
            <form action="{{ route('pegawai.manajemendokumen.index') }}" method="GET" class="flex w-full max-w-md">
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

            <a href="{{ route('pegawai.manajemendokumen.create') }}"
               class="ml-4 inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                + Tambah Dokumen
            </a>
        </div>

        @if($dokumen->count())
            <div class="grid grid-cols-1 gap-4">
                @foreach($dokumen as $item)
                    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow flex flex-col sm:flex-row">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                                {{ $item->nama_dokumen }}
                                @if($item->kategoriDokumen && $item->kategoriDokumen->nama_kategoridokumen == 'Rahasia')
                                    <span class="text-red-500 text-xs ml-2">(Rahasia)</span>
                                @endif
                            </h3>

                            <p class="text-gray-600 dark:text-gray-300 text-sm">
                                Kategori: 
                                <span class="font-semibold">
                                    {{ $item->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                                </span>
                            </p>

                            <p class="text-gray-700 dark:text-gray-300 mt-2">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 100) }}
                            </p>
                        </div>

                        <div class="flex gap-2 mt-4 sm:mt-0 sm:ml-4">
                            @if($item->kategoriDokumen && $item->kategoriDokumen->nama_kategoridokumen == 'Rahasia')
                                <button 
                                    data-id="{{ $item->id }}"
                                    data-nama="{{ $item->nama_dokumen }}"
                                    onclick="showKeyModal(this)"
                                    class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 text-sm rounded">
                                    Lihat
                                </button>
                            @else
                                <a href="{{ route('pegawai.manajemendokumen.show', $item->id) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 text-sm rounded">
                                    Lihat
                                </a>
                            @endif

                            <a href="{{ route('pegawai.manajemendokumen.edit', $item->id) }}"
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 text-sm rounded">
                                Edit
                            </a>

                            <form action="{{ route('pegawai.manajemendokumen.destroy', $item->id) }}"
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

    <!-- Modal Input Kunci -->
    <div id="keyModal" class="fixed z-50 inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded p-6 w-full max-w-md">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100" id="modalTitle">
                Masukkan Kunci Dokumen
            </h2>
            <form id="keyForm" method="GET">
                <input type="password" name="encrypted_key" 
                       placeholder="Kunci Dokumen"
                       class="w-full mb-4 px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-200">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeKeyModal()" class="px-4 py-2 rounded bg-gray-400 text-white">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">
                        Lihat Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showKeyModal(button) {
            const id = button.dataset.id;
            const nama = button.dataset.nama;

            document.getElementById('modalTitle').innerText = 'Masukkan Kunci Dokumen: ' + nama;

            let form = document.getElementById('keyForm');
            form.action = "/pegawai/manajemendokumen/" + id;

            document.getElementById('keyModal').classList.remove('hidden');
        }

        function closeKeyModal() {
            document.getElementById('keyModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
