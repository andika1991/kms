@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
$allowedExtensions = ['pdf','doc','docx','xls','xlsx','ppt','pptx','txt'];
@endphp

@section('title', 'Edit Dokumen Sekretaris')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] flex flex-col pb-10">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Dokumen</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
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

        {{-- FORM GRID --}}
        <div class="px-4 md:px-8 grid grid-cols-1 xl:grid-cols-12 gap-8 mt-6">
            <form method="POST" action="{{ route('sekretaris.manajemendokumen.update', $manajemendokuman->id) }}"
                enctype="multipart/form-data" id="manajemen-dokumen-form"
                class="bg-white rounded-2xl shadow-lg p-8 xl:col-span-8 flex flex-col gap-7">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start min-h-[480px]">
                    {{-- Dokumen Preview/Upload --}}
                    <div class="flex flex-col items-center justify-start w-full">
                        @php
                        $ext = strtolower(pathinfo($manajemendokuman->path_dokumen, PATHINFO_EXTENSION));
                        $currentFile = $manajemendokuman->path_dokumen
                        ? asset('storage/'.$manajemendokuman->path_dokumen)
                        : null;
                        @endphp
                        <div class="w-full">
                            {{-- Area preview --}}
                            <div id="edit-preview-box"
                                class="border-2 border-gray-300 border-dashed rounded-2xl min-h-[350px] h-[350px] md:h-[420px] flex flex-col justify-center items-center w-full bg-white overflow-hidden relative transition-all">

                                {{-- Static preview (file lama) --}}
                                <div id="static-preview"
                                    class="w-full h-full flex flex-col justify-center items-center bg-white transition-all {{ old('path_dokumen') ? 'hidden' : '' }}">
                                    @if($manajemendokuman->path_dokumen)
                                    @if($ext === 'pdf')
                                    <div class="w-full h-full overflow-auto">
                                        <embed src="{{ $currentFile }}" type="application/pdf" width="100%"
                                            height="100%" class="rounded" />
                                    </div>
                                    @elseif(in_array($ext, ['doc','docx']))
                                    <img src="{{ asset('assets/img/icon-word.svg') }}" class="w-20 h-20 my-7" />
                                    @elseif(in_array($ext, ['xls','xlsx']))
                                    <img src="{{ asset('assets/img/icon-excel.svg') }}" class="w-20 h-20 my-7" />
                                    @elseif(in_array($ext, ['ppt','pptx']))
                                    <img src="{{ asset('assets/img/icon-ppt.svg') }}" class="w-20 h-20 my-7" />
                                    @elseif($ext === 'txt')
                                    <i class="fa fa-file-alt text-6xl text-gray-300 my-7"></i>
                                    @else
                                    <img src="{{ asset('assets/img/default-file.svg') }}" class="w-20 h-20 my-7" />
                                    @endif
                                    @else
                                    <i class="fa fa-upload text-4xl text-gray-400 mb-2"></i>
                                    <span class="text-gray-500 text-base">Tidak ada file dokumen</span>
                                    @endif
                                </div>
                                {{-- Preview file baru dari JS --}}
                                <div id="file-preview" class="absolute top-0 left-0 w-full h-full z-20"></div>
                            </div>

                            <p class="text-xs text-gray-600 mt-2 truncate text-center" id="current-file-name">
                                {{ $manajemendokuman->path_dokumen ? basename($manajemendokuman->path_dokumen) : '' }}
                            </p>

                            {{-- Tombol Ganti Dokumen --}}
                            <div class="block mt-2 w-full">
                                <label
                                    class="rounded-xl bg-[#3067a7] hover:bg-[#21518a] transition text-white font-semibold text-base flex items-center justify-center h-12 w-full cursor-pointer select-none relative">
                                    <i class="fa fa-upload mr-2"></i> Ganti dokumen
                                    <input type="file" name="path_dokumen" id="path_dokumen"
                                        class="absolute w-full h-full opacity-0 top-0 left-0 cursor-pointer"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">
                                    <span id="file-name" class="hidden"></span>
                                </label>
                            </div>
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
                            <textarea name="deskripsi" rows="6"
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
                        <p class="font-bold text-lg leading-tight mb-2">
                            {{ Auth::user()->role->nama_role ?? 'Sekretaris' }}</p>
                        <p class="text-xs">Edit atau perbarui dokumen kegiatan, inovasi, dan knowledge sharing di sini.
                        </p>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <button id="btn-update-dokumen" type="button"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-save"></i>
                        <span>Update</span>
                    </button>
                    <a href="{{ route('sekretaris.manajemendokumen.index') }}" id="btn-cancel-dokumen"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-times"></i>
                        <span>Batalkan</span>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const kategoriSelect = document.getElementById('kategoriSelect');
        const keyField = document.getElementById('encrypted-key-field');
        const fileInput = document.getElementById('path_dokumen');
        const fileName = document.getElementById('file-name');
        const filePreview = document.getElementById('file-preview');
        const staticPreview = document.getElementById('static-preview');
        const currentFileName = document.getElementById('current-file-name');
        const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];

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

        // File input preview (untuk ganti dokumen)
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                filePreview.innerHTML = '';
                if (this.files && this.files.length > 0) {
                    // Hide static preview
                    if (staticPreview) staticPreview.classList.add('hidden');
                    let file = this.files[0];
                    let ext = file.name.split('.').pop().toLowerCase();
                    if (!allowedExtensions.includes(ext)) {
                        filePreview.innerHTML =
                            `<span class="text-red-600 text-xs">File yang diperbolehkan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT</span>`;
                        this.value = '';
                        if (fileName) fileName.textContent = '';
                        if (currentFileName) currentFileName.textContent = '';
                        return;
                    }
                    if (fileName) fileName.textContent = file.name;
                    if (currentFileName) currentFileName.textContent = file.name;

                    // Preview
                    if (ext === 'pdf') {
                        let url = URL.createObjectURL(file);
                        filePreview.innerHTML =
                            `<div class="w-full h-full overflow-auto">
                            <embed src="${url}" type="application/pdf" width="100%" height="100%" class="rounded"/>
                        </div>`;
                    } else if (['doc', 'docx'].includes(ext)) {
                        filePreview.innerHTML =
                            `<img src="{{ asset('assets/img/icon-word.svg') }}" class="w-20 h-20 my-7" />`;
                    } else if (['xls', 'xlsx'].includes(ext)) {
                        filePreview.innerHTML =
                            `<img src="{{ asset('assets/img/icon-excel.svg') }}" class="w-20 h-20 my-7" />`;
                    } else if (['ppt', 'pptx'].includes(ext)) {
                        filePreview.innerHTML =
                            `<img src="{{ asset('assets/img/icon-ppt.svg') }}" class="w-20 h-20 my-7" />`;
                    } else if (ext === 'txt') {
                        filePreview.innerHTML =
                            `<i class="fa fa-file-alt text-6xl text-gray-300 my-7"></i>`;
                    } else {
                        filePreview.innerHTML =
                            `<img src="{{ asset('assets/img/default-file.svg') }}" class="w-20 h-20 my-7" />`;
                    }
                } else {
                    // Show static preview if no new file
                    if (staticPreview) staticPreview.classList.remove('hidden');
                    if (fileName) fileName.textContent = '';
                }
            });
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script>
    document.getElementById('btn-update-dokumen').addEventListener('click', function(e) {
        Swal.fire({
            icon: 'warning',
            title: 'Apakah Anda Yakin',
            html: '<span class="text-gray-600 text-base">perubahan akan disimpan</span>',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-2xl px-8',
                icon: 'mt-5 mb-3',
                title: 'mb-1',
                htmlContainer: 'mb-3',
                confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-10 py-2 rounded-lg text-base mr-2',
                cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-10 py-2 rounded-lg text-base',
                actions: 'flex justify-center gap-4',
            },
            buttonsStyling: false,
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('manajemen-dokumen-form').submit();
            }
        });
    });
    document.getElementById('btn-cancel-dokumen').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Apakah Anda Yakin',
            html: '<span class="text-gray-600 text-base">perubahan tidak akan disimpan</span>',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            reverseButtons: true,
            focusCancel: true,
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-2xl px-8',
                icon: 'mt-5 mb-3 flex justify-center',
                title: 'mb-1 text-2xl font-semibold text-gray-700',
                htmlContainer: 'mb-3',
                confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-10 py-2 rounded-lg text-base mr-2',
                cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-10 py-2 rounded-lg text-base',
                actions: 'flex justify-center gap-4',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href =
                    "{{ route('sekretaris.manajemendokumen.index') }}";
            }
        });
    });
    </script>
</x-app-layout>