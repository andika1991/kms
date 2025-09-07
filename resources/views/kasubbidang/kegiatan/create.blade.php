@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Tambah Kegiatan Kasubbidang')

<style>
[x-cloak] {
    display: none
}
</style>

<x-app-layout>
    {{-- HEADER --}}
    <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Kegiatan Kasubbidang</h2>
                <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
            </div>

            <div class="flex items-center gap-4 w-full sm:w-auto">
                <label class="relative flex-grow sm:flex-grow-0 sm:w-64">
                    <input type="text" placeholder="Cari kegiatan..."
                        class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fa fa-search"></i>
                    </span>
                </label>

                <div x-data="{ open:false }" class="relative">
                    <button @click="open = !open"
                        class="w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                        title="Profile">
                        <i class="fa-solid fa-user"></i>
                    </button>
                    <nav x-show="open" @click.away="open=false" x-transition
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
                    </nav>
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN --}}
    <main
        class="flex flex-col lg:flex-row gap-8 justify-center items-start min-h-[80vh] px-3 md:px-6 py-8 bg-[#eaf5ff]">

        {{-- FORM --}}
        <form id="form-kegiatan" method="POST" action="{{ route('kasubbidang.kegiatan.store') }}"
            enctype="multipart/form-data"
            class="w-full max-w-2xl mx-auto bg-white rounded-2xl shadow-xl px-6 md:px-7 py-7 space-y-6">
            @csrf
            <input type="hidden" name="subbidang_id" value="{{ $subbidangId ?? '' }}">

            {{-- THUMBNAIL --}}
            <section x-data="{ preview:null }" class="relative" x-cloak>
                <label class="block font-semibold text-gray-700 mb-1">Thumbnail</label>

                {{-- Bingkai rasio tetap + overflow hidden, tanpa 'block' yang redundant --}}
                <label for="thumbnail"
                    class="relative w-full aspect-video rounded-xl border-2 border-dashed border-gray-300
                bg-white overflow-hidden grid place-items-center text-center cursor-pointer hover:bg-gray-50 transition">

                    {{-- IMG absolut agar tidak mengubah tinggi kontainer --}}
                    <template x-if="preview">
                        <img :src="preview" alt="Preview thumbnail"
                            class="absolute inset-0 w-full h-full object-contain p-2 pointer-events-none select-none" />
                    </template>

                    <template x-if="!preview">
                        <div class="text-gray-500">
                            <i class="fa-solid fa-image text-4xl text-gray-400"></i>
                            <p class="mt-2 text-sm">Tambahkan foto</p>
                        </div>
                    </template>
                </label>

                {{-- input di luar label supaya tombol X tidak memicu file picker --}}
                <input id="thumbnail" name="thumbnail" type="file" accept="image/*" class="hidden" x-ref="thumb"
                    @change="
           if($event.target.files.length){
             const r=new FileReader();
             r.onload=e=>preview=e.target.result;
             r.readAsDataURL($event.target.files[0]);
           }">

                {{-- Tombol silang merah untuk menghapus preview --}}
                <button type="button" x-show="preview" @click="preview=null; $refs.thumb.value='';"
                    class="absolute top-2 right-2 z-10 w-7 h-7 grid place-items-center rounded-full bg-red-600 hover:bg-red-700 text-white shadow"
                    aria-label="Hapus thumbnail">✕</button>

                @error('thumbnail')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </section>

            {{-- NAMA --}}
            <section>
                <label for="nama_kegiatan" class="block font-semibold text-gray-700 mb-1">Judul Kegiatan</label>
                <input id="nama_kegiatan" name="nama_kegiatan" type="text" value="{{ old('nama_kegiatan') }}"
                    class="w-full rounded-xl border {{ $errors->has('nama_kegiatan') ? 'border-red-400' : 'border-gray-300' }} bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 px-3 py-2"
                    placeholder="Contoh: Diskusi Gubernur dan Kapolda…" required>
                @error('nama_kegiatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </section>

            {{-- DESKRIPSI --}}
            <section>
                <label for="deskripsi_kegiatan" class="block font-semibold text-gray-700 mb-1">Deskripsi
                    Kegiatan</label>
                <textarea id="deskripsi_kegiatan" name="deskripsi_kegiatan" rows="4"
                    class="w-full rounded-xl border {{ $errors->has('deskripsi_kegiatan') ? 'border-red-400' : 'border-gray-300' }} bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 px-3 py-2"
                    placeholder="Jelaskan kegiatan secara singkat…" required>{{ old('deskripsi_kegiatan') }}</textarea>
                @error('deskripsi_kegiatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </section>

            {{-- FOTO DOKUMENTASI (opsional, max 5) --}}
            <section>
                <label class="block font-semibold text-gray-700 mb-1">
                    Dokumentasi Kegiatan <span class="text-xs text-gray-400">(opsional, maks 5)</span>
                </label>

                <label for="foto_kegiatan_input"
                    class="w-full h-28 border-2 border-dashed border-blue-200 rounded-xl grid place-items-center text-center cursor-pointer hover:border-blue-500 bg-blue-50 transition">
                    <div class="text-blue-600">
                        <i class="fa fa-cloud-upload text-3xl text-blue-400"></i>
                        <p class="text-sm font-medium mt-1">Klik atau tarik foto ke sini</p>
                    </div>
                    <input id="foto_kegiatan_input" name="foto_kegiatan[]" type="file" multiple accept="image/*"
                        class="hidden">
                </label>

                <ul id="preview-foto" class="flex flex-wrap gap-3 mt-3"></ul>

                @error('foto_kegiatan') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </section>

            {{-- KATEGORI --}}
            <section>
                <label for="kategori_kegiatan" class="block font-semibold text-gray-700 mb-1">Kategori Kegiatan</label>
                <select id="kategori_kegiatan" name="kategori_kegiatan"
                    class="w-full rounded-xl border {{ $errors->has('kategori_kegiatan') ? 'border-red-400' : 'border-gray-300' }} bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 px-3 py-2"
                    required>
                    <option value="">Pilih Kategori</option>
                    <option value="publik" {{ old('kategori_kegiatan')=='publik'   ? 'selected' : '' }}>Publik</option>
                    <option value="internal" {{ old('kategori_kegiatan')=='internal' ? 'selected' : '' }}>Internal
                    </option>
                </select>
                @error('kategori_kegiatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </section>

            {{-- CATATAN: tombol dipindahkan ke sidebar, tidak di sini --}}
        </form>

        {{-- SIDEBAR --}}
        <aside class="w-full max-w-sm mx-auto lg:mx-0 mt-4 lg:mt-0 space-y-6">
            <section
                class="bg-gradient-to-br from-blue-700 to-blue-500 text-white rounded-2xl shadow-lg p-7 text-center">
                <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon"
                    class="h-14 w-14 mx-auto mb-3">
                <p class="font-bold">Bidang {{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}</p>
            </section>

            <section class="flex gap-3 w-full">
                <button id="btn-tambah" type="button"
                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition">
                    <i class="fa-solid fa-plus"></i><span>Tambah</span>
                </button>
                <button id="btn-batal" type="button"
                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm transition">
                    <i class="fa-solid fa-xmark"></i><span>Batalkan</span>
                </button>
            </section>

            <section class="bg-white rounded-2xl shadow-lg p-7">
                <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Tips Produktif Kasubbidang</h3>
                <ul class="list-disc list-inside text-sm text-gray-600 leading-relaxed space-y-1">
                    <li>Fokus pada tugas dengan dampak terbesar terlebih dahulu.</li>
                    <li>Beri staf tanggung jawab & instruksi jelas.</li>
                    <li>Gunakan tools digital untuk komunikasi & tugas.</li>
                    <li>Alokasikan waktu singkat untuk perencanaan pagi.</li>
                </ul>
            </section>
        </aside>
    </main>

    {{-- FOOTER --}}
    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                    class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>

    {{-- Preview multi foto (maks 5) --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const inputFile = document.getElementById('foto_kegiatan_input');
        const preview = document.getElementById('preview-foto');
        let filesToUpload = [];

        inputFile.addEventListener('change', e => {
            const incoming = Array.from(e.target.files);
            filesToUpload = [...filesToUpload, ...incoming].slice(0, 5);
            render();
        });

        function render() {
            preview.innerHTML = '';
            filesToUpload.forEach((file, idx) => {
                const r = new FileReader();
                r.onload = ev => {
                    const li = document.createElement('li');
                    li.className = 'relative';

                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    img.alt = 'Preview';
                    img.className = 'h-16 w-16 object-cover rounded-lg border';

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className =
                        'absolute -top-1.5 -right-1.5 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 grid place-items-center';
                    btn.innerHTML = '&times;';
                    btn.onclick = () => {
                        filesToUpload.splice(idx, 1);
                        syncInput();
                        render();
                    };

                    li.append(img, btn);
                    preview.appendChild(li);
                };
                r.readAsDataURL(file);
            });
            syncInput();
        }

        function syncInput() {
            const dt = new DataTransfer();
            filesToUpload.forEach(f => dt.items.add(f));
            inputFile.files = dt.files;
        }
    });
    </script>

    {{-- Actions: Tambah / Batalkan (SweetAlert seperti Figma) --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('form-kegiatan');

        // Modal SIMPAN (success): tombol Tidak (merah) & Ya (hijau)
        document.getElementById('btn-tambah').addEventListener('click', () => {
            Swal.fire({
                icon: 'success',
                title: 'Apakah Anda Yakin',
                html: '<div class="text-gray-600">perubahan akan disimpan</div>',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                customClass: {
                    popup: 'rounded-2xl p-8',
                    confirmButton: 'bg-[#32c671] hover:bg-[#259a51] text-white font-semibold px-8 py-2 rounded-lg mx-2',
                    cancelButton: 'bg-[#a44d3a] hover:bg-[#943b2b] text-white font-semibold px-8 py-2 rounded-lg mx-2'
                },
                buttonsStyling: false
            }).then(res => {
                if (res.isConfirmed) form.submit();
            });
        });

        // Modal BATAL (warning): tombol Batal & Yakin (biru)
        document.getElementById('btn-batal').addEventListener('click', () => {
            Swal.fire({
                icon: 'warning',
                title: 'Apakah Anda Yakin',
                html: '<div class="text-gray-600">perubahan tidak akan disimpan</div>',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText: 'Yakin',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl p-8',
                    confirmButton: 'bg-[#3971A6] hover:bg-[#295480] text-white font-semibold px-8 py-2 rounded-lg mx-2',
                    cancelButton: 'bg-[#3971A6] hover:bg-[#295480] text-white font-semibold px-8 py-2 rounded-lg mx-2'
                },
                buttonsStyling: false
            }).then(res => {
                if (res.isConfirmed) window.location.href = "{{ url()->previous() }}";
            });
        });
    });
    </script>
</x-app-layout>