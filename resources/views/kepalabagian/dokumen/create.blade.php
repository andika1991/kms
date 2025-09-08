@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Tambah Dokumen Kepala Bagian')

<x-app-layout>
    <div class="min-h-screen bg-[#eaf5ff] flex flex-col pb-10">

        {{-- HEADER --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    {{-- Search --}}
                    <form method="GET" action="{{ route('kepalabagian.manajemendokumen.index') }}"
                        class="w-full md:w-72">
                        <div class="relative">
                            <input name="search" value="{{ request('search') }}" placeholder="Cari nama dokumen..."
                                class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm
                        focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </form>

                    {{-- Profile dropdown --}}
                    <div x-data="{open:false}" class="relative">
                        <button type="button" @click="open=!open" class="w-10 h-10 grid place-items-center bg-white rounded-full border border-gray-300
                 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <nav x-show="open" @click.away="open=false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20 hidden">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">@csrf
                                <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
                                    Out</button>
                            </form>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        {{-- GRID KONTEN --}}
        <main class="px-4 md:px-8 grid grid-cols-1 xl:grid-cols-12 gap-8 mt-6">

            {{-- FORM (tetap route & field aslinya) --}}
            <form id="form-tambah-dokumen-kb" method="POST" action="{{ route('kepalabagian.manajemendokumen.store') }}"
                enctype="multipart/form-data"
                class="bg-white rounded-2xl shadow-lg p-8 xl:col-span-8 flex flex-col gap-7">
                @csrf

                @if($errors->any())
                <div class="px-4 py-3 rounded-lg bg-red-50 text-red-700 border border-red-200 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <section class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

                    {{-- DROPZONE / PREVIEW --}}
                    <section class="flex flex-col items-center w-full">
                        <div
                            class="border-2 border-gray-300 border-dashed rounded-2xl w-full min-h-[420px] max-h-[520px] bg-white overflow-auto flex items-center justify-center relative">
                            <div id="file-preview" class="w-full h-full flex items-center justify-center overflow-auto">
                            </div>
                            <div id="upload-prompt"
                                class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <i class="fa fa-upload text-4xl text-gray-400 mb-2"></i>
                                <span class="text-gray-500 text-base">Tambah dokumen</span>
                            </div>
                        </div>

                        <span id="file-name" class="block text-gray-700 text-sm mt-3 text-center"></span>

                        <label
                            class="mt-2 rounded-xl bg-[#3067a7] hover:bg-[#21518a] transition text-white font-semibold text-base flex items-center justify-center h-12 w-full cursor-pointer select-none relative">
                            <i class="fa fa-upload mr-2"></i> Pilih Dokumen
                            <input id="path_dokumen" name="path_dokumen" type="file"
                                class="absolute inset-0 opacity-0 cursor-pointer"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" required>
                        </label>
                        @error('path_dokumen')
                        <span class="text-red-500 text-xs mt-2">{{ $message }}</span>
                        @enderror
                    </section>

                    {{-- KOLOM INPUT --}}
                    <section class="flex flex-col gap-5 w-full">
                        <label class="block">
                            <span class="font-semibold text-gray-800">Judul</span>
                            <input name="nama_dokumen" value="{{ old('nama_dokumen') }}"
                                class="mt-1 w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500"
                                required>
                        </label>

                        <label class="block">
                            <span class="font-semibold text-gray-800">Kategori</span>
                            <select id="kategoriSelect" name="kategori_dokumen_id"
                                class="mt-1 w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="">Pilih Kategori</option>
                                @forelse($kategori as $kat)
                                <option value="{{ $kat->id }}" data-nama="{{ strtolower($kat->nama_kategoridokumen) }}"
                                    {{ old('kategori_dokumen_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategoridokumen }} @if($kat->subbidang) — {{ $kat->subbidang->nama }}
                                    @endif
                                </option>
                                @empty
                                <option disabled>Tidak ada kategori tersedia</option>
                                @endforelse
                            </select>
                        </label>

                        <label class="block">
                            <span class="font-semibold text-gray-800">Deskripsi</span>
                            <textarea name="deskripsi" rows="5"
                                class="mt-1 w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 resize-none"
                                required>{{ old('deskripsi') }}</textarea>
                        </label>

                        {{-- Field kunci hanya jika kategori "Rahasia" --}}
                        <label id="encrypted-key-field" class="block hidden">
                            <span class="font-semibold text-gray-800">Kunci Rahasia / Encrypted Key</span>
                            <input name="encrypted_key" placeholder="Masukkan kunci dokumen"
                                class="mt-1 w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500">
                        </label>
                    </section>
                </section>
            </form>

            {{-- SIDEBAR (tanpa kelola kategori) --}}
            <aside class="xl:col-span-4 flex flex-col gap-6">
                <section
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 text-center grid place-items-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <p class="font-bold text-lg leading-tight"> {{ Auth::user()->role->nama_role ?? 'Kepala Bagian' }}
                    </p>
                    <p class="text-xs opacity-90 mt-1">Unggah, simpan, dan tinjau dokumen kerja di sini.</p>
                </section>

                <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button id="btn-simpan" type="button"
                        class="px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm">
                        Tambah
                    </button>
                    <button id="btn-batal" type="button"
                        class="px-5 py-2.5 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm">
                        Batalkan
                    </button>
                </section>
            </aside>
        </main>

        {{-- FOOTER --}}
        <x-slot name="footer">
            <footer class="bg-[#2b6cb0] py-4 mt-8">
                <div class="max-w-7xl mx-auto px-4 grid place-items-center">
                    <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                        class="h-10 object-contain">
                </div>
            </footer>
        </x-slot>
    </div>

    {{-- SweetAlert2 (terbaru) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.5/dist/sweetalert2.all.min.js"></script>

    {{-- Preview file + toggle field Rahasia + aksi tombol --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const kategoriSelect = document.getElementById('kategoriSelect');
        const keyField = document.getElementById('encrypted-key-field');
        const fileInput = document.getElementById('path_dokumen');
        const fileName = document.getElementById('file-name');
        const filePreview = document.getElementById('file-preview');
        const uploadPrompt = document.getElementById('upload-prompt');
        const allowedExt = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];

        function toggleKey() {
            const opt = kategoriSelect.options[kategoriSelect.selectedIndex];
            const nama = opt ? (opt.getAttribute('data-nama') || '').toLowerCase() : '';
            nama === 'rahasia' ? keyField.classList.remove('hidden') : keyField.classList.add('hidden');
        }
        kategoriSelect.addEventListener('change', toggleKey);
        toggleKey();

        fileInput.addEventListener('change', function() {
            filePreview.innerHTML = '';
            if (!this.files || this.files.length === 0) {
                fileName.textContent = '';
                uploadPrompt?.classList.remove('hidden');
                return;
            }

            const f = this.files[0];
            const ext = (f.name.split('.').pop() || '').toLowerCase();
            fileName.textContent = f.name;

            if (!allowedExt.includes(ext)) {
                filePreview.innerHTML =
                    '<span class="text-red-600 text-xs">File yang diperbolehkan: PDF/DOC/XLS/PPT/TXT</span>';
                this.value = '';
                fileName.textContent = '';
                uploadPrompt?.classList.remove('hidden');
                return;
            }

            uploadPrompt?.classList.add('hidden');

            if (ext === 'pdf') {
                const url = URL.createObjectURL(f);
                filePreview.innerHTML =
                    `<div class="w-full h-full overflow-auto">
                        <embed src="${url}" type="application/pdf" class="w-full h-[480px] rounded pointer-events-auto select-text" />
                     </div>`;
            } else {
                let icon = '<i class="fa fa-file-alt text-4xl text-gray-400"></i>';
                filePreview.innerHTML =
                    `<div class="flex flex-col items-center">${icon}<span class="text-xs mt-2 text-gray-700">${f.name}</span></div>`;
            }
        });

        // SweetAlert2: Tambah
        document.getElementById('btn-simpan').addEventListener('click', function() {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                html: '<span class="font-semibold">Perubahan akan disimpan.</span>',
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl p-8',
                    confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-10 py-2 rounded-lg text-base mr-2',
                    cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-10 py-2 rounded-lg text-base',
                    actions: 'flex justify-center gap-4',
                },
                buttonsStyling: false
            }).then(r => {
                if (r.isConfirmed) document.getElementById('form-tambah-dokumen-kb').submit();
            });
        });

        // SweetAlert2: Batalkan
        document.getElementById('btn-batal').addEventListener('click', function() {
            Swal.fire({
                title: 'Batalkan perubahan?',
                text: 'Form akan ditutup dan data tidak disimpan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, batalkan',
                cancelButtonText: 'Kembali',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl p-8',
                    confirmButton: 'bg-red-700 hover:bg-red-800 text-white font-semibold px-8 py-2 rounded-lg mr-2',
                    cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-8 py-2 rounded-lg'
                },
                buttonsStyling: false
            }).then(r => {
                if (r.isConfirmed) window.location.href =
                    "{{ route('kepalabagian.manajemendokumen.index') }}";
            });
        });
    });
    </script>
</x-app-layout>