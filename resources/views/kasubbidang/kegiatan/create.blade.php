@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Tambah Kegiatan Kasubbidang')

<x-app-layout>
    {{-- HEADER --}}
    <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Tambah Kegiatan Kasubbidang</h2>
                <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="flex flex-col lg:flex-row gap-8 justify-center items-start min-h-[80vh] px-2 py-10 bg-[#eaf5ff]">
        {{-- FORM TAMBAH KEGIATAN --}}
        <div class="w-full max-w-2xl mx-auto">
            <form id="form-kegiatan" method="POST" action="{{ route('kasubbidang.kegiatan.store') }}"
                enctype="multipart/form-data" class="bg-white rounded-xl shadow-xl px-7 py-8 flex flex-col gap-6">
                @csrf
                <input type="hidden" name="subbidang_id" value="{{ $subbidangId ?? '' }}">

                {{-- Upload Foto Kegiatan --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">
                        Foto Kegiatan <span class="text-xs text-gray-400">(opsional, max 5)</span>
                    </label>
                    <label for="foto_kegiatan_input"
                        class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-blue-200 rounded-lg cursor-pointer hover:border-blue-500 bg-blue-50 transition">
                        <i class="fa fa-cloud-upload text-3xl text-blue-400 mb-1"></i>
                        <span class="text-blue-600 text-sm font-medium">Klik atau tarik foto ke sini</span>
                        <input type="file" id="foto_kegiatan_input" name="foto_kegiatan[]" multiple accept="image/*"
                            class="hidden">
                    </label>
                    <div id="preview-foto" class="flex flex-wrap gap-4 mt-2"></div>
                    @error('foto_kegiatan')
                    <span class="text-red-500 text-xs mt-2">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Nama Kegiatan --}}
                <div>
                    <label for="nama_kegiatan" class="block font-semibold text-gray-700 mb-1">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" id="nama_kegiatan" value="{{ old('nama_kegiatan') }}"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-3 py-2"
                        placeholder="Contoh: Sosialisasi Inovasi Kerja" required>
                    @error('nama_kegiatan')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="deskripsi_kegiatan" class="block font-semibold text-gray-700 mb-1">Deskripsi
                        Kegiatan</label>
                    <textarea name="deskripsi_kegiatan" id="deskripsi_kegiatan" rows="3"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-3 py-2"
                        placeholder="Jelaskan kegiatan, misal: sharing knowledge, improvement proses, pelatihan, dll."
                        required>{{ old('deskripsi_kegiatan') }}</textarea>
                    @error('deskripsi_kegiatan')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Kategori Kegiatan --}}
                <div>
                    <label for="kategori_kegiatan" class="block font-semibold text-gray-700 mb-1">Kategori
                        Kegiatan</label>
                    <select name="kategori_kegiatan" id="kategori_kegiatan"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-3 py-2"
                        required>
                        <option value="">Pilih Kategori</option>
                        <option value="publik" {{ old('kategori_kegiatan') == 'publik' ? 'selected' : '' }}>Publik
                        </option>
                        <option value="internal" {{ old('kategori_kegiatan') == 'internal' ? 'selected' : '' }}>Internal
                        </option>
                    </select>
                    @error('kategori_kegiatan')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="flex gap-3 mt-2">
                    <button id="btn-simpan" type="submit"
                        class="flex-1 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition text-base">
                        Simpan
                    </button>
                    <a href="{{ url()->previous() }}" id="btn-batalkan"
                        class="flex-1 px-4 py-2 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow transition text-base text-center">
                        Batalkan
                    </a>
                </div>
            </form>
        </div>

        {{-- SIDEBAR: Progress & Tips --}}
        <aside class="flex flex-col gap-6 w-full max-w-sm mx-auto mt-10 lg:mt-0">
            {{-- CARD ROLE --}}
            <div
                class="bg-gradient-to-br from-blue-700 to-blue-500 text-white rounded-2xl shadow-lg p-7 flex flex-col items-center justify-center text-center">
                <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-14 w-14 mb-3">
                <p class="font-bold text-base leading-tight">{{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}</p>
            </div>
            {{-- Progress Box --}}
            <div
                class="rounded-xl shadow-xl bg-gradient-to-r from-green-400 via-blue-500 to-blue-700 p-6 flex flex-col items-center">
                <i class="fa fa-tasks text-4xl mb-3 text-white drop-shadow"></i>
                <div class="font-bold text-lg text-white mb-1">Progress Kegiatan Kasubbidang</div>
                <div class="text-white text-sm opacity-90 text-center">
                    Dokumentasikan setiap aktivitas kerja, inovasi, knowledge sharing, pelatihan, dan kolaborasi tim di
                    sini.
                </div>
            </div>
            {{-- Tips Box --}}
            <div class="rounded-xl shadow-lg bg-white p-6">
                <div class="font-semibold text-blue-700 mb-3">Tips Produktif Kasubbidang</div>
                <ul class="text-gray-700 text-sm space-y-1 pl-4 list-disc">
                    <li>Update laporan kegiatan secara berkala.</li>
                    <li>Unggah dokumentasi foto setiap aktivitas.</li>
                    <li>Laporkan kegiatan inovatif & kolaboratif.</li>
                    <li>Jaga kualitas dokumentasi pengetahuan.</li>
                </ul>
            </div>
        </aside>
    </div>

    {{-- Preview Foto --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputFile = document.getElementById('foto_kegiatan_input');
        const previewContainer = document.getElementById('preview-foto');
        let filesToUpload = [];

        inputFile.addEventListener('change', function(event) {
            filesToUpload = Array.from(event.target.files).slice(0, 5);
            renderPreview();
        });

        function renderPreview() {
            previewContainer.innerHTML = '';
            filesToUpload.forEach((file, idx) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = "relative";
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = "h-16 w-16 object-cover rounded-lg border";
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.textContent = '×';
                    removeBtn.className =
                        "absolute top-[-7px] right-[-7px] bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700";
                    removeBtn.onclick = () => {
                        filesToUpload.splice(idx, 1);
                        updateInputFiles();
                        renderPreview();
                    };
                    div.appendChild(img);
                    div.appendChild(removeBtn);
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
            updateInputFiles();
        }

        function updateInputFiles() {
            const dt = new DataTransfer();
            filesToUpload.forEach(file => dt.items.add(file));
            inputFile.files = dt.files;
        }
    });
    </script>

    {{-- FOOTER --}}
    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                    class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>

    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Konfirmasi simpan
        const form = document.getElementById('form-kegiatan');
        const btnSimpan = form.querySelector('button[type="submit"]');
        const btnBatalkan = form.querySelector('a[href="{{ url()->previous() }}"]');

        // Handler SIMPAN
        btnSimpan.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'question',
                title: 'Simpan Data?',
                text: 'Apakah data sudah benar dan ingin disimpan?',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg mx-2',
                    cancelButton: 'bg-red-700 hover:bg-red-800 text-white font-semibold px-6 py-2 rounded-lg mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Handler BATALKAN
        btnBatalkan.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Batalkan Input?',
                text: 'Data yang diisi akan dibatalkan dan tidak disimpan.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Kembali',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'bg-red-700 hover:bg-red-800 text-white font-semibold px-6 py-2 rounded-lg mx-2',
                    cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-900 font-semibold px-6 py-2 rounded-lg mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url()->previous() }}";
                }
            });
        });
    });
    </script>

</x-app-layout>