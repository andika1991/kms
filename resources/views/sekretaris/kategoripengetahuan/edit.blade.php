<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Kategori Pengetahuan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('sekretaris.kategoripengetahuan.update', $kategoripengetahuan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">
                            Nama Kategori
                        </label>
                        <input type="text" name="nama_kategoripengetahuan" value="{{ old('nama_kategoripengetahuan', $kategoripengetahuan->nama_kategoripengetahuan) }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:shadow-outline">
                        @error('nama_kategoripengetahuan')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
