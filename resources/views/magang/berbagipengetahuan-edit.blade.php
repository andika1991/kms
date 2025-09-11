@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Edit Pengetahuan Magang')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] pb-32">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Artikel Pengetahuan</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari artikel pengetahuan..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                    {{-- Dropdown Profile --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition>
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

        {{-- FORM EDIT ARTIKEL --}}
        <form id="edit-artikel-form" method="POST"
            action="{{ route('magang.berbagipengetahuan.update', $artikelpengetahuan->id) }}"
            enctype="multipart/form-data" class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            @csrf
            @method('PUT')

            {{-- KOLOM KIRI FORM --}}
            <div class="xl:col-span-8 w-full flex flex-col gap-8">

                {{-- Thumbnail --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <label class="block font-semibold text-gray-800 mb-2">Thumbnail</label>
                    <div x-data="{
                        preview: '{{ $artikelpengetahuan->thumbnail ? asset('storage/'.$artikelpengetahuan->thumbnail) : '' }}',
                        original: '{{ $artikelpengetahuan->thumbnail ? asset('storage/'.$artikelpengetahuan->thumbnail) : '' }}'
                    }" class="w-full flex flex-col items-center">
                        <div
                            class="w-full h-48 md:h-56 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center bg-gray-50 overflow-hidden mb-3">
                            <template x-if="preview">
                                <img :src="preview" alt="Preview Thumbnail"
                                    class="w-full h-full object-cover rounded-xl" />
                            </template>
                            <template x-if="!preview">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-image text-4xl text-gray-400"></i>
                                    <div class="mt-2 text-sm text-gray-500">Tambahkan foto</div>
                                </div>
                            </template>
                        </div>
                        <div class="flex gap-2 w-full justify-end mt-2">
                            {{-- Ganti gambar --}}
                            <button type="button"
                                class="px-4 py-1 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition"
                                @click="Swal.fire({
                                    title: 'Ganti Thumbnail',
                                    text: 'Yakin ingin mengganti gambar?',
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Ganti',
                                    cancelButtonText: 'Batal',
                                    reverseButtons: true,
                                    customClass: {
                                        confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg mx-2',
                                        cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg mx-2'
                                    }
                                }).then((result) => {
                                    if(result.isConfirmed) {
                                        document.getElementById('thumbnail').click();
                                    }
                                })">
                                Ganti gambar
                            </button>
                            {{-- Hapus --}}
                            <button type="button"
                                class="px-4 py-1 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition"
                                @click="Swal.fire({
                                    title: 'Hapus Thumbnail',
                                    text: 'Yakin ingin menghapus gambar?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Hapus',
                                    cancelButtonText: 'Batal',
                                    reverseButtons: true,
                                    customClass: {
                                        confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg mx-2',
                                        cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg mx-2'
                                    }
                                }).then((result) => {
                                    if(result.isConfirmed) {
                                        preview = ''; 
                                        document.getElementById('thumbnail').value = '';
                                    }
                                })">
                                Hapus
                            </button>
                        </div>
                        {{-- Input file hidden --}}
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="hidden" @change="if($event.target.files.length){
                                let reader = new FileReader();
                                reader.onload = e => preview = e.target.result;
                                reader.readAsDataURL($event.target.files[0]);
                            }">
                        @if($artikelpengetahuan->thumbnail)
                        <div class="text-xs text-gray-500 mt-2 truncate">
                            {{ \Illuminate\Support\Str::afterLast($artikelpengetahuan->thumbnail, '/') }}
                        </div>
                        @endif
                        @error('thumbnail')
                        <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Judul Artikel --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <label for="judul" class="block font-semibold text-gray-800 mb-1">Judul Artikel</label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul', $artikelpengetahuan->judul) }}"
                        required
                        class="w-full rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan Judul">
                </div>

                {{-- Kategori & Slug --}}
                <div class="flex flex-col md:flex-row gap-6">
                    {{-- Kategori --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 w-full">
                        <label for="kategori_pengetahuan_id"
                            class="block font-semibold text-gray-800 mb-1">Kategori</label>
                        <select name="kategori_pengetahuan_id" id="kategori_pengetahuan_id"
                            class="w-full rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}"
                                {{ old('kategori_pengetahuan_id', $artikelpengetahuan->kategori_pengetahuan_id) == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategoripengetahuan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Slug --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 w-full">
                        <label for="slug" class="block font-semibold text-gray-800 mb-1">Slug</label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $artikelpengetahuan->slug) }}"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan Slug">
                    </div>
                </div>

                {{-- Isi Artikel --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <label for="isi" class="block font-semibold text-gray-800 mb-2">Isi Artikel</label>
                    <textarea id="isi" name="isi" rows="10"
                        class="w-full rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan isi artikel">{{ old('isi', $artikelpengetahuan->isi) }}</textarea>
                </div>

                {{-- Dokumen Tambahan --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <label for="filedok" class="block font-semibold text-gray-800 mb-2">Dokumen</label>
                    <div
                        x-data="{ fileName: '{{ $artikelpengetahuan->filedok ? \Illuminate\Support\Str::afterLast($artikelpengetahuan->filedok, '/') : '' }}' }">
                        <label for="filedok"
                            class="w-full h-32 border-2 border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center text-center cursor-pointer hover:bg-gray-50 transition relative">
                            <i class="fa-solid fa-file-arrow-up text-4xl text-gray-400"></i>
                            <span class="mt-2 text-sm text-gray-500" x-show="!fileName">Tambah dokumen</span>
                            <span class="mt-2 text-sm text-gray-700 font-semibold" x-text="fileName"
                                x-show="fileName"></span>
                            <input type="file" name="filedok" id="filedok"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar" class="hidden"
                                @change="fileName = $event.target.files.length ? $event.target.files[0].name : null">
                        </label>
                        @if($artikelpengetahuan->filedok)
                        <a href="{{ asset('storage/'.$artikelpengetahuan->filedok) }}" target="_blank"
                            class="block text-blue-600 underline text-xs mt-2">
                            {{ \Illuminate\Support\Str::afterLast($artikelpengetahuan->filedok, '/') }}
                        </a>
                        @endif
                        @error('filedok')
                        <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-6">
                {{-- Card Role --}}
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'Magang' }}
                        </p>
                    </div>
                </div>
                {{-- Tombol Aksi --}}
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <button id="btn-update-artikel" type="button"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-save"></i>
                        <span>Update</span>
                    </button>
                    <button id="btn-cancel-artikel" type="button"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-times"></i>
                        <span>Batalkan</span>
                    </button>
                </div>
            </aside>
        </form>
    </div>

    {{-- FOOTER --}}
    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                    class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>

    {{-- TinyMCE CDN --}}
    <script src="https://cdn.tiny.cloud/1/5tsdsuoydzm2f0tjnkrffxszmoas3as1xlmcg5ujs82or4wz/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script>
    tinymce.init({
        selector: '#isi',
        height: 300,
        plugins: 'lists link image preview',
        toolbar: 'undo redo | formatselect | bold italic underline | bullist numlist | link image | preview',
        menubar: false,
        content_css: false,
        skin: "oxide-dark",
    });

    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.querySelector('input[name="judul"]');
        const slugInput = document.querySelector('input[name="slug"]');

        if (titleInput && slugInput) {
            titleInput.addEventListener('input', function() {
                let slug = this.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
                slugInput.value = slug;
            });
        }
    });

    // SweetAlert2 Modal 
    document.getElementById('btn-update-artikel').addEventListener('click', function(e) {
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
                document.getElementById('edit-artikel-form').submit();
            }
        });
    });

    // Batalkan → redirect ke halaman show
    document.getElementById('btn-cancel-artikel').addEventListener('click', function(e) {
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
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href =
                    "{{ route('magang.berbagipengetahuan.show', $artikelpengetahuan->id) }}";
            }
        });
    });
    </script>
</x-app-layout>