<x-app-layout>
    @php
        // Tujuan kembali yang aman
        $backHref = url()->previous();
        if (!$backHref || $backHref === url()->current()) {
            $backHref = route('kasubbidang.berbagipengetahuan.index');
        }
    @endphp

    <div
        x-data="{ showSaveModal: false }"
        class="fixed inset-0 flex items-center justify-center min-h-screen bg-black/10 backdrop-blur-sm"
        style="z-index:30;"
        role="dialog" aria-modal="true"
        {{-- klik di luar kartu untuk keluar --}}
        onclick="if (event.target === this) { window.location.href = @json($backHref); }"
        {{-- Esc: tutup modal konfirmasi jika terbuka, selain itu keluar --}}
        @keydown.escape.window="showSaveModal ? showSaveModal = false : window.location.href = @json($backHref)"
    >
        {{-- KARTU EDIT --}}
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
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor">
                    <path fill-rule="evenodd" d="M6.225 4.811a1 1 0 0 1 1.414 0L12 9.172l4.361-4.361a1 1 0 1 1 1.414 1.414L13.414 10.586l4.361 4.361a1 1 0 0 1-1.414 1.414L12 12l-4.361 4.361a1 1 0 1 1-1.414-1.414l4.361-4.361-4.361-4.361a1 1 0 0 1 0-1.414Z" clip-rule="evenodd"/>
                </svg>
            </a>

            {{-- Ikon & Judul --}}
            <img src="{{ asset('assets/img/folder-blue.png') }}" alt="Edit Icon" class="w-20 h-20 mb-3 drop-shadow" />
            <h1 class="text-xl font-bold text-center mb-4 text-gray-800">Edit Kategori Pengetahuan</h1>

            {{-- FORM --}}
            <form
                id="editKategoriForm"
                action="{{ route('kasubbidang.kategoripengetahuan.update', $kategoripengetahuan->id) }}"
                method="POST"
                class="w-full space-y-4"
                @submit.prevent="showSaveModal = true"
            >
                @csrf
                @method('PUT')

                <input
                    type="text"
                    name="nama_kategoripengetahuan"
                    value="{{ old('nama_kategoripengetahuan', $kategoripengetahuan->nama_kategoripengetahuan) }}"
                    placeholder="Masukkan Kategori"
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
                >{{ old('deskripsi', $kategoripengetahuan->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror

                <div class="flex justify-end pt-2">
                    {{-- Tombol bukan submit langsung; memunculkan modal konfirmasi --}}
                    <button
                        type="button"
                        @click="showSaveModal = true"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-2 rounded-xl shadow transition"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- MODAL KONFIRMASI SIMPAN --}}
        <div
            x-show="showSaveModal"
            class="fixed inset-0 flex items-center justify-center bg-black/30 z-50"
            style="display: none"
            @click.self="showSaveModal = false"
        >
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-xs p-6 text-center animate-fade-in">
                <div class="flex flex-col items-center">
                    <svg class="w-12 h-12 mb-2" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <circle cx="24" cy="24" r="22" fill="#eafbee" stroke="#32c671" stroke-width="3" />
                        <path d="M15 25l6 6 12-13" stroke="#32c671" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <h2 class="font-bold text-lg text-gray-900 mb-1">Apakah Anda Yakin</h2>
                    <p class="text-gray-600 text-sm mb-6">perubahan akan disimpan</p>
                </div>
                <div class="flex gap-2 justify-center">
                    <button
                        type="button"
                        @click="showSaveModal = false"
                        class="bg-[#a44d3a] hover:bg-[#943b2b] text-white px-6 py-2 rounded-lg font-semibold w-1/2"
                    >
                        Tidak
                    </button>
                    <button
                        type="button"
                        @click="document.getElementById('editKategoriForm').submit();"
                        class="bg-[#32c671] hover:bg-[#259a51] text-white px-6 py-2 rounded-lg font-semibold w-1/2"
                    >
                        Ya
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Animasi sederhana --}}
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: scale(0.95); }
            to   { opacity: 1; transform: scale(1); }
        }
        .animate-fade-in { animation: fade-in 0.25s cubic-bezier(0.4, 0, 0.2, 1) both; }
    </style>
</x-app-layout>
