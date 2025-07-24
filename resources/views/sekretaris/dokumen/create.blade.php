@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Tambah Dokumen Sekretaris')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] flex flex-col pb-10">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
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
            <form method="POST" action="{{ route('sekretaris.manajemendokumen.store') }}" enctype="multipart/form-data"
                id="form-tambah-dokumen"
                class="bg-white rounded-2xl shadow-lg p-8 xl:col-span-8 flex flex-col gap-7">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start min-h-[480px]">
                    {{-- Preview Dokumen --}}
                    <div class="flex flex-col items-center w-full h-full">
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
                        {{-- Tombol Input File --}}
                        <div class="mt-2 w-full flex justify-center">
                            <label
                                class="rounded-xl bg-[#3067a7] hover:bg-[#21518a] transition text-white font-semibold text-base flex items-center justify-center h-12 w-full cursor-pointer select-none relative">
                                <i class="fa fa-upload mr-2"></i> Pilih Dokumen
                                <input type="file" name="path_dokumen" id="path_dokumen"
                                    class="absolute w-full h-full opacity-0 top-0 left-0 cursor-pointer"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" required>
                            </label>
                        </div>
                        @error('path_dokumen')
                        <span class="text-red-500 text-xs mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- Kolom Form --}}
                    <div class="flex flex-col gap-5 w-full">
                        <div>
                            <label class="block font-semibold text-gray-800 mb-1">Judul</label>
                            <input type="text" name="nama_dokumen" value="{{ old('nama_dokumen') }}"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>
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
                        <div>
                            <label class="block font-semibold text-gray-800 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" rows="5"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 resize-none"
                                required>{{ old('deskripsi') }}</textarea>
                        </div>
                        <div id="encrypted-key-field" class="hidden">
                            <label class="block font-semibold text-gray-800 mb-1">Kunci Rahasia / Encrypted Key</label>
                            <input type="text" name="encrypted_key"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500">
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
                        <p class="text-xs">Upload, simpan, dan kelola dokumen kegiatan, inovasi, dan knowledge sharing
                            di sini.</p>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <button id="btn-create-dokumen" type="button" class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-save"></i>
                        <span>Simpan</span>
                    </button>
                    <a href="{{ route('sekretaris.manajemendokumen.index') }}" class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-times"></i>
                        <span>Batalkan</span>
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
        const kategoriSelect = document.getElementById('kategoriSelect');
        const keyField = document.getElementById('encrypted-key-field');
        const fileInput = document.getElementById('path_dokumen');
        const fileName = document.getElementById('file-name');
        const filePreview = document.getElementById('file-preview');
        const uploadPrompt = document.getElementById('upload-prompt');
        const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];

        // Toggle Kunci Rahasia Field
        kategoriSelect.addEventListener('change', function() {
            const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
            const namaKategori = selectedOption ? selectedOption.getAttribute('data-nama') : null;
            if (namaKategori === 'rahasia') {
                keyField.classList.remove('hidden');
            } else {
                keyField.classList.add('hidden');
            }
        });

        // File input preview
        fileInput.addEventListener('change', function() {
            filePreview.innerHTML = '';
            if (this.files && this.files.length > 0) {
                let file = this.files[0];
                fileName.textContent = file.name;
                let ext = file.name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(ext)) {
                    filePreview.innerHTML =
                        `<span class="text-red-600 text-xs">File yang diperbolehkan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT</span>`;
                    this.value = '';
                    fileName.textContent = '';
                    uploadPrompt?.classList.remove('hidden');
                    return;
                }
                uploadPrompt?.classList.add('hidden');

                if (ext === 'pdf') {
                    let url = URL.createObjectURL(file);
                    filePreview.innerHTML =
                        `<div class="w-full h-full overflow-auto">
                        <embed src="${url}" type="application/pdf"
                            class="w-full h-[480px] rounded pointer-events-auto select-text"
                            style="max-height:480px;" />
                    </div>`;
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
            } else {
                fileName.textContent = '';
                uploadPrompt?.classList.remove('hidden');
            }
        });
    });
    </script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script>
        document.getElementById('btn-create-dokumen').addEventListener('click', function (e) {
            // Modal konfirmasi mirip Figma
            Swal.fire({
                title: 'Apakah Anda Yakin',
                html: '<span class="font-semibold">perubahan akan disimpan</span>',
                icon: 'success',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl p-8',
                    icon: 'mt-0 mb-3',
                    title: 'mb-1',
                    htmlContainer: 'mb-3',
                    confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-10 py-2 rounded-lg text-base mr-2',
                    cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-10 py-2 rounded-lg text-base',
                    actions: 'flex justify-center gap-4',
                },
                buttonsStyling: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-tambah-dokumen').submit();
                }
            });
        });
    </script>

</x-app-layout>