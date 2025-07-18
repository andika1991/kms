<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Pengguna
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        {{-- Flash Message --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <form action="{{ route('kasubbidang.manajemenpengguna.update', $pengguna->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 dark:text-gray-200 mb-1 font-semibold">Nama</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $pengguna->name) }}"
                           class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-200"
                           required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 dark:text-gray-200 mb-1 font-semibold">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $pengguna->email) }}"
                           class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-200"
                           required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 dark:text-gray-200 mb-1 font-semibold">
                        Password <small class="text-gray-500">(Kosongkan jika tidak ingin diubah Kata Sandinya)</small>
                    </label>
                    <input type="password" name="password" id="password"
                           class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-gray-200"
                           placeholder="Masukkan password baru jika ingin mengganti">
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('kasubbidang.manajemenpengguna.index') }}"
                       class="px-4 py-2 rounded bg-gray-400 text-white hover:bg-gray-500">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
