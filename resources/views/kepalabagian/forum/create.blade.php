@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Tambah Forum Kepala Bagian')

<x-app-layout>
    {{-- Wrapper utama untuk semua konten di kanan sidebar --}}
    <div class="w-full bg-[#eaf5ff]">

        {{-- HEADER KONTEN --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Forum Diskusi</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari Forum..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i
                                class="fa fa-search"></i></span>
                    </div>
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
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
                                        Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BODY KONTEN GRID --}}
        <form id="form-forum-kb" method="POST" action="{{ route('kepalabagian.forum.store') }}"
            class="p-6 md:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            {{-- KOLOM KIRI (FORM INPUT) --}}
            <div class="lg:col-span-2 flex flex-col gap-8">

                {{-- Card Informasi Forum --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/80 p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-1">Informasi Forum Diskusi</h3>
                    <p class="text-sm text-gray-500 mb-6">Tambahkan informasi tentang forum.</p>
                    <div class="space-y-6">
                        <div>
                            <label for="nama_grup" class="block font-semibold text-gray-700 mb-1">Nama Forum</label>
                            <input type="text" id="nama_grup" name="nama_grup" value="{{ old('nama_grup') }}" required
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Masukkan Nama Forum">
                            @error('nama_grup') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="deskripsi" class="block font-semibold text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Masukkan Deskripsi singkat">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Card Pengaturan Grup --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/80 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-lg text-gray-800">Private Forum</h3>
                            <p class="text-sm text-gray-500">Aktifkan jika forum ini hanya untuk anggota tertentu.</p>
                        </div>
                        <label for="is_private" class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" id="is_private" name="is_private" value="1" class="sr-only"
                                    {{ old('is_private') ? 'checked' : '' }}>
                                <div class="block bg-gray-200 w-14 h-8 rounded-full"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                            </div>
                        </label>
                    </div>
                    <div class="mt-6 space-y-6">
                        <div id="bidang-field" class="hidden">
                            <label for="bidang_id" class="block font-semibold text-gray-700 mb-1">Bidang (Untuk Grup
                                Umum)</label>
                            <select name="bidang_id" id="bidang_id"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Bidang --</option>
                                {{-- PERBAIKAN: Loop untuk menampilkan data bidang dikembalikan --}}
                                @foreach($bidangs as $bidang)
                                <option value="{{ $bidang->id }}">{{ $bidang->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="users-field" class="hidden">
                            <label class="block font-semibold text-gray-700 mb-1">Pilih Anggota Grup (Untuk Grup
                                Private)</label>
                            <select id="user-select" name="pengguna_id[]" multiple
                                placeholder="Cari dan pilih anggota...">
                                {{-- PERBAIKAN: Loop untuk menampilkan data user dikembalikan --}}
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (SIDEBAR AKSI) --}}
            <aside class="lg:col-span-1 w-full flex flex-col gap-8">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">

                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-18 w-18 mb-4">

                    <div>
                        <p class="text-base opacity-90">Role anda sebagai</p>
                        <p class="font-bold text-xl leading-tight mt-1">{{ Auth::user()->role->nama_role ?? 'User' }}
                        </p>
                    </div>
                </div>
                <div class="flex gap-3 mt-2">
                    <button type="submit"
                        class="flex-1 px-6 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition text-base">
                        Tambah
                    </button>

                    <a href="{{ route('kepalabagian.forum.index') }}"
                        class="btn-batal flex-1 px-6 py-3 rounded-xl bg-[#ad3a2c] hover:bg-[#992b1e] text-white font-semibold shadow transition text-base text-center">
                        Batalkan
                    </a>
                </div>
            </aside>
        </form>
    </div>

    {{-- Script untuk TomSelect dan Toggle --}}
    @push('scripts')

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi TomSelect
        new TomSelect("#user-select", {
            maxItems: null,
            valueField: 'value',
            labelField: 'text',
            searchField: ['text'],
        });

        // Logika untuk Toggle Private/Umum
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
        // Jalankan saat halaman dimuat untuk memeriksa kondisi awal
        toggleFields();

    });
    </script>

    {{-- SweetAlert2 (latest) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.23.0/dist/sweetalert2.all.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('form-forum-kb');
        const btnBatal = document.querySelector('.btn-batal');

        // ---- Modal SIMPAN (ikon check - tombol Ya / Tidak) ----
        let allowSubmit = false;
        form.addEventListener('submit', (e) => {
            if (allowSubmit) return; // biarkan submit kedua
            e.preventDefault();

            const nama = (form.querySelector('#nama_grup')?.value || '').trim();

            Swal.fire({
                icon: 'success',
                title: 'Apakah Anda Yakin',
                html: 'perubahan akan disimpan' + (nama ? `<br><b>${nama}</b>` : ''),
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-2xl p-8',
                    title: 'mb-2',
                    icon: 'mb-2',
                    actions: 'flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 w-full',
                    confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-2 rounded-lg w-full sm:w-auto',
                    cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-2 rounded-lg w-full sm:w-auto',
                }
            }).then((r) => {
                if (r.isConfirmed) {
                    allowSubmit = true;
                    form.requestSubmit ? form.requestSubmit() : form.submit();
                }
            });
        });

        // ---- Modal BATAL (ikon warning - tombol Batal / Yakin) ----
        if (btnBatal) {
            btnBatal.addEventListener('click', (e) => {
                e.preventDefault();
                const target = e.currentTarget.getAttribute('href');

                Swal.fire({
                    icon: 'warning',
                    title: 'Apakah Anda Yakin',
                    html: 'perubahan tidak akan disimpan',
                    showCancelButton: true,
                    // urutan & label sesuai Figma
                    confirmButtonText: 'Yakin',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-2xl p-8',
                        title: 'mb-2',
                        icon: 'mb-2',
                        actions: 'flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 w-full',
                        // keduanya bernuansa biru sesuai mockup
                        confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2 rounded-lg w-full sm:w-auto',
                        cancelButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2 rounded-lg w-full sm:w-auto',
                    }
                }).then((r) => {
                    if (r.isConfirmed) window.location.href = target;
                });
            });
        }
    });
    </script>

    <style>
    /* Styling untuk TomSelect agar sesuai dengan tema */
    .ts-control {
        border-radius: 0.5rem !important;
        border-color: #D1D5DB !important;
        background-color: #F9FAFB !important;
        padding: 0.5rem 0.75rem !important;
    }

    .ts-control:focus-within {
        --tw-ring-color: rgb(59 130 246 / 0.5);
        border-color: #2563EB !important;
        box-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color) !important;
    }

    /* Styling untuk checkbox toggle */
    input:checked~.dot {
        transform: translateX(100%);
        background-color: #2563EB;
    }

    input:checked~.block {
        background-color: #93C5FD;
    }
    </style>
    @endpush
</x-app-layout>