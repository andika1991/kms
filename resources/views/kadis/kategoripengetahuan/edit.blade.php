<x-app-layout>
    <div x-data="{ showSaveModal: false }"
        class="fixed inset-0 flex items-center justify-center min-h-screen bg-gray-200" style="z-index:30;">
        {{-- Modal Edit Kategori --}}
        <div
            class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md flex flex-col items-center border border-gray-200 animate-fade-in">
            <img src="{{ asset('assets/img/folder-blue.png') }}" alt="Edit Icon" class="w-20 h-20 mb-3 drop-shadow" />
            <h1 class="text-xl font-bold text-center mb-4 text-gray-800">Edit Kategori Pengetahuan</h1>
            <form id="editKategoriForm"
                action="{{ route('kadis.kategoripengetahuan.update', $kategoripengetahuan->id) }}" method="POST"
                class="w-full space-y-4" @submit.prevent="showSaveModal = true">
                @csrf
                @method('PUT')
                <input type="text" name="nama_kategoripengetahuan"
                    value="{{ old('nama_kategoripengetahuan', $kategoripengetahuan->nama_kategoripengetahuan) }}"
                    placeholder="Masukkan Kategori" required autofocus
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow transition placeholder-gray-400 text-base">
                @error('nama_kategoripengetahuan')
                <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror

                <textarea name="deskripsi" placeholder="Deskripsi kategori (opsional)" rows="2"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow transition placeholder-gray-400 text-base">{{ old('deskripsi', $kategoripengetahuan->deskripsi) }}</textarea>
                @error('deskripsi')
                <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror

                <div class="flex justify-end pt-2">
                    {{-- Tombol bukan submit, trigger modal --}}
                    <button type="button" @click="showSaveModal = true"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-2 rounded-xl shadow transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Modal Konfirmasi Simpan Perubahan --}}
        <div x-show="showSaveModal" class="fixed inset-0 flex items-center justify-center bg-black/30 z-50"
            style="display: none">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-xs p-6 text-center animate-fade-in">
                <div class="flex flex-col items-center">
                    {{-- Ikon Ceklis --}}
                    <svg class="w-12 h-12 mb-2" fill="none" viewBox="0 0 48 48">
                        <circle cx="24" cy="24" r="22" fill="#eafbee" stroke="#32c671" stroke-width="3" />
                        <path d="M15 25l6 6 12-13" stroke="#32c671" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <h2 class="font-bold text-lg text-gray-900 mb-1">Apakah Anda Yakin</h2>
                    <p class="text-gray-600 text-sm mb-6">perubahan akan disimpan</p>
                </div>
                <div class="flex gap-2 justify-center">
                    <button type="button" @click="showSaveModal = false"
                        class="bg-[#a44d3a] hover:bg-[#943b2b] text-white px-6 py-2 rounded-lg font-semibold w-1/2">Tidak</button>
                    <button type="button" @click="document.getElementById('editKategoriForm').submit();"
                        class="bg-[#32c671] hover:bg-[#259a51] text-white px-6 py-2 rounded-lg font-semibold w-1/2">Ya</button>
                </div>
            </div>
        </div>
    </div>
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