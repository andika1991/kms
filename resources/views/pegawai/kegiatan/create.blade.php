@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Tambah Kegiatan Pegawai')

<x-app-layout>
    {{-- HEADER (match admin) --}}
    <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Tambah Kegiatan</h2>
                <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
            </div>

            <div class="flex items-center gap-4 w-full sm:w-auto">
                {{-- Search (opsional) --}}
                <form action="{{ route('pegawai.kegiatan.index') }}" method="GET"
                    class="relative flex-grow sm:flex-grow-0 sm:w-64">
                    <input type="text" name="search" placeholder="Cari kegiatan..."
                        class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition"
                        value="{{ request('search') }}">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fa fa-search"></i>
                    </span>
                </form>

                {{-- Dropdown Profile --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                        title="Profile">
                        <i class="fa-solid fa-user"></i>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition
                        style="display:none">
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

    {{-- MAIN CONTENT --}}
    <div class="w-full min-h-[80vh] bg-[#eaf5ff] flex flex-col lg:flex-row gap-8 px-2 py-10 justify-center items-start">
        {{-- FORM --}}
        <div class="w-full max-w-2xl">
            <form id="form-kegiatan" method="POST" action="{{ route('pegawai.kegiatan.store') }}"
                enctype="multipart/form-data" class="bg-white rounded-xl shadow-xl px-7 py-8 flex flex-col gap-6">
                @csrf
                <input type="hidden" name="subbidang_id" value="{{ $subbidangId ?? '' }}">
                <input type="hidden" name="bidang_id" value="{{ $bidangId ?? '' }}">

                {{-- THUMBNAIL (gambar utama) --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">
                        Thumbnail <span class="text-xs text-gray-400">(gambar utama kegiatan)</span>
                    </label>
                    <label for="thumbnail_kegiatan_input"
                        class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-blue-200 rounded-lg cursor-pointer hover:border-blue-500 bg-blue-50 transition">
                        <i class="fa fa-cloud-upload text-3xl text-blue-400 mb-1"></i>
                        <span class="text-blue-600 text-sm font-medium">Klik atau tarik thumbnail ke sini</span>
                        <input type="file" id="thumbnail_kegiatan_input" name="thumbnail_kegiatan" accept="image/*"
                            class="hidden">
                    </label>
                    <div id="preview-thumbnail" class="mt-2"></div>
                    @error('thumbnail_kegiatan')
                    <span class="text-red-500 text-xs mt-2">{{ $message }}</span>
                    @enderror
                </div>

                {{-- JUDUL --}}
                <div>
                    <label for="nama_kegiatan" class="block font-semibold text-gray-700 mb-1">Judul Kegiatan</label>
                    <input type="text" name="nama_kegiatan" id="nama_kegiatan" value="{{ old('nama_kegiatan') }}"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-3 py-2"
                        placeholder="Contoh: Workshop Data Science" required>
                    @error('nama_kegiatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- DESKRIPSI --}}
                <div>
                    <label for="deskripsi_kegiatan" class="block font-semibold text-gray-700 mb-1">Deskripsi
                        Kegiatan</label>
                    <textarea name="deskripsi_kegiatan" id="deskripsi_kegiatan" rows="3"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-3 py-2"
                        placeholder="Jelaskan kegiatan yang dilakukan secara singkat..."
                        required>{{ old('deskripsi_kegiatan') }}</textarea>
                    @error('deskripsi_kegiatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- KATEGORI --}}
                <div>
                    <label for="kategori_kegiatan" class="block font-semibold text-gray-700 mb-1">Kategori
                        Kegiatan</label>
                    <select name="kategori_kegiatan" id="kategori_kegiatan"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-3 py-2"
                        required>
                        <option value="">Pilih Kategori</option>
                        <option value="publik" {{ old('kategori_kegiatan') == 'publik'   ? 'selected' : '' }}>Publik
                        </option>
                        <option value="internal" {{ old('kategori_kegiatan') == 'internal' ? 'selected' : '' }}>Internal
                        </option>
                    </select>
                    @error('kategori_kegiatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- DOKUMENTASI (opsional, max 5) --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">
                        Dokumentasi Kegiatan <span class="text-xs text-gray-400">(opsional, max 5)</span>
                    </label>
                    <label for="foto_kegiatan_input"
                        class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-blue-200 rounded-lg cursor-pointer hover:border-blue-500 bg-blue-50 transition">
                        <i class="fa fa-cloud-upload text-3xl text-blue-400 mb-1"></i>
                        <span class="text-blue-600 text-sm font-medium">Klik atau tarik foto ke sini</span>
                        <input type="file" id="foto_kegiatan_input" name="foto_kegiatan[]" multiple accept="image/*"
                            class="hidden">
                    </label>
                    <div id="preview-foto" class="flex flex-wrap gap-4 mt-2"></div>
                    @error('foto_kegiatan') <span class="text-red-500 text-xs mt-2">{{ $message }}</span> @enderror
                </div>
            </form>
        </div>

        {{-- SIDEBAR --}}
        <aside class="flex flex-col gap-6 w-full max-w-sm mt-10 lg:mt-0">
            <div
                class="bg-gradient-to-br from-blue-700 to-blue-500 text-white rounded-2xl shadow-lg p-7 flex flex-col items-center justify-center text-center">
                <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-14 w-14 mb-3">
                <p class="font-bold text-base leading-tight">{{ Auth::user()->role->nama_role ?? 'Pegawai' }}</p>
            </div>
            <div class="flex gap-3 w-full">
                <button id="btn-simpan" type="button"
                    class="flex-1 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition text-base">
                    Tambah
                </button>
                <button id="btn-batal" type="button"
                    class="flex-1 px-4 py-2 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow transition text-base">
                    Batalkan
                </button>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-7">
                <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Tips Produktif Pegawai</h3>
                <ul class="list-disc list-inside text-sm text-gray-600 leading-relaxed space-y-1">
                    <li>Catat setiap aktivitas harian kerja.</li>
                    <li>Beri staf Anda tanggung jawab dan instruksi yang jelas.</li>
                    <li>Unggah dokumen pendukung bila ada.</li>
                    <li>Jaga kualitas dokumentasi kegiatan.</li>
                </ul>
            </div>
        </aside>
    </div>

    {{-- PREVIEW: thumbnail (single dgn tombol hapus) & dokumentasi (max 5) --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== Thumbnail (single) =====
        const thumbInput = document.getElementById('thumbnail_kegiatan_input');
        const thumbPreview = document.getElementById('preview-thumbnail');

        function clearThumb() {
            thumbInput.value = '';
            thumbPreview.innerHTML = '';
        }

        function renderThumb(file) {
            thumbPreview.innerHTML = '';
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (ev) => {
                const wrap = document.createElement('div');
                wrap.className = 'relative inline-block';

                const img = document.createElement('img');
                img.src = ev.target.result;
                img.className = 'h-24 w-24 object-cover rounded-lg border';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.textContent = '×';
                removeBtn.className =
                    'absolute -top-1.5 -right-1.5 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700';

                removeBtn.addEventListener('click', clearThumb);

                wrap.appendChild(img);
                wrap.appendChild(removeBtn);
                thumbPreview.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        }

        thumbInput.addEventListener('change', (e) => {
            renderThumb(e.target.files?. [0]);
        });

        // ===== Dokumentasi (multiple, max 5) =====
        const inputFile = document.getElementById('foto_kegiatan_input');
        const previewContainer = document.getElementById('preview-foto');
        let filesToUpload = [];

        inputFile.addEventListener('change', function(event) {
            const newFiles = Array.from(event.target.files);
            const total = [...filesToUpload, ...newFiles].slice(0, 5); // batas 5
            filesToUpload = total;
            renderDocs();
        });

        function renderDocs() {
            previewContainer.innerHTML = '';
            filesToUpload.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrap = document.createElement('div');
                    wrap.className = 'relative';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'h-16 w-16 object-cover rounded-lg border';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.textContent = '×';
                    removeBtn.className =
                        'absolute -top-1.5 -right-1.5 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700';

                    removeBtn.onclick = () => {
                        const idx = filesToUpload.findIndex(f => f === file);
                        if (idx > -1) {
                            filesToUpload.splice(idx, 1);
                            syncInput();
                            renderDocs();
                        }
                    };

                    wrap.appendChild(img);
                    wrap.appendChild(removeBtn);
                    previewContainer.appendChild(wrap);
                };
                reader.readAsDataURL(file);
            });
            syncInput();
        }

        function syncInput() {
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

    {{-- SweetAlert2 (latest) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form-kegiatan');

        document.getElementById('btn-simpan').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'success',
                title: 'Apakah Anda Yakin?',
                html: '<span class="text-gray-600 text-base">Perubahan akan disimpan</span>',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl px-8 pt-5 pb-6',
                    confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-2 rounded-lg text-base mr-2',
                    cancelButton: 'bg-red-700 hover:bg-red-800 text-white font-semibold px-8 py-2 rounded-lg text-base',
                    actions: 'flex justify-center gap-4',
                },
                buttonsStyling: false,
            }).then((r) => {
                if (r.isConfirmed) form.submit();
            });
        });

        document.getElementById('btn-batal').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                width: 560,
                backdrop: true,
                iconColor: 'transparent',
                iconHtml: `
        <svg width="98" height="98" viewBox="0 0 24 24" fill="#F6C343" xmlns="http://www.w3.org/2000/svg">
          <path d="M10.29 3.86L1.82 18A2 2 0 003.55 21h16.9a2 2 0 001.73-3L13.71 3.86a2 2 0 00-3.42 0z"/>
          <rect x="11" y="8" width="2" height="6" fill="white"/>
          <rect x="11" y="15.5" width="2" height="2" rx="1" fill="white"/>
        </svg>
      `,
                title: 'Apakah Anda Yakin',
                html: '<div class="text-gray-600 text-lg">Perubahan tidak akan disimpan</div>',
                showCancelButton: true,
                confirmButtonText: 'Yakin',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-2xl px-8 py-8',
                    icon: 'mb-3',
                    title: 'text-2xl font-extrabold text-gray-900',
                    htmlContainer: 'mt-1',
                    actions: 'mt-6 flex justify-center gap-6',
                    confirmButton: 'px-10 py-3 rounded-2xl bg-[#2b6cb0] hover:bg-[#235089] text-white text-lg font-semibold',
                    cancelButton: 'px-10 py-3 rounded-2xl bg-[#2b6cb0] hover:bg-[#235089] text-white text-lg font-semibold'
                },
                buttonsStyling: false,
            }).then((r) => {
                if (r.isConfirmed) window.location.href = "{{ url()->previous() }}";
            });
        });
    });
    </script>
</x-app-layout>