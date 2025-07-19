@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] flex flex-col pb-10">
        {{-- Header --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                {{-- Search Bar (Optional) --}}
                <div class="hidden sm:flex items-center gap-4 w-full sm:w-auto">
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Grid --}}
        <div class="px-4 md:px-8 grid grid-cols-1 xl:grid-cols-12 gap-8 mt-6">
            <form method="POST" action="{{ route('magang.manajemendokumen.store') }}" enctype="multipart/form-data"
                class="bg-white rounded-2xl shadow-lg p-8 xl:col-span-8 flex flex-col gap-7">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                    {{-- Dokumen Preview/Upload --}}
                    <div class="flex flex-col items-center justify-center h-full">
                        <label for="path_dokumen" class="w-full">
                            <div
                                class="border-2 border-gray-300 border-dashed rounded-xl h-60 flex flex-col justify-center items-center cursor-pointer hover:bg-gray-50 transition">
                                <i class="fa fa-upload text-4xl text-gray-400 mb-2"></i>
                                <span class="text-gray-500 text-base">Tambah dokumen</span>
                                <input type="file" name="path_dokumen" id="path_dokumen" class="hidden" required>
                            </div>
                        </label>
                        @error('path_dokumen')
                        <span class="text-red-500 text-xs mt-2">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Kolom Form --}}
                    <div class="flex flex-col gap-5">
                        {{-- Judul --}}
                        <div>
                            <label class="block font-semibold text-gray-800 mb-1">Judul</label>
                            <input type="text" name="nama_dokumen" value="{{ old('nama_dokumen') }}"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>
                        {{-- Kategori --}}
                        <div>
                            <label class="block font-semibold text-gray-800 mb-1">Kategori</label>
                            <select id="kategoriSelect" name="kategori_dokumen_id"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="">Pilih Kategori</option>
                                @forelse($kategori as $kat)
                                    <option value="{{ $kat->id }}" data-nama="{{ strtolower($kat->nama_kategoridokumen) }}"
                                        {{ old('kategori_dokumen_id') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama_kategoridokumen }}
                                        @if($kat->subbidang)
                                            — {{ $kat->subbidang->nama }}
                                        @endif
                                    </option>
                                @empty
                                    <option disabled>Tidak ada kategori tersedia</option>
                                @endforelse
                            </select>
                        </div>
                        {{-- Deskripsi --}}
                        <div>
                            <label class="block font-semibold text-gray-800 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" rows="4"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500"
                                required>{{ old('deskripsi') }}</textarea>
                        </div>
                        {{-- Field Kunci Rahasia --}}
                        <div id="encrypted-key-field" class="hidden">
                            <label class="block font-semibold text-gray-800 mb-1">Kunci Rahasia / Encrypted Key</label>
                            <input type="text" name="encrypted_key"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            </form>

            {{-- Sidebar Aksi --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'User' }}</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <button type="submit"
                        form="manajemen-dokumen-form"
                        class="flex-1 px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                        Simpan
                    </button>
                    <a href="{{ url()->previous() }}"
                        class="flex-1 px-6 py-2 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base text-center">
                        Batalkan
                    </a>
                </div>
            </aside>
        </div>
    </div>

    {{-- Footer --}}
    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                    class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>

    {{-- Script Field Kunci Rahasia --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const kategoriSelect = document.getElementById('kategoriSelect');
        const keyField = document.getElementById('encrypted-key-field');

        function toggleKeyField() {
            const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
            const namaKategori = selectedOption.getAttribute('data-nama');
            if (namaKategori === 'rahasia') {
                keyField.classList.remove('hidden');
            } else {
                keyField.classList.add('hidden');
            }
        }
        kategoriSelect.addEventListener('change', toggleKeyField);
        toggleKeyField();
    });
    </script>
</x-app-layout>