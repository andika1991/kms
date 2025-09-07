<x-app-layout>
    @php
        // Tentukan tujuan kembali yang aman
        $backHref = url()->previous();
        if (!$backHref || $backHref === url()->current()) {
            $backHref = route('kasubbidang.berbagipengetahuan.index');
        }
    @endphp

    {{-- Overlay Modal Style --}}
    <div
        class="fixed inset-0 z-40 bg-black/10 backdrop-blur-sm flex items-center justify-center min-h-screen"
        role="dialog" aria-modal="true"
        onclick="if (event.target === this) { window.location.href = @json($backHref); }"
    >
        {{-- Modal/Card --}}
        <div
            class="relative bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md flex flex-col items-center border border-gray-200 animate-fade-in"
        >
            {{-- Tombol Tutup (X) --}}
            <a
                href="{{ $backHref }}"
                title="Tutup"
                class="absolute right-4 top-4 inline-flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-800 shadow focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <span class="sr-only">Tutup</span>
                {{-- Ikon X (SVG) --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M6.225 4.811a1 1 0 0 1 1.414 0L12 9.172l4.361-4.361a1 1 0 1 1 1.414 1.414L13.414 10.586l4.361 4.361a1 1 0 0 1-1.414 1.414L12 12l-4.361 4.361a1 1 0 1 1-1.414-1.414l4.361-4.361-4.361-4.361a1 1 0 0 1 0-1.414Z"
                          clip-rule="evenodd"/>
                </svg>
            </a>

            {{-- Icon Folder Plus --}}
            <img src="{{ asset('assets/img/folder-blue.png') }}" alt="Plus Icon" class="w-20 h-20 mb-3 drop-shadow" />

            {{-- Judul --}}
            <h1 class="text-xl font-bold text-center mb-6 text-gray-800">
                Tambah Kategori Pengetahuan
            </h1>

            {{-- Form --}}
            <form action="{{ route('kasubbidang.kategoripengetahuan.store') }}" method="POST" class="w-full space-y-5">
                @csrf

                <input
                    type="text"
                    name="nama_kategoripengetahuan"
                    placeholder="Masukkan Kategori"
                    value="{{ old('nama_kategoripengetahuan') }}"
                    required
                    autofocus
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow transition placeholder-gray-400 text-base"
                >
                @error('nama_kategoripengetahuan')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror

                <textarea
                    name="deskripsi"
                    placeholder="Deskripsi kategori (opsional)"
                    rows="2"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow transition placeholder-gray-400 text-base"
                >{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror

                <div class="flex justify-end pt-2">
                    <button
                        type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-2 rounded-xl shadow transition"
                    >
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tambahan animasi modal sederhana --}}
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: scale(0.95); }
            to   { opacity: 1; transform: scale(1); }
        }
        .animate-fade-in { animation: fade-in 0.25s cubic-bezier(0.4, 0, 0.2, 1) both; }
    </style>

    {{-- Tutup dengan tombol ESC --}}
    <script>
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                window.location.href = @json($backHref);
            }
        });
    </script>
</x-app-layout>
