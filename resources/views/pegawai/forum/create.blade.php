@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Tambah Forum Pegawai')

<x-app-layout>
    <div class="bg-[#eaf5ff] min-h-screen w-full flex flex-col">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Forum Diskusi</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search --}}
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari Forum..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>

                    {{-- Profile dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition
                            style="display:none;">
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
                                        Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT + SIDEBAR --}}
        <form id="form-forum-pegawai" method="POST" action="{{ route('pegawai.forum.store') }}"
            class="flex flex-col lg:flex-row gap-8 px-4 md:px-12 pt-8 pb-10 flex-1 w-full max-w-7xl mx-auto"
            autocomplete="off">
            @csrf

            {{-- FORM UTAMA --}}
            <div class="flex-1">
                <div class="bg-white rounded-2xl shadow-lg px-6 md:px-12 py-8 flex flex-col gap-6 max-w-2xl mx-auto">
                    <div>
                        <h3 class="font-bold text-lg mb-1">Informasi Forum Diskusi</h3>
                        <p class="text-sm text-gray-500">Tambahkan informasi tentang forum.</p>
                    </div>

                    {{-- Nama Forum --}}
                    <div>
                        <label class="block text-gray-700 mb-1 font-semibold">Nama Forum</label>
                        <input type="text" name="nama_grup" value="{{ old('nama_grup') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-[#f5fafd] text-base focus:ring-2 focus:ring-blue-400 transition placeholder:text-gray-400"
                            placeholder="Nama Forum" required>
                        @error('nama_grup') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-gray-700 mb-1 font-semibold">Deskripsi</label>
                        <textarea name="deskripsi" rows="4"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-[#f5fafd] text-base focus:ring-2 focus:ring-blue-400 transition placeholder:text-gray-400"
                            placeholder="Deskripsi singkat">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Role (opsional) --}}
                    <div>
                        <label class="block text-gray-700 mb-1 font-semibold">Role Forum (Opsional)</label>
                        <input type="text" name="grup_role" value="{{ old('grup_role') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-[#f5fafd] text-base focus:ring-2 focus:ring-blue-400 transition placeholder:text-gray-400"
                            placeholder="Role (Opsional)">
                        @error('grup_role') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Private switch --}}
                    <div class="flex items-center justify-between mt-2">
                        <div>
                            <h4 class="font-bold text-lg text-gray-800">Private Forum</h4>
                            <p class="text-sm text-gray-500">Aktifkan jika forum ini hanya untuk anggota tertentu.</p>
                        </div>
                        <label for="is_private" class="flex items-center cursor-pointer select-none">
                            <div class="relative">
                                <input type="checkbox" id="is_private" name="is_private" value="1" class="sr-only"
                                    {{ old('is_private') ? 'checked' : '' }}>
                                <div class="block w-14 h-8 rounded-full bg-gray-200"></div>
                                <div class="dot absolute left-1 top-1 w-6 h-6 rounded-full bg-white transition"></div>
                            </div>
                        </label>
                    </div>

                    {{-- Bidang (untuk grup umum) --}}
                    <div id="bidang-field" class="mt-2">
                        <label class="block text-gray-700 mb-1 font-semibold">Bidang (Untuk Grup Umum)</label>
                        <select name="bidang_id"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-[#f5fafd] text-base focus:ring-2 focus:ring-blue-400 transition">
                            <option value="">-- Pilih Bidang --</option>
                            @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}" {{ old('bidang_id') == $bidang->id ? 'selected' : '' }}>
                                {{ $bidang->nama }}
                            </option>
                            @endforeach
                        </select>
                        @error('bidang_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Pilih anggota (untuk private) --}}
                    <div id="users-field" class="hidden">
                        <label class="block text-gray-700 mb-1 font-semibold">Pilih Anggota Grup (Untuk Grup
                            Private)</label>
                        <select id="user-select" name="pengguna_id[]" multiple placeholder="Cari dan pilih anggota...">
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->decrypted_name ?? $user->name }}
                                ({{ $user->decrypted_email ?? $user->email }})
                            </option>
                            @endforeach
                        </select>
                        <small class="text-gray-500">Ketik untuk mencari, bisa pilih lebih dari satu.</small>
                        @error('pengguna_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- SIDEBAR --}}
            <aside class="w-full lg:w-80 flex flex-col gap-6">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <p class="font-bold text-lg leading-tight">
                        {{ Auth::user()->role->nama_role ?? 'User' }}
                    </p>
                </div>

                <div class="flex gap-3 mt-2">
                    <button type="submit"
                        class="flex-1 px-6 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition text-base">
                        Tambah
                    </button>
                    <a href="{{ url()->previous() }}"
                        class="flex-1 px-6 py-3 rounded-xl bg-[#ad3a2c] hover:bg-[#992b1e] text-white font-semibold shadow transition text-base text-center">
                        Batalkan
                    </a>
                </div>
            </aside>
        </form>
    </div>

    {{-- FOOTER --}}
    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                    class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>

    {{-- SweetAlert2 (latest) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // TomSelect untuk multiple users (pastikan library TomSelect sudah dimuat global)
        if (window.TomSelect) {
            new TomSelect("#user-select", {
                maxItems: null,
                valueField: 'value',
                labelField: 'text',
                searchField: ['text'],
            });
        }

        // Toggle bidang vs users
        const isPrivate = document.getElementById('is_private');
        const bidangField = document.getElementById('bidang-field');
        const usersField = document.getElementById('users-field');

        function toggleFields() {
            if (isPrivate.checked) {
                bidangField.classList.add('hidden');
                usersField.classList.remove('hidden');
            } else {
                bidangField.classList.remove('hidden');
                usersField.classList.add('hidden');
            }
        }
        isPrivate.addEventListener('change', toggleFields);
        toggleFields(); // initial

        // Switch UI animasi
        const dot = document.querySelector('.dot');
        const track = document.querySelector('input#is_private')?.nextElementSibling;

        function styleSwitch() {
            if (isPrivate.checked) {
                dot.style.transform = 'translateX(100%)';
                if (track) track.classList.add('bg-blue-300');
            } else {
                dot.style.transform = 'translateX(0)';
                if (track) track.classList.remove('bg-blue-300');
            }
        }
        isPrivate.addEventListener('change', styleSwitch);
        styleSwitch();

        // SweetAlert submit confirm
        const form = document.getElementById('form-forum-pegawai');
        let allowSubmit = false;
        form.addEventListener('submit', function(e) {
            if (allowSubmit) return;
            e.preventDefault();

            const nama = (form.querySelector('input[name="nama_grup"]')?.value || '').trim();

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                html: `Data forum${nama ? ` "<span class='italic'>${nama}</span>"` : ''} akan disimpan.`,
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl p-8',
                    icon: 'mt-0 mb-3',
                    title: 'mb-1',
                    htmlContainer: 'mb-3',
                    confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-2 rounded-lg mr-0 sm:mr-2 w-full sm:w-auto',
                    cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-2 rounded-lg w-full sm:w-auto',
                    actions: 'flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 w-full',
                },
                buttonsStyling: false,
                focusCancel: true
            }).then(res => {
                if (res.isConfirmed) {
                    allowSubmit = true;
                    if (form.requestSubmit) form.requestSubmit();
                    else form.submit();
                }
            });
        });
    });
    </script>

    {{-- Sedikit styling TomSelect + switch --}}
    <style>
    .ts-control {
        border-radius: .75rem !important;
        border-color: #D1D5DB !important;
        background-color: #F5FAFD !important;
        padding: .65rem .9rem !important;
    }

    .ts-control:focus-within {
        --tw-ring-color: rgb(59 130 246 / .45);
        border-color: #2563EB !important;
        box-shadow: 0 0 0 2px var(--tw-ring-color) !important;
    }
    </style>
</x-app-layout>