@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Edit Dokumen Kasubbidang')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] flex flex-col pb-10">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen Kasubbidang</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <form method="GET" action="{{ route('kasubbidang.manajemendokumen.index') }}"
                        class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama dokumen..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </form>

                    {{-- Profile Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20"
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

        {{-- FORM GRID --}}
        <div class="px-4 md:px-8 grid grid-cols-1 xl:grid-cols-12 gap-8 mt-6">
            <form method="POST" action="{{ route('kasubbidang.manajemendokumen.update', $manajemendokuman->id) }}"
                enctype="multipart/form-data" id="manajemen-dokumen-form"
                class="bg-white rounded-2xl shadow-lg p-8 xl:col-span-8 flex flex-col gap-7">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start min-h-[480px]">
                    {{-- Dokumen Preview/Upload --}}
                    <div class="flex flex-col items-center w-full h-full">
                        <div
                            class="border-2 border-gray-300 border-dashed rounded-2xl w-full min-h-[420px] max-h-[520px] bg-white overflow-auto flex items-center justify-center relative">
                            {{-- Preview Area --}}
                            <div id="file-preview" class="w-full h-full flex items-center justify-center overflow-auto">
                                @php
                                $ext = strtolower(pathinfo($manajemendokuman->path_dokumen, PATHINFO_EXTENSION));
                                $thumb = $manajemendokuman->thumbnail ? asset('storage/' . $manajemendokuman->thumbnail)
                                : null;
                                $img = $manajemendokuman->path_dokumen ? asset('storage/' .
                                $manajemendokuman->path_dokumen) : null;
                                @endphp

                                @if($manajemendokuman->path_dokumen)
                                @if(in_array($ext, ['jpg','jpeg','png','gif','bmp','webp']))
                                <img src="{{ $img }}" alt="Preview"
                                    class="object-contain w-full max-h-[440px] rounded-lg border" />
                                @elseif($ext == 'pdf')
                                <embed src="{{ $img }}" type="application/pdf"
                                    class="w-full min-h-[380px] max-h-[440px] rounded-lg" />
                                @else
                                <div class="flex flex-col items-center">
                                    <i class="fa fa-file-alt text-4xl text-gray-400"></i>
                                    <span
                                        class="text-xs mt-2 text-gray-700">{{ basename($manajemendokuman->path_dokumen) }}</span>
                                </div>
                                @endif
                                @endif
                            </div>

                            <div id="upload-prompt"
                                class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none"
                                style="display: none;">
                                <i class="fa fa-upload text-4xl text-gray-400 mb-2"></i>
                                <span class="text-gray-500 text-base">Ganti dokumen</span>
                            </div>
                        </div>

                        {{-- Nama File --}}
                        <span id="file-name" class="block text-gray-700 text-sm mt-3 text-center">
                            @if($manajemendokuman->path_dokumen)
                            {{ basename($manajemendokuman->path_dokumen) }}
                            @endif
                        </span>

                        {{-- Tombol Input File --}}
                        <div class="mt-2 w-full flex justify-center">
                            <label
                                class="rounded-xl bg-[#3067a7] hover:bg-[#21518a] transition text-white font-semibold text-base flex items-center justify-center h-12 w-full cursor-pointer select-none relative">
                                <i class="fa fa-upload mr-2"></i> Ganti dokumen
                                <input type="file" name="path_dokumen" id="path_dokumen"
                                    class="absolute w-full h-full opacity-0 top-0 left-0 cursor-pointer"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">
                            </label>
                        </div>
                        @error('path_dokumen')
                        <span class="text-red-500 text-xs mt-2">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Kolom Form --}}
                    <div class="flex flex-col gap-5 w-full">
                        {{-- Judul --}}
                        <div>
                            <label class="block font-semibold text-gray-800 mb-1">Judul</label>
                            <input type="text" name="nama_dokumen"
                                value="{{ old('nama_dokumen', $manajemendokuman->nama_dokumen) }}"
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
                                    {{ old('kategori_dokumen_id', $manajemendokuman->kategori_dokumen_id) == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategoridokumen }} @if($kat->subbidang) — {{ $kat->subbidang->nama }}
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
                            <textarea name="deskripsi" rows="5"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 resize-none"
                                required>{{ old('deskripsi', $manajemendokuman->deskripsi) }}</textarea>
                        </div>

                        {{-- Field Kunci Rahasia --}}
                        <div id="encrypted-key-field" class="hidden">
                            <label class="block font-semibold text-gray-800 mb-1">Kunci Rahasia / Encrypted Key</label>
                            <input type="text" name="encrypted_key"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500"
                                value="{{ old('encrypted_key', $manajemendokuman->encrypted_key) }}">
                        </div>
                    </div>
                </div>
            </form>

            {{-- SIDEBAR AKSI --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8 mt-8 xl:mt-0">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight mb-2">Bidang
                            {{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-center gap-4">
                    {{-- Tambah id agar mudah di-hook --}}
                    <button id="btn-simpan" type="submit" form="manajemen-dokumen-form"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-save"></i><span>Simpan</span>
                    </button>

                    <a id="btn-batal" href="{{ route('kasubbidang.manajemendokumen.index') }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-times"></i><span>Batalkan</span>
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

    {{-- SweetAlert2 CDN (versi terbaru) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const kategoriSelect = document.getElementById('kategoriSelect');
        const keyField = document.getElementById('encrypted-key-field');
        const fileInput = document.getElementById('path_dokumen');
        const fileName = document.getElementById('file-name');
        const filePreview = document.getElementById('file-preview');
        const uploadPrompt = document.getElementById('upload-prompt');
        const allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'jpg', 'jpeg', 'png', 'gif',
            'bmp', 'webp'
        ];

        // Toggle Kunci Rahasia
        const setKeyField = () => {
            const opt = kategoriSelect.options[kategoriSelect.selectedIndex];
            const nama = opt ? opt.getAttribute('data-nama') : null;
            nama === 'rahasia' ? keyField.classList.remove('hidden') : keyField.classList.add('hidden');
        };
        kategoriSelect.addEventListener('change', setKeyField);
        setKeyField();

        // Preview file
        fileInput.addEventListener('change', function() {
            filePreview.innerHTML = '';
            if (!this.files?.length) {
                fileName.textContent = '';
                uploadPrompt?.classList.remove('hidden');
                return;
            }
            const file = this.files[0];
            const ext = file.name.split('.').pop().toLowerCase();
            fileName.textContent = file.name;

            if (!allowed.includes(ext)) {
                filePreview.innerHTML =
                    `<span class="text-red-600 text-xs">File yang diperbolehkan: PDF, DOC(X), XLS(X), PPT(X), TXT, JPG/PNG, dsb.</span>`;
                this.value = '';
                fileName.textContent = '';
                uploadPrompt?.classList.remove('hidden');
                return;
            }
            uploadPrompt?.classList.add('hidden');

            if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(ext)) {
                const url = URL.createObjectURL(file);
                filePreview.innerHTML =
                    `<img src="${url}" alt="Preview" class="object-contain w-full max-h-[440px] rounded-lg border" />`;
            } else if (ext === 'pdf') {
                const url = URL.createObjectURL(file);
                filePreview.innerHTML =
                    `<embed src="${url}" type="application/pdf" class="w-full min-h-[380px] max-h-[440px] rounded-lg" />`;
            } else {
                let icon = '';
                if (['doc', 'docx'].includes(ext)) icon =
                    `<img src="{{ asset('assets/img/icon-word.svg') }}" class="w-12 h-12 inline">`;
                else if (['xls', 'xlsx'].includes(ext)) icon =
                    `<img src="{{ asset('assets/img/icon-excel.svg') }}" class="w-12 h-12 inline">`;
                else if (['ppt', 'pptx'].includes(ext)) icon =
                    `<img src="{{ asset('assets/img/icon-ppt.svg') }}" class="w-12 h-12 inline">`;
                else icon = `<i class="fa fa-file-alt text-4xl text-gray-400"></i>`;
                filePreview.innerHTML =
                    `<div class="flex flex-col items-center">${icon}<span class="text-xs mt-2 text-gray-700">${file.name}</span></div>`;
            }
        });

        // SweetAlert2: SIMPAN
        const form = document.getElementById('manajemen-dokumen-form');
        const btnSimpan = document.getElementById('btn-simpan');
        btnSimpan.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'success',
                title: 'Apakah Anda Yakin',
                html: '<div class="text-gray-600">perubahan akan disimpan</div>',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-2xl p-8',
                    confirmButton: 'bg-[#32c671] hover:bg-[#259a51] text-white font-semibold px-8 py-2 rounded-lg mx-2',
                    cancelButton: 'bg-[#E02424] hover:bg-[#c81e1e] text-white font-semibold px-8 py-2 rounded-lg mx-2'
                }
            }).then(res => {
                if (res.isConfirmed) form.submit();
            });
        });

        // SweetAlert2: BATALKAN
        const btnBatal = document.getElementById('btn-batal');
        btnBatal.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Apakah Anda Yakin',
                html: '<div class="text-gray-600">perubahan tidak akan disimpan</div>',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Yakin',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-2xl p-8',
                    confirmButton: 'bg-[#3971A6] hover:bg-[#295480] text-white font-semibold px-8 py-2 rounded-lg mx-2',
                    cancelButton: 'bg-[#3971A6] hover:bg-[#295480] text-white font-semibold px-8 py-2 rounded-lg mx-2'
                }
            }).then(res => {
                if (res.isConfirmed) window.location.href = btnBatal.getAttribute('href');
            });
        });
    });
    </script>
</x-app-layout>