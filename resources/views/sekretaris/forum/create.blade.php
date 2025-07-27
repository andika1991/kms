@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Tambah Forum Diskusi Sekretaris')

<x-app-layout>
    <div class="bg-[#eaf5ff] min-h-screen w-full flex flex-col">
        <!-- HEADER -->
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Forum Diskusi</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari Forum..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                    <!-- Profile Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT + SIDEBAR: FORM WRAPS BOTH! -->
        <form method="POST" action="{{ route('sekretaris.forum.store') }}"
            class="flex flex-col lg:flex-row gap-8 px-4 md:px-12 pt-8 pb-10 flex-1 w-full max-w-7xl mx-auto"
            autocomplete="off">
            @csrf

            <!-- FORM UTAMA -->
            <div class="flex-1">
                <div class="bg-white rounded-2xl shadow-lg px-6 md:px-12 py-8 flex flex-col gap-6 max-w-2xl mx-auto">
                    <div>
                        <h3 class="font-bold text-lg mb-1">Informasi Forum Diskusi</h3>
                        <p class="text-sm text-gray-500 mb-4">Tambahkan informasi tentang forum</p>
                    </div>
                    <!-- Nama Grup -->
                    <div>
                        <label class="block text-gray-700 mb-1 font-semibold">Nama Forum</label>
                        <input type="text" name="nama_grup" value="{{ old('nama_grup') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-[#f5fafd] text-base focus:ring-2 focus:ring-blue-400 transition placeholder:text-gray-400"
                            placeholder="Nama Forum" required>
                        @error('nama_grup')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-gray-700 mb-1 font-semibold">Deskripsi Forum</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-[#f5fafd] text-base focus:ring-2 focus:ring-blue-400 transition placeholder:text-gray-400"
                            placeholder="Deskripsi">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Grup Role -->
                    <div>
                        <label class="block text-gray-700 mb-1 font-semibold">Role Forum (Opsional)</label>
                        <input type="text" name="grup_role" value="{{ old('grup_role') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-[#f5fafd] text-base focus:ring-2 focus:ring-blue-400 transition placeholder:text-gray-400"
                            placeholder="Role (Opsional)">
                        @error('grup_role')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Private Grup Checkbox -->
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_private" id="is_private" value="1"
                            class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-400"
                            {{ old('is_private') ? 'checked' : '' }}>
                        <label for="is_private" class="text-base text-gray-700">Private Forum</label>
                    </div>
                    <!-- Pilih Anggota (jika private) -->
                    <div id="users-field" class="hidden">
                        <label class="block text-gray-700 mb-1">Pilih Anggota Grup</label>
                        <select id="user-select" name="pengguna_id[]" multiple
                            class="w-full rounded-xl border border-gray-300 bg-[#f5fafd] text-base">
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->decrypted_name }} ({{ $user->decrypted_email }})
                            </option>
                            @endforeach
                        </select>
                        <small class="text-gray-500">Ketik untuk mencari dan pilih anggota grup. Bisa pilih lebih dari
                            satu.</small>
                    </div>
                    <!-- Pilih Bidang (jika publik) -->
                    <div id="bidang-field">
                        <label class="block text-gray-700 mb-1">Bidang (Untuk Grup Umum)</label>
                        <select name="bidang_id" class="w-full rounded-xl border border-gray-300 bg-[#f5fafd] text-base"
                            readonly>
                            @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}" selected>{{ $bidang->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR KANAN -->
            <aside class="w-full lg:w-80 flex flex-col gap-6">
                <!-- Kartu Bidang -->
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight">Bidang
                            {{ Auth::user()->role->nama_role ?? 'Sekretaris' }}</p>
                    </div>
                </div>
                <!-- Tombol -->
                <div class="flex gap-3 mt-2">
                    <button type="submit"
                        class="flex-1 px-6 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition text-base">
                        Simpan
                    </button>
                    <a href="{{ route('sekretaris.forum.index') }}"
                        class="flex-1 px-6 py-3 rounded-xl bg-[#ad3a2c] hover:bg-[#992b1e] text-white font-semibold shadow transition text-base text-center">
                        Batalkan
                    </a>
                </div>
            </aside>
        </form>
    </div>
    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                    class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const isPrivateCheckbox = document.getElementById('is_private');
        const bidangField = document.getElementById('bidang-field');
        const usersField = document.getElementById('users-field');

        function toggleFields() {
            if (isPrivateCheckbox.checked) {
                bidangField.classList.add('hidden');
                usersField.classList.remove('hidden');
            } else {
                bidangField.classList.remove('hidden');
                usersField.classList.add('hidden');
            }
        }

        isPrivateCheckbox.addEventListener('change', toggleFields);
        toggleFields();
    });


    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi TomSelect untuk multiple select dengan search
        new TomSelect("#user-select", {
            maxItems: null, // Bisa pilih banyak
            valueField: "value",
            labelField: "text",
            searchField: ["text"],
            // optional config, misal highlight hasil pencarian, dll
        });

        // Toggle bidang & anggota grup seperti sebelumnya (jika masih ingin)
        const isPrivateCheckbox = document.getElementById('is_private');
        const bidangField = document.getElementById('bidang-field');
        const usersField = document.getElementById('user-select').closest('div');

        function toggleFields() {
            if (isPrivateCheckbox.checked) {
                bidangField.classList.add('hidden');
                usersField.classList.remove('hidden');
            } else {
                bidangField.classList.remove('hidden');
                usersField.classList.add('hidden');
            }
        }

        isPrivateCheckbox.addEventListener('change', toggleFields);
        toggleFields();
    });
    </script>
</x-app-layout>