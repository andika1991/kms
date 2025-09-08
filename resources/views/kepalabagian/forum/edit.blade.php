@php
    use Carbon\Carbon;
    $carbon = Carbon::now()->locale('id');
    $carbon->settings(['formatFunction' => 'translatedFormat']);
    $tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Edit Forum Diskusi Kepala Bagian')

<x-app-layout>
    <div class="bg-[#eaf5ff] min-h-screen w-full">

        {{-- HEADER --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Forum Diskusi</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <label class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari Forum..."
                               class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </label>

                    {{-- Profile dropdown --}}
                    <div x-data="{open:false}" class="relative">
                        <button type="button" @click="open=!open" @keydown.escape.window="open=false"
                                class="w-10 h-10 grid place-items-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                                title="Profile" aria-haspopup="true" :aria-expanded="open">
                            <i class="fa-solid fa-user"></i>
                        </button>

                        <nav x-cloak x-show="open" @click.outside="open=false"
                             x-transition.opacity.scale.origin.top.right
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-50">
                            <a href="{{ route('profile.edit') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                            </form>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        {{-- BODY --}}
        <main class="p-6 md:p-8 flex flex-col lg:flex-row gap-8 max-w-7xl mx-auto">

            {{-- FORM EDIT --}}
            <form id="edit-forum-kabid"
                  method="POST"
                  action="{{ route('kepalabagian.forum.update', $grupchat->id) }}"
                  class="flex-1 max-w-3xl bg-white rounded-2xl shadow-xl p-6 md:p-10 space-y-6"
                  autocomplete="off">
                @csrf
                @method('PUT')

                <section>
                    <h3 class="font-bold text-lg md:text-xl text-[#222]">Informasi Forum Diskusi</h3>
                    <p class="text-gray-500 text-sm mb-5">Perbarui informasi forum di bawah ini.</p>

                    <label for="nama_grup" class="block text-gray-700 font-semibold mb-2">Nama Forum</label>
                    <input id="nama_grup" name="nama_grup" type="text" required
                           value="{{ old('nama_grup', $grupchat->nama_grup) }}"
                           class="w-full rounded-xl border border-gray-300 px-5 py-3 bg-white shadow focus:outline-none focus:ring-2 focus:ring-blue-500 text-base font-semibold">
                    @error('nama_grup') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

                    <label for="deskripsi" class="block text-gray-700 font-semibold mt-4 mb-2">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"
                              class="w-full rounded-xl border border-gray-300 px-5 py-3 bg-white shadow focus:outline-none focus:ring-2 focus:ring-blue-500 text-base">{{ old('deskripsi', $grupchat->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

                    <label for="grup_role" class="block text-gray-700 font-semibold mt-4 mb-2">Role (Opsional)</label>
                    <input id="grup_role" name="grup_role" type="text"
                           value="{{ old('grup_role', $grupchat->grup_role) }}"
                           class="w-full rounded-xl border border-gray-300 px-5 py-3 bg-white shadow focus:outline-none focus:ring-2 focus:ring-blue-500 text-base">
                    @error('grup_role') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </section>

                <label class="flex items-center gap-2 bg-white rounded-xl px-5 py-2 shadow">
                    <input id="is_private" name="is_private" value="1" type="checkbox"
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                           {{ old('is_private', $grupchat->is_private) ? 'checked' : '' }}>
                    <span class="text-gray-800 font-semibold">Private Forum</span>
                </label>

                {{-- Anggota (Private) --}}
                <section id="users-field"
                         class="bg-white rounded-xl px-5 py-3 shadow transition-all @if(!old('is_private', $grupchat->is_private)) hidden @endif">
                    <label class="block text-gray-700 font-semibold mb-2">Pilih Anggota Grup</label>
                    <select id="user-select" name="pengguna_id[]" multiple
                            class="w-full rounded-xl border border-gray-300 px-3 py-2 bg-white text-base focus:outline-none focus:ring-2 focus:ring-blue-500 shadow"
                            placeholder="Cari dan pilih anggota...">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id, $anggota_ids) ? 'selected' : '' }}>
                                {{ $user->decrypted_name ?? $user->name }} ({{ $user->decrypted_email ?? $user->email }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-gray-500">Ketik untuk mencari dan bisa pilih lebih dari satu.</small>
                </section>

                {{-- Bidang (Umum) --}}
                <section id="bidang-field"
                         class="bg-white rounded-xl px-5 py-3 shadow transition-all @if(old('is_private', $grupchat->is_private)) hidden @endif">
                    <label class="block text-gray-700 font-semibold mb-2">Bidang (Untuk Grup Umum)</label>
                    <select name="bidang_id"
                            class="w-full rounded-xl border border-gray-300 px-3 py-2 bg-white text-base focus:outline-none focus:ring-2 focus:ring-blue-500 shadow">
                        <option value="">-- Pilih Bidang --</option>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}" {{ $grupchat->bidang_id == $bidang->id ? 'selected' : '' }}>
                                {{ $bidang->nama ?? $bidang->nama_bidang }}
                            </option>
                        @endforeach
                    </select>
                </section>
            </form>

            {{-- SIDEBAR --}}
            <aside class="w-full lg:w-80 flex flex-col gap-6">
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 grid place-items-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'Kepala Bagian' }}</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" form="edit-forum-kabid"
                            class="flex-1 px-6 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition text-base">
                        Simpan
                    </button>
                    <a href="{{ route('kepalabagian.forum.index') }}" id="btn-batal"
                       class="flex-1 px-6 py-3 rounded-xl bg-[#d32f2f] hover:bg-[#b71c1c] text-white font-semibold shadow transition text-base text-center">
                        Batalkan
                    </a>
                </div>
            </aside>
        </main>
    </div>

    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik" class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>

    {{-- SweetAlert2 (terbaru) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.23.0/dist/sweetalert2.all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // TomSelect aman-aman saja kalau belum di-load
            if (window.TomSelect) {
                new TomSelect('#user-select', {
                    maxItems: null, valueField: 'value', labelField: 'text', searchField: ['text']
                });
            }

            // Toggle bidang vs users
            const isPrivate = document.getElementById('is_private');
            const bidangField = document.getElementById('bidang-field');
            const usersField  = document.getElementById('users-field');
            const toggleFields = () => {
                if (isPrivate.checked) { bidangField.classList.add('hidden'); usersField.classList.remove('hidden'); }
                else { bidangField.classList.remove('hidden'); usersField.classList.add('hidden'); }
            };
            isPrivate.addEventListener('change', toggleFields); toggleFields();

            // Modal SIMPAN (ikon centang hijau) — konfirmasi sebelum submit
            const form = document.getElementById('edit-forum-kabid');
            let allowSubmit = false;
            form.addEventListener('submit', (e) => {
                if (allowSubmit) return;
                e.preventDefault();
                const nama = (document.getElementById('nama_grup')?.value || '').trim();

                Swal.fire({
                    icon: 'success',
                    title: 'Apakah Anda Yakin',
                    html: 'perubahan akan disimpan',
                    showCancelButton: true,
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-2xl p-8',
                        icon: 'mt-0 mb-3',
                        title: 'mb-1 text-xl font-bold',
                        htmlContainer: 'mb-4',
                        actions: 'flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 w-full',
                        confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-10 py-2 rounded-lg w-full sm:w-auto',
                        cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-10 py-2 rounded-lg w-full sm:w-auto',
                    },
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                }).then(res => {
                    if (res.isConfirmed) {
                        allowSubmit = true;
                        form.requestSubmit ? form.requestSubmit() : form.submit();
                    }
                });
            });

            // Modal BATAL (ikon warning kuning) — sebelum kembali
            const btnBatal = document.getElementById('btn-batal');
            btnBatal.addEventListener('click', (e) => {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Apakah Anda Yakin',
                    html: 'perubahan tidak akan disimpan',
                    showCancelButton: true,
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-2xl p-8',
                        icon: 'mt-0 mb-3',
                        title: 'mb-1 text-xl font-bold',
                        htmlContainer: 'mb-4',
                        actions: 'flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 w-full',
                        confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-10 py-2 rounded-lg w-full sm:w-auto',
                        cancelButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-10 py-2 rounded-lg w-full sm:w-auto',
                    },
                    confirmButtonText: 'Yakin',
                    cancelButtonText: 'Batal',
                }).then(res => {
                    if (res.isConfirmed) window.location.href = btnBatal.getAttribute('href');
                });
            });
        });
    </script>

    {{-- Sedikit pemoles TomSelect agar konsisten --}}
    <style>
        .ts-control{border-radius:.75rem!important;border-color:#D1D5DB!important;background:#fff!important;padding:.5rem .75rem!important}
        .ts-control:focus-within{--tw-ring-color:rgb(59 130 246 / .5);border-color:#2563EB!important;box-shadow:0 0 0 4px var(--tw-ring-color)!important}
        [x-cloak]{display:none!important}
    </style>
</x-app-layout>
