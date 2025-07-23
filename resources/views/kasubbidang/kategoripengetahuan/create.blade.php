<x-app-layout>
    {{-- Overlay Modal Style --}}
    <div class="fixed inset-0 z-40 bg-black/10 backdrop-blur-sm flex items-center justify-center min-h-screen">
        {{-- Modal/Card --}}
        <div
            class="relative bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md flex flex-col items-center border border-gray-200 animate-fade-in">
            {{-- Icon Folder Plus --}}
            <img src="{{ asset('assets/img/folder-blue.png') }}" alt="Plus Icon" class="w-20 h-20 mb-3 drop-shadow" />
            {{-- Judul --}}
            <h1 class="text-xl font-bold text-center mb-6 text-gray-800">
                Tambah Kategori Pengetahuan
            </h1>
            {{-- Form --}}
            <form action="{{ route('kasubbidang.kategoripengetahuan.store') }}" method="POST" class="w-full space-y-5">
                @csrf
                <input type="text" name="nama_kategoripengetahuan" placeholder="Masukkan Kategori"
                    value="{{ old('nama_kategoripengetahuan') }}" required autofocus
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow transition placeholder-gray-400 text-base">
                @error('nama_kategoripengetahuan')
                <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror

                <textarea name="deskripsi" placeholder="Deskripsi kategori (opsional)" rows="2"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow transition placeholder-gray-400 text-base">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror

                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-2 rounded-xl shadow transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tambahan animasi modal sederhana --}}
    <style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.25s cubic-bezier(0.4, 0, 0.2, 1) both;
    }
    </style>
</x-app-layout>