@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Tambah Forum Magang')

<x-app-layout>
    <div class="min-h-screen w-full bg-[#eaf5ff]">
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Forum Diskusi</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari Forum..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>

                    {{-- Profile (drop simple) --}}
                    <div x-data="{ open:false }" class="relative">
                        <button @click="open=!open"
                            class="w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open=false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20"
                            style="display:none;">
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
        </header>

        {{-- FORM + SIDEBAR --}}
        <form id="form-forum-magang" method="POST" action="{{ route('magang.forum.store') }}"
            class="px-4 md:px-8 pt-8 pb-10 grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
            @csrf

            {{-- FORM KIRI (2 kolom) --}}
            <section class="lg:col-span-2 space-y-6">
                {{-- Informasi Forum --}}
                <article class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                    <header class="mb-6">
                        <h3 class="font-bold text-lg text-gray-900">Informasi Forum Diskusi</h3>
                        <p class="text-sm text-gray-500">Tambahkan informasi tentang forum.</p>
                    </header>

                    <label class="block mb-4">
                        <span class="block text-sm font-semibold text-gray-700 mb-1">Nama Forum</span>
                        <input name="nama_grup" value="{{ old('nama_grup') }}" required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-[#f5fafd] text-base focus:ring-2 focus:ring-blue-500">
                        @error('nama_grup') <small class="text-red-600">{{ $message }}</small> @enderror
                    </label>

                    <label class="block">
                        <span class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</span>
                        <textarea name="deskripsi" rows="4"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-[#f5fafd] text-base focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan deskripsi singkat">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <small class="text-red-600">{{ $message }}</small> @enderror
                    </label>

                    {{-- (Opsional) Role forum agar seragam dengan pegawai --}}
                    <label class="block mt-6">
                        <span class="block text-sm font-semibold text-gray-700 mb-1">Role Forum (Opsional)</span>
                        <input name="grup_role" value="{{ old('grup_role') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-[#f5fafd] text-base focus:ring-2 focus:ring-blue-500"
                            placeholder="Contoh: Magang, Umum, dll">
                        @error('grup_role') <small class="text-red-600">{{ $message }}</small> @enderror
                    </label>
                </article>

                {{-- Private / Umum --}}
                <article class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-lg text-gray-900">Private Forum</h4>
                            <p class="text-sm text-gray-500">Aktifkan jika forum ini hanya untuk anggota tertentu.</p>
                        </div>

                        {{-- Switch --}}
                        <label for="is_private" class="relative inline-flex cursor-pointer select-none items-center">
                            <input id="is_private" name="is_private" type="checkbox" value="1" class="sr-only"
                                {{ old('is_private') ? 'checked' : '' }}>
                            <span
                                class="block w-14 h-8 rounded-full bg-gray-200 transition-colors peer-checked:bg-blue-300"></span>
                            <span
                                class="dot absolute left-1 top-1 w-6 h-6 rounded-full bg-white transition-transform"></span>
                        </label>
                    </div>

                    {{-- Umum: pilih Bidang (SEMUA bidang) --}}
                    <label id="bidang-field" class="block mt-6">
                        <span class="block text-sm font-semibold text-gray-700 mb-1">Bidang (Untuk Grup Umum)</span>
                        <select name="bidang_id"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-[#f5fafd] text-base focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Bidang --</option>
                            @foreach($bidangs as $b)
                            <option value="{{ $b->id }}" {{ old('bidang_id')==$b->id?'selected':'' }}>
                                {{ $b->nama ?? $b->nama_bidang ?? 'Bidang '.$b->id }}
                            </option>
                            @endforeach
                        </select>
                        @error('bidang_id') <small class="text-red-600">{{ $message }}</small> @enderror
                    </label>

                    {{-- Private: pilih anggota (SEMUA user; prioritas bisa search nama/role) --}}
                    <label id="users-field" class="block mt-6 hidden">
                        <span class="block text-sm font-semibold text-gray-700 mb-2">Pilih Anggota Grup (Untuk Grup
                            Private)</span>
                        <select id="user-select" name="pengguna_id[]" multiple placeholder="Cari & pilih anggota...">
                            @foreach($users as $u)
                            <option value="{{ $u->id }}">
                                {{ $u->decrypted_name ?? $u->name }}
                                ({{ $u->decrypted_email ?? $u->email }})
                                @if($u->role) — {{ $u->role->nama_role ?? 'Role' }} @endif
                            </option>
                            @endforeach
                        </select>
                        <small class="text-gray-500">Ketik untuk mencari, bisa pilih lebih dari satu.</small>
                        @error('pengguna_id') <small class="text-red-600">{{ $message }}</small> @enderror
                    </label>
                </article>
            </section>

            {{-- SIDEBAR KANAN --}}
            <aside class="space-y-6">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" class="h-16 w-16 mx-auto mb-4" alt="">
                    <p class="opacity-90 text-sm">Role anda sebagai</p>
                    <p class="font-bold text-lg mt-1">{{ Auth::user()->role->nama_role ?? 'User' }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm">
                        <i class="fa-solid fa-save"></i> Tambah
                    </button>
                    <a href="{{ url()->previous() }}" id="btn-cancel"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm">
                        <i class="fa-solid fa-xmark"></i> Batalkan
                    </a>
                </div>
            </aside>
        </form>

        {{-- FOOTER --}}
        <x-slot name="footer">
            <footer class="bg-[#2b6cb0] py-4 mt-8">
                <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                    <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                        class="h-10 object-contain">
                </div>
            </footer>
        </x-slot>
    </div>

    {{-- SweetAlert2 + interaksi UI --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.4/dist/sweetalert2.all.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // TomSelect (global)
        if (window.TomSelect) {
            new TomSelect('#user-select', {
                maxItems: null,
                valueField: 'value',
                labelField: 'text',
                searchField: ['text']
            });
        }

        const isPrivate = document.getElementById('is_private');
        const bidang = document.getElementById('bidang-field');
        const users = document.getElementById('users-field');
        const dot = document.querySelector('.dot');

        function toggleFields() {
            if (isPrivate.checked) {
                bidang.classList.add('hidden');
                users.classList.remove('hidden');
                dot.style.transform = 'translateX(100%)';
            } else {
                bidang.classList.remove('hidden');
                users.classList.add('hidden');
                dot.style.transform = 'translateX(0)';
            }
        }
        isPrivate.addEventListener('change', toggleFields);
        toggleFields();

        // Confirm submit
        const form = document.getElementById('form-forum-magang');
        let allowSubmit = false;
        form.addEventListener('submit', (e) => {
            if (allowSubmit) return;
            e.preventDefault();
            const nama = (form.querySelector('input[name="nama_grup"]')?.value || '').trim();

            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: `Forum${nama?` <b>"${nama}"</b>`:''} akan disimpan.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-2xl p-8',
                    confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-2 rounded-lg mr-0 sm:mr-2',
                    cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-2 rounded-lg'
                }
            }).then(res => {
                if (res.isConfirmed) {
                    allowSubmit = true;
                    form.submit();
                }
            });
        });

        // Confirm cancel
        document.getElementById('btn-cancel')?.addEventListener('click', (ev) => {
            ev.preventDefault();
            const href = ev.currentTarget.getAttribute('href') || '{{ url()->previous() }}';
            Swal.fire({
                title: 'Batalkan perubahan?',
                text: 'Data yang sudah diisi tidak akan disimpan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Batalkan',
                cancelButtonText: 'Kembali',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    actions: 'flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 mt-2 w-full',
                    confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-2 rounded-lg w-full sm:w-auto',
                    cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-8 py-2 rounded-lg w-full sm:w-auto'
                }
            }).then(r => {
                if (r.isConfirmed) window.location.href = href;
            });
        });
    });
    </script>

    {{-- Sedikit styling TomSelect --}}
    <style>
    .ts-control {
        border-radius: .75rem !important;
        border-color: #D1D5DB !important;
        background: #F5FAFD !important;
        padding: .65rem .9rem !important
    }

    .ts-control:focus-within {
        --tw-ring-color: rgb(59 130 246 / .45);
        border-color: #2563EB !important;
        box-shadow: 0 0 0 2px var(--tw-ring-color) !important
    }
    </style>
</x-app-layout>