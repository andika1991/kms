@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

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
                    <!-- Dropdown Profile -->
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
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BODY GRID -->
        <div class="flex flex-col lg:flex-row gap-8 px-4 md:px-12 pt-8 pb-10 flex-1 w-full max-w-7xl mx-auto">
            <!-- FORM -->
            <form method="POST" action="{{ route('kepalabagian.forum.update', $grupchat->id) }}"
                class="flex-1 max-w-3xl mx-auto bg-white rounded-2xl shadow-xl p-6 md:p-10 flex flex-col gap-8"
                autocomplete="off" id="submit">
                @csrf
                @method('PUT')

                <div>
                    <div class="font-bold text-lg md:text-xl text-[#222] mb-1">Informasi Forum Diskusi</div>
                    <div class="text-gray-500 text-sm mb-5">Tambahkan informasi tentang forum</div>

                    <!-- Nama Grup -->
                    <label class="block text-gray-700 font-semibold mb-2" for="nama_grup">Nama Forum</label>
                    <input id="nama_grup" type="text" name="nama_grup" placeholder="Nama Forum"
                        value="{{ old('nama_grup', $grupchat->nama_grup) }}"
                        class="w-full mb-4 rounded-xl border border-gray-300 px-5 py-3 bg-white shadow focus:outline-none focus:ring-2 focus:ring-blue-500 text-base font-semibold"
                        required>
                    @error('nama_grup')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Deskripsi -->
                    <label class="block text-gray-700 font-semibold mb-2" for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Deskripsi"
                        class="w-full mb-4 rounded-xl border border-gray-300 px-5 py-3 bg-white shadow focus:outline-none focus:ring-2 focus:ring-blue-500 text-base">{{ old('deskripsi', $grupchat->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Grup Role -->
                    <label class="block text-gray-700 font-semibold mb-2" for="grup_role">Role (Opsional)</label>
                    <input id="grup_role" type="text" name="grup_role" value="{{ old('grup_role', $grupchat->grup_role) }}"
                        placeholder="Role (Opsional)"
                        class="w-full mb-2 rounded-xl border border-gray-300 px-5 py-3 bg-white shadow focus:outline-none focus:ring-2 focus:ring-blue-500 text-base">
                    @error('grup_role')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Private Checkbox -->
                <div class="mb-4 flex items-center gap-2 bg-white rounded-xl px-5 py-2 shadow">
                    <input type="checkbox" name="is_private" id="is_private" value="1"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        {{ old('is_private', $grupchat->is_private) ? 'checked' : '' }}>
                    <label for="is_private" class="text-gray-800 font-semibold">Private Forum</label>
                </div>

                <!-- Pilih Anggota Grup -->
                <div id="users-field"
                    class="mb-2 bg-white rounded-xl px-5 py-3 shadow transition-all @if(!old('is_private', $grupchat->is_private)) hidden @endif">
                    <label class="block text-gray-700 font-semibold mb-2">Pilih Anggota</label>
                    <select id="user-select" name="pengguna_id[]" multiple
                        class="w-full rounded-xl border border-gray-300 px-3 py-2 bg-white text-base focus:outline-none focus:ring-2 focus:ring-blue-500 shadow"
                        placeholder="Cari dan pilih anggota grup...">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ in_array($user->id, $anggota_ids) ? 'selected' : '' }}>
                                {{ $user->decrypted_name }} ({{ $user->decrypted_email }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-gray-500">Ketik untuk mencari dan pilih anggota grup. Bisa pilih lebih dari satu.</small>
                </div>

                <!-- Bidang -->
                <div id="bidang-field"
                    class="mb-4 bg-white rounded-xl px-5 py-3 shadow transition-all @if(old('is_private', $grupchat->is_private)) hidden @endif">
                    <label class="block text-gray-700 font-semibold mb-2">Bidang</label>
                    <select name="bidang_id"
                        class="w-full rounded-xl border border-gray-300 px-3 py-2 bg-white text-base focus:outline-none focus:ring-2 focus:ring-blue-500 shadow" readonly>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}"
                                {{ $grupchat->bidang_id == $bidang->id ? 'selected' : '' }}>
                                {{ $bidang->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                 <button type="submit"
                        class="flex-1 px-6 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition text-base">
                        Update
                    </button>
            </form>

            <!-- SIDEBAR KANAN -->
            <aside class="w-full lg:w-80 flex flex-col gap-6">
                <!-- Kartu Bidang -->
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight">Bidang
                            {{ Auth::user()->role->nama_role ?? 'KepalaBagian' }}</p>
                    </div>
                </div>
                <!-- Tombol -->
                <div class="flex gap-3">
                   
                    <a href="{{ route('kepalabagian.forum.index') }}"
                        class="flex-1 px-6 py-3 rounded-xl bg-[#ad3a2c] hover:bg-[#992b1e] text-white font-semibold shadow transition text-base text-center">
                        Batalkan
                    </a>
                </div>
            </aside>
        </div>
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
        new TomSelect("#user-select", {
            maxItems: null,
            valueField: "value",
            labelField: "text",
            searchField: ["text"],
        });

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