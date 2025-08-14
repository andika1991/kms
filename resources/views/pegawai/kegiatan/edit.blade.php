@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');

$thumb = optional($kegiatan->fotokegiatan->first())->path_foto ?? null; // foto pertama = thumbnail
@endphp

@section('title', 'Edit Kegiatan Pegawai')

<x-app-layout>
    {{-- HEADER --}}
    <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Kegiatan</h2>
                <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
            </div>

            <div class="flex items-center gap-4 w-full sm:w-auto">
                {{-- Search Bar --}}
                <form action="{{ route('pegawai.kegiatan.index') }}" method="GET" class="relative flex-grow sm:flex-grow-0 sm:w-64">
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
                            class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                        <i class="fa-solid fa-user"></i>
                    </button>
                    <div x-show="open" @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition style="display:none">
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="w-full min-h-[80vh] bg-[#eaf5ff] flex flex-col lg:flex-row gap-8 px-2 py-10 justify-center items-start">
        {{-- FORM UTAMA --}}
        <div class="w-full max-w-2xl">
            <form id="form-kegiatan" method="POST" action="{{ route('pegawai.kegiatan.update', $kegiatan->id) }}" enctype="multipart/form-data"
                  class="bg-white rounded-xl shadow-xl px-7 py-8 flex flex-col gap-7">
                @csrf
                @method('PUT')

                {{-- THUMBNAIL --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Thumbnail</label>
                    <div class="relative mb-3">
                        <img id="preview-thumbnail"
                             src="{{ $thumb ? asset('storage/'.$thumb) : asset('assets/img/empty-photo.png') }}"
                             alt="Thumbnail Kegiatan"
                             class="w-full h-48 object-cover rounded-lg border shadow">
                        <div class="absolute right-4 bottom-4 flex gap-2 z-10">
                            {{-- Ganti Gambar --}}
                            <label for="thumbnail_kegiatan_input"
                                   class="px-4 py-2 rounded-lg bg-[#2171b8] hover:bg-blue-700 text-white font-semibold text-sm shadow cursor-pointer transition flex items-center gap-2">
                                <i class="fa fa-refresh"></i>
                                Ganti gambar
                                <input type="file" id="thumbnail_kegiatan_input" name="thumbnail_kegiatan" accept="image/*" class="hidden">
                            </label>
                            {{-- Hapus Thumbnail (jika ada) --}}
                            @if ($thumb)
                                <button type="button" id="btn-delete-thumbnail"
                                        class="px-4 py-2 rounded-lg bg-[#e94545] hover:bg-red-700 text-white font-semibold text-sm shadow flex items-center gap-2">
                                    <i class="fa fa-trash"></i>
                                    Hapus
                                </button>
                            @endif
                        </div>
                    </div>
                    @error('thumbnail_kegiatan')
                        <span class="text-red-500 text-xs mt-2">{{ $message }}</span>
                    @enderror
                </div>

                {{-- JUDUL --}}
                <div>
                    <label for="nama_kegiatan" class="block font-semibold text-gray-700 mb-1">Judul Kegiatan</label>
                    <input type="text" name="nama_kegiatan" id="nama_kegiatan"
                           value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}"
                           class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-3 py-2" required>
                    @error('nama_kegiatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- DESKRIPSI --}}
                <div>
                    <label for="deskripsi_kegiatan" class="block font-semibold text-gray-700 mb-1">Deskripsi Kegiatan</label>
                    <textarea name="deskripsi_kegiatan" id="deskripsi_kegiatan" rows="5"
                              class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-3 py-2" required>{{ old('deskripsi_kegiatan', $kegiatan->deskripsi_kegiatan) }}</textarea>
                    @error('deskripsi_kegiatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- KATEGORI --}}
                <div>
                    <label for="kategori_kegiatan" class="block font-semibold text-gray-700 mb-1">Kategori Kegiatan</label>
                    <select name="kategori_kegiatan" id="kategori_kegiatan"
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-3 py-2" required>
                        <option value="">Pilih Kategori</option>
                        <option value="publik"   {{ old('kategori_kegiatan', $kegiatan->kategori_kegiatan) == 'publik' ? 'selected' : '' }}>Publik</option>
                        <option value="internal" {{ old('kategori_kegiatan', $kegiatan->kategori_kegiatan) == 'internal' ? 'selected' : '' }}>Internal</option>
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
                        <input type="file" id="foto_kegiatan_input" name="foto_kegiatan[]" multiple accept="image/*" class="hidden">
                    </label>
                    <div id="preview-foto" class="flex flex-wrap gap-4 mt-2"></div>
                    <div id="hidden-file-inputs"></div>
                    @error('foto_kegiatan') <span class="text-red-500 text-xs mt-2">{{ $message }}</span> @enderror

                    {{-- Dokumentasi Lama (mulai dari foto ke-2 agar thumbnail tidak dobel) --}}
                    @if($kegiatan->fotokegiatan->count() > 1)
                        <div class="mt-4 flex flex-wrap gap-4">
                            @foreach($kegiatan->fotokegiatan->slice(1) as $foto)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $foto->path_foto) }}"
                                         class="h-20 w-32 object-cover rounded-lg border" alt="Foto Kegiatan">
                                    <a href="#"
                                       onclick="event.preventDefault(); if(confirm('Hapus foto ini?')) document.getElementById('delete-foto-{{ $foto->id }}').submit();"
                                       class="absolute top-[-7px] right-[-7px] bg-[#e94545] text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700 opacity-90 group-hover:opacity-100 transition">&times;</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Forms hapus foto lama (di luar form utama) --}}
                @foreach($kegiatan->fotokegiatan->slice(1) as $foto)
                    <form id="delete-foto-{{ $foto->id }}" action="{{ route('pegawai.kegiatan.foto.delete', $foto->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            </form>
        </div>

        {{-- SIDEBAR --}}
        <aside class="flex flex-col gap-6 w-full max-w-sm mt-10 lg:mt-0">
            <div class="bg-gradient-to-br from-blue-700 to-blue-500 text-white rounded-2xl shadow-lg p-7 flex flex-col items-center justify-center text-center">
                <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-14 w-14 mb-3">
                <p class="font-bold text-base leading-tight">Bidang {{ Auth::user()->role->nama_role ?? 'Pegawai' }}</p>
            </div>
            {{-- Tombol Simpan & Batalkan --}}
            <div class="flex gap-3 w-full">
                <button type="button" id="btn-update-kegiatan"
                        class="flex-1 px-4 py-2 rounded-lg bg-[#2171b8] hover:bg-blue-700 text-white font-semibold shadow transition text-base">
                    Edit
                </button>
                <button type="button" id="btn-cancel-kegiatan"
                        class="flex-1 px-4 py-2 rounded-lg bg-[#e94545] hover:bg-red-700 text-white font-semibold shadow transition text-base text-center">
                    Batalkan
                </button>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-7">
                <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Tips Produktif Pegawai</h3>
                <ul class="list-disc list-inside text-sm text-gray-600 leading-relaxed space-y-1">
                    <li>Catat setiap aktivitas harian kerja.</li>
                    <li>Unggah bukti/dokumen pendukung bila ada.</li>
                    <li>Jaga kualitas dokumentasi kegiatan.</li>
                </ul>
            </div>
        </aside>
    </div>

    {{-- PREVIEW & HANDLERS (1x saja, hindari duplikasi listener) --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // === Thumbnail preview ===
        const thumbInput   = document.getElementById('thumbnail_kegiatan_input');
        const thumbPreview = document.getElementById('preview-thumbnail');

        thumbInput?.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (evt) => { thumbPreview.src = evt.target.result; };
            reader.readAsDataURL(file);
        });

        // === Hapus Thumbnail (SweetAlert2) ===
        document.getElementById('btn-delete-thumbnail')?.addEventListener('click', () => {
            Swal.fire({
                icon: 'warning',
                title: 'Hapus Thumbnail?',
                text: 'Thumbnail akan dihapus dari kegiatan ini.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'bg-[#e94545] hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg mx-2',
                    cancelButton:  'bg-gray-300 hover:bg-gray-400 text-gray-900 font-semibold px-6 py-2 rounded-lg mx-2',
                },
                buttonsStyling: false
            }).then((r) => {
                if (r.isConfirmed) {
                    const input = document.createElement('input');
                    input.type  = 'hidden';
                    input.name  = 'delete_thumbnail';
                    input.value = '1';
                    document.getElementById('form-kegiatan').appendChild(input);
                    document.getElementById('form-kegiatan').submit();
                }
            });
        });

        // === Dokumentasi baru (max 5) ===
        let filesToUpload = [];
        const inputFile  = document.getElementById('foto_kegiatan_input');
        const previewBox = document.getElementById('preview-foto');
        const hiddenBox  = document.getElementById('hidden-file-inputs');

        inputFile?.addEventListener('change', (event) => {
            for (const file of event.target.files) {
                if (!file.type.startsWith('image/')) continue;
                if (filesToUpload.length >= 5) { break; }
                if (!filesToUpload.some(f => f.name === file.name && f.size === file.size)) {
                    filesToUpload.push(file);
                }
            }
            renderPreview();
            inputFile.value = '';
        });

        function renderPreview() {
            previewBox.innerHTML = '';
            hiddenBox.innerHTML  = '';
            filesToUpload.slice(0,5).forEach((file, idx) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const wrap = document.createElement('div');
                    wrap.className = 'relative';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'h-16 w-16 object-cover rounded-lg border';

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = '×';
                    btn.className = 'absolute top-[-7px] right-[-7px] bg-[#e94545] text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700';
                    btn.addEventListener('click', () => {
                        filesToUpload.splice(idx, 1);
                        renderPreview();
                    });

                    wrap.appendChild(img);
                    wrap.appendChild(btn);
                    previewBox.appendChild(wrap);
                };
                reader.readAsDataURL(file);

                // inject file ke form (agar terkirim)
                const dt = new DataTransfer();
                dt.items.add(file);
                const hidden = document.createElement('input');
                hidden.type  = 'file';
                hidden.name  = 'foto_kegiatan[]';
                hidden.files = dt.files;
                hidden.style.display = 'none';
                hiddenBox.appendChild(hidden);
            });
        }

        // === Tombol Edit & Batalkan (SweetAlert2) ===
        document.getElementById('btn-update-kegiatan').addEventListener('click', () => {
            Swal.fire({
                icon: 'question',
                title: 'Simpan Perubahan?',
                text: 'Apakah Anda yakin ingin menyimpan perubahan?',
                showCancelButton: true,
                confirmButtonText: 'Ya, Edit',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'bg-[#2171b8] hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg mx-2',
                    cancelButton:  'bg-[#e94545] hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg mx-2',
                },
                buttonsStyling: false
            }).then((r) => {
                if (r.isConfirmed) document.getElementById('form-kegiatan').submit();
            });
        });

        document.getElementById('btn-cancel-kegiatan').addEventListener('click', () => {
            Swal.fire({
                icon: 'warning',
                title: 'Batalkan Edit?',
                text: 'Perubahan tidak akan disimpan.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Kembali',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'bg-[#e94545] hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg mx-2',
                    cancelButton:  'bg-gray-300 hover:bg-gray-400 text-gray-900 font-semibold px-6 py-2 rounded-lg mx-2',
                },
                buttonsStyling: false
            }).then((r) => {
                if (r.isConfirmed) window.location.href = "{{ route('pegawai.kegiatan.index') }}";
            });
        });
    });
    </script>

    {{-- SweetAlert2 (latest) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>

    {{-- FOOTER --}}
    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik" class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>
</x-app-layout>
