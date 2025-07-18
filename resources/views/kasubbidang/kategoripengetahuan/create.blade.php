<x-app-layout>

     
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">
                Tambah Kategori Pengetahuan
            </h1>

            <form action="{{ route('kasubbidang.kategoripengetahuan.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <x-input-label for="nama_kategoripengetahuan" :value="'Nama Kategori'" />
                    <x-text-input id="nama_kategoripengetahuan" class="block mt-1 w-full" type="text" name="nama_kategoripengetahuan" value="{{ old('nama_kategoripengetahuan') }}" required autofocus />
                    <x-input-error :messages="$errors->get('nama_kategoripengetahuan')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="deskripsi" :value="'Deskripsi'" />
                    <textarea name="deskripsi" id="deskripsi" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">{{ old('deskripsi') }}</textarea>
                    <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                </div>

                <div class="flex justify-end">
                    <x-primary-button>
                        Simpan
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
