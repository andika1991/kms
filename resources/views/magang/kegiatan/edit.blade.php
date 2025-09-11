@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');

$thumb = optional($kegiatan->fotokegiatan->first());
$thumbUrl = $thumb? asset('storage/'.$thumb->path_foto) : asset('assets/img/empty-photo.png');
@endphp

@section('title', 'Edit Kegiatan Magang')

<x-app-layout>
    {{-- HEADER (match pegawai) --}}
    <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Kegiatan</h2>
                <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
            </div>

            <div class="flex items-center gap-4 w-full sm:w-auto">
                {{-- Search Bar (optional, non-breaking) --}}
                <form action="{{ route('magang.kegiatan.index') }}" method="GET"
                    class="relative flex-grow sm:flex-grow-0 sm:w-64">
                    <input type="text" name="search" placeholder="Cari kegiatan..." value="{{ request('search') }}"
                        class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
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
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition
                        style="display:none;">
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </button>
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
            <form id="form-kegiatan" method="POST" action="{{ route('magang.kegiatan.update', $kegiatan->id) }}"
                enctype="multipart/form-data" class="bg-white rounded-xl shadow-xl px-7 py-8 flex flex-col gap-7">
                @csrf
                @method('PUT')

                {{-- THUMBNAIL (utama) --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-2">Thumbnail</label>
                    <div class="relative rounded-xl overflow-hidden border shadow">
                        <img id="thumb-img" src="{{ $thumbUrl }}" alt="Thumbnail" class="w-full h-56 object-cover">
                        <div class="absolute right-4 bottom-4 flex gap-2">
                            {{-- Ganti gambar (merge ke foto_kegiatan[] saat submit) --}}
                            <label for="thumbnail_kegiatan_input"
                                class="px-4 py-2 rounded-lg bg-[#2171b8] hover:bg-blue-700 text-white font-semibold text-sm shadow cursor-pointer transition flex items-center gap-2">
                                <i class="fa-solid fa-rotate"></i> Ganti gambar
                                <input type="file" id="thumbnail_kegiatan_input" accept="image/*" class="hidden">
                            </label>

                            {{-- Hapus thumbnail (hapus foto pertama) --}}
                            @if($thumb)
                            <button type="button" id="btn-delete-thumb"
                                class="px-4 py-2 rounded-lg bg-[#e94545] hover:bg-red-700 text-white font-semibold text-sm shadow transition flex items-center gap-2">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- NAMA --}}
                <div>
                    <label for="nama_kegiatan" class="block font-semibold text-gray-700 mb-1">Judul Kegiatan</label>
                    <input type="text" name="nama_kegiatan" id="nama_kegiatan"
                        value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-3 py-2"
                        required>
                    @error('nama_kegiatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- DESKRIPSI --}}
                <div>
                    <label for="deskripsi_kegiatan" class="block font-semibold text-gray-700 mb-1">Deskripsi
                        Kegiatan</label>
                    <textarea name="deskripsi_kegiatan" id="deskripsi_kegiatan" rows="5"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-3 py-2"
                        required>{{ old('deskripsi_kegiatan', $kegiatan->deskripsi_kegiatan) }}</textarea>
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
                        <option value="publik"
                            {{ old('kategori_kegiatan', $kegiatan->kategori_kegiatan) == 'publik' ? 'selected' : '' }}>
                            Publik</option>
                        <option value="internal"
                            {{ old('kategori_kegiatan', $kegiatan->kategori_kegiatan) == 'internal' ? 'selected' : '' }}>
                            Internal</option>
                    </select>
                    @error('kategori_kegiatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- DOKUMENTASI BARU (opsional, max 5) --}}
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

                {{-- DOKUMENTASI LAMA (tanpa thumbnail agar tidak dobel) --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Dokumentasi Saat Ini</label>
                    @if($kegiatan->fotokegiatan->count() > 1)
                    <div class="flex flex-wrap gap-4 mt-2">
                        @foreach($kegiatan->fotokegiatan->slice(1) as $foto)
                        <div class="relative">
                            <img src="{{ asset('storage/'.$foto->path_foto) }}"
                                class="h-20 w-32 object-cover rounded-lg border" alt="Foto Kegiatan">
                            <a href="#"
                                onclick="event.preventDefault(); if(confirm('Hapus foto ini?')) document.getElementById('delete-foto-{{ $foto->id }}').submit();"
                                class="absolute -top-1.5 -right-1.5 bg-[#e94545] text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700">&times;</a>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 mt-2">Belum ada dokumentasi tambahan.</p>
                    @endif
                </div>

                {{-- form delete untuk dokumentasi lama --}}
                @foreach($kegiatan->fotokegiatan->slice(1) as $foto)
                <form id="delete-foto-{{ $foto->id }}" action="{{ route('magang.kegiatan.foto.delete', $foto->id) }}"
                    method="POST" class="hidden">
                    @csrf @method('DELETE')
                </form>
                @endforeach
            </form>

            {{-- form delete untuk thumbnail --}}
            @if($thumb)
            <form id="delete-thumb-form" action="{{ route('magang.kegiatan.foto.delete', $thumb->id) }}" method="POST"
                class="hidden">
                @csrf @method('DELETE')
            </form>
            @endif
        </div>

        {{-- SIDEBAR --}}
        <aside class="flex flex-col gap-6 w-full max-w-sm mt-10 lg:mt-0">
            <div
                class="bg-gradient-to-br from-blue-700 to-blue-500 text-white rounded-2xl shadow-lg p-7 flex flex-col items-center justify-center text-center">
                <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-14 w-14 mb-3">
                <p class="font-bold text-base leading-tight">{{ Auth::user()->role->nama_role ?? 'Magang' }}</p>
            </div>

            <div class="flex gap-3 w-full">
                <button type="button" id="btn-update-kegiatan"
                    class="flex-1 px-4 py-2 rounded-lg bg-[#2171b8] hover:bg-blue-700 text-white font-semibold shadow transition text-base">
                    Simpan
                </button>
                <button type="button" id="btn-cancel-kegiatan"
                    class="flex-1 px-4 py-2 rounded-lg bg-[#e94545] hover:bg-red-700 text-white font-semibold shadow transition text-base">
                    Batalkan
                </button>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-7">
                <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Tips Produktif Magang</h3>
                <ul class="list-disc list-inside text-sm text-gray-600 leading-relaxed space-y-1">
                    <li>Update laporan kegiatan secara berkala.</li>
                    <li>Unggah dokumentasi foto setiap aktivitas.</li>
                    <li>Jaga kualitas dokumentasi pengetahuan.</li>
                </ul>
            </div>
        </aside>
    </div>

    {{-- PREVIEW & HANDLERS --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // === Preview thumbnail baru ===
        const thumbInput = document.getElementById('thumbnail_kegiatan_input');
        const thumbImg = document.getElementById('thumb-img');
        thumbInput?.addEventListener('change', (e) => {
            const f = e.target.files?. [0];
            if (!f) return;
            const r = new FileReader();
            r.onload = ev => thumbImg.src = ev.target.result;
            r.readAsDataURL(f);
        });

        // === Dokumentasi baru (max 5) ===
        const inputFile = document.getElementById('foto_kegiatan_input');
        const preview = document.getElementById('preview-foto');
        let files = [];
        inputFile?.addEventListener('change', (e) => {
            const picked = Array.from(e.target.files);
            files = [...files, ...picked].slice(0, 5);
            renderDocs();
            inputFile.value = '';
        });

        function renderDocs() {
            preview.innerHTML = '';
            files.forEach((file, idx) => {
                const reader = new FileReader();
                reader.onload = ev => {
                    const wrap = document.createElement('div');
                    wrap.className = 'relative';
                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    img.className = 'h-16 w-16 object-cover rounded-lg border';
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = '×';
                    btn.className =
                        'absolute -top-1.5 -right-1.5 bg-[#e94545] text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700';
                    btn.onclick = () => {
                        files.splice(idx, 1);
                        renderDocs();
                    };
                    wrap.appendChild(img);
                    wrap.appendChild(btn);
                    preview.appendChild(wrap);
                };
                reader.readAsDataURL(file);
            });
            // sinkronkan ke input utama
            const dt = new DataTransfer();
            files.forEach(f => dt.items.add(f));
            inputFile.files = dt.files;
        }

        // === Hapus thumbnail (SweetAlert2) ===
        document.getElementById('btn-delete-thumb')?.addEventListener('click', () => {
            Swal.fire({
                 width: 560,
                backdrop: true,
                icon: 'warning',
                iconColor: 'transparent', 
                iconHtml: `
      <svg width="98" height="98" viewBox="0 0 24 24" fill="#F6C343" xmlns="http://www.w3.org/2000/svg">
        <path d="M10.29 3.86L1.82 18A2 2 0 003.55 21h16.9a2 2 0 001.73-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        <rect x="11" y="8" width="2" height="6" fill="white"/>
        <rect x="11" y="15.5" width="2" height="2" rx="1" fill="white"/>
      </svg>
    `,
                // title: 'Batalkan Edit?', // jika ingin mempertahankan judul lama, pakai ini
                title: 'Apakah Anda Yakin',
                html: '<div class="text-gray-600 text-lg">Perubahan tidak akan disimpan</div>',
                showCancelButton: true,
                confirmButtonText: 'Yakin',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-2xl px-8 py-8',
                    icon: 'mb-1', // jarak ikon ke judul dipersempit
                    title: 'text-2xl font-extrabold text-gray-900',
                    htmlContainer: 'mt-1',
                    actions: 'mt-5 flex justify-center gap-6',
                    confirmButton: 'px-10 py-3 rounded-2xl bg-[#2b6cb0] hover:bg-[#235089] text-white text-lg font-semibold',
                    cancelButton: 'px-10 py-3 rounded-2xl bg-[#2b6cb0] hover:bg-[#235089] text-white text-lg font-semibold'
                },
                buttonsStyling: false
            }).then(r => {
                if (r.isConfirmed) document.getElementById('delete-thumb-form')?.submit();
            });
        });

        // === Submit Edit (gabungkan thumbnail ke foto_kegiatan[] supaya backend tetap sama) ===
        document.getElementById('btn-update-kegiatan').addEventListener('click', () => {
            Swal.fire({
                icon: 'success',
                title: 'Apakah Anda Yakin?',
                text: 'Perubahan akan disimpan',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg mx-2',
                    cancelButton: 'bg-red-700 hover:bg-red-800 text-white font-semibold px-6 py-2 rounded-lg mx-2'
                },
                buttonsStyling: false
            }).then(r => {
                if (!r.isConfirmed) return;

                const docs = document.getElementById('foto_kegiatan_input');
                const dt = new DataTransfer();
                // tambahkan thumbnail baru (jika ada)
                const t = thumbInput?.files?. [0];
                if (t) dt.items.add(t);
                // pertahankan foto dokumentasi yang sudah dipilih
                Array.from(docs.files).forEach(f => dt.items.add(f));
                docs.files = dt.files;

                document.getElementById('form-kegiatan').submit();
            });
        });

        // === Batalkan ===
        document.getElementById('btn-cancel-kegiatan').addEventListener('click', () => {
            Swal.fire({
                width: 560,
                backdrop: true,
                icon: 'warning',
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
                    icon: 'mb-1', // jarak ikon ke judul dipersempit
                    title: 'text-2xl font-extrabold text-gray-900',
                    htmlContainer: 'mt-1',
                    actions: 'mt-5 flex justify-center gap-6',
                    confirmButton: 'px-10 py-3 rounded-2xl bg-[#2b6cb0] hover:bg-[#235089] text-white text-lg font-semibold',
                    cancelButton: 'px-10 py-3 rounded-2xl bg-[#2b6cb0] hover:bg-[#235089] text-white text-lg font-semibold'
                },
                buttonsStyling: false
            }).then(r => {
                if (r.isConfirmed) window.location.href =
                "{{ route('magang.kegiatan.index') }}";
            });
        });
    });
    </script>

    {{-- SweetAlert2 (terbaru) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>

    {{-- FOOTER --}}
    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                    class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>
</x-app-layout>