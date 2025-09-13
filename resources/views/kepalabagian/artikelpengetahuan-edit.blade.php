@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Edit Artikel Pengetahuan Kepala Bagian')

<x-app-layout>
    <main class="w-full min-h-screen bg-[#eaf5ff] pb-32">

        {{-- HEADER --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Artikel Pengetahuan</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search (presentasional) --}}
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari artikel pengetahuan..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>

                    {{-- Dropdown Profile --}}
                    <nav x-data="{ open:false }" class="relative">
                        <button @click="open=!open"
                            class="w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open=false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition
                            style="display:none;">
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
                    </nav>
                </div>
            </div>
        </header>

        {{-- FORM EDIT (struktur & name field tetap) --}}
        <form id="edit-artikel-kb" method="POST"
            action="{{ route('kepalabagian.artikelpengetahuan.update', $artikelpengetahuan->id) }}"
            enctype="multipart/form-data" class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            @csrf
            @method('PUT')

            {{-- KOLOM KIRI --}}
            <section class="xl:col-span-8 w-full space-y-8">

                {{-- THUMBNAIL + PREVIEW --}}
                <fieldset class="bg-white rounded-2xl shadow-lg p-6" x-data="{
                   preview: @js($artikelpengetahuan->thumbnail ? asset('storage/'.$artikelpengetahuan->thumbnail) : ''),
                 }">
                    <label for="thumbnail" class="block font-semibold text-gray-800 mb-2">Thumbnail</label>

                    <label for="thumbnail" class="w-full h-48 md:h-56 border-2 border-dashed border-gray-300 rounded-xl
                        flex flex-col items-center justify-center text-center cursor-pointer
                        hover:bg-gray-50 transition overflow-hidden">
                        <template x-if="preview">
                            <img :src="preview" alt="Preview Thumbnail" class="w-full h-full object-cover rounded-xl" />
                        </template>
                        <template x-if="!preview">
                            <div>
                                <i class="fa-solid fa-image text-4xl text-gray-400"></i>
                                <p class="mt-2 text-sm text-gray-500">Tambahkan / ganti foto</p>
                            </div>
                        </template>
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="hidden"
                            @change="if($event.target.files.length){const r=new FileReader();r.onload=e=>preview=e.target.result;r.readAsDataURL($event.target.files[0]);}">
                    </label>

                    @if($artikelpengetahuan->thumbnail)
                    <p class="text-xs text-gray-500 mt-2 truncate">
                        {{ \Illuminate\Support\Str::afterLast($artikelpengetahuan->thumbnail, '/') }}
                    </p>
                    @endif
                    @error('thumbnail') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror

                    <div class="flex gap-2 justify-end mt-3">
                        <button type="button"
                            class="px-4 py-1 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition"
                            @click="document.getElementById('thumbnail').click()">
                            Ganti Gambar
                        </button>
                        <button type="button"
                            class="px-4 py-1 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition"
                            @click="Swal.fire({
                      title:'Hapus Thumbnail?',
                      icon:'warning',
                      showCancelButton:true,
                      confirmButtonText:'Hapus',
                      cancelButtonText:'Batal',
                      reverseButtons:true,
                      buttonsStyling:false,
                      customClass:{confirmButton:'bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg mr-2',cancelButton:'bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg'}
                    }).then(r=>{ if(r.isConfirmed){ preview=''; const i=document.getElementById('thumbnail'); i.value=''; }})">
                            Hapus
                        </button>
                    </div>
                </fieldset>

                {{-- JUDUL --}}
                <fieldset class="bg-white rounded-2xl shadow-lg p-6">
                    <label for="judul" class="block font-semibold text-gray-800 mb-1">Judul Artikel</label>
                    <input type="text" id="judul" name="judul" required
                        value="{{ old('judul', $artikelpengetahuan->judul) }}"
                        class="w-full rounded-xl border @error('judul') border-red-400 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan Judul">
                    @error('judul') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </fieldset>

                {{-- KATEGORI & SLUG --}}
                <section class="flex flex-col md:flex-row gap-6">
                    <fieldset class="bg-white rounded-2xl shadow-lg p-6 w-full">
                        <label for="kategori_pengetahuan_id"
                            class="block font-semibold text-gray-800 mb-1">Kategori</label>
                        <select name="kategori_pengetahuan_id" id="kategori_pengetahuan_id" required
                            class="w-full rounded-xl border @error('kategori_pengetahuan_id') border-red-400 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}"
                                {{ old('kategori_pengetahuan_id', $artikelpengetahuan->kategori_pengetahuan_id) == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategoripengetahuan }}-{{ $kat->subbidang->nama ?? '-' }}
                            </option>
                            @endforeach
                        </select>
                        @error('kategori_pengetahuan_id') <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <fieldset class="bg-white rounded-2xl shadow-lg p-6 w-full">
                        <label for="slug" class="block font-semibold text-gray-800 mb-1">Slug Artikel</label>
                        <input type="text" id="slug" name="slug" required
                            value="{{ old('slug', $artikelpengetahuan->slug) }}"
                            class="w-full rounded-xl border @error('slug') border-red-400 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="masukkan-slug">
                        @error('slug') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </fieldset>
                </section>

                {{-- ISI ARTIKEL --}}
                <fieldset class="bg-white rounded-2xl shadow-lg p-6">
                    <label for="isi" class="block font-semibold text-gray-800 mb-2">Isi Artikel</label>
                    <textarea id="isi" name="isi" rows="10"
                        class="w-full rounded-xl border @error('isi') border-red-400 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan isi artikel">{{ old('isi', $artikelpengetahuan->isi) }}</textarea>
                    @error('isi') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </fieldset>

                {{-- DOKUMEN --}}
                <fieldset class="bg-white rounded-2xl shadow-lg p-6"
                    x-data="{ fileName: @js($artikelpengetahuan->filedok ? \Illuminate\Support\Str::afterLast($artikelpengetahuan->filedok, '/') : null) }">
                    <label for="filedok" class="block font-semibold text-gray-800 mb-2">Dokumen</label>

                    @if($artikelpengetahuan->filedok)
                    <a href="{{ asset('storage/'.$artikelpengetahuan->filedok) }}" target="_blank"
                        class="inline-flex items-center text-blue-600 underline text-xs mb-2">
                        {{ \Illuminate\Support\Str::afterLast($artikelpengetahuan->filedok, '/') }}
                    </a>
                    @endif

                    <label for="filedok"
                        class="w-full h-32 border-2 border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center text-center cursor-pointer hover:bg-gray-50 transition">
                        <i class="fa-solid fa-file-arrow-up text-4xl text-gray-400"></i>
                        <span class="mt-2 text-sm text-gray-500" x-show="!fileName">Ganti / tambah dokumen</span>
                        <span class="mt-2 text-sm text-gray-700 font-semibold" x-text="fileName"
                            x-show="fileName"></span>
                        <input type="file" name="filedok" id="filedok"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar" class="hidden"
                            @change="fileName = $event.target.files.length ? $event.target.files[0].name : null">
                    </label>
                    @error('filedok') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </fieldset>

            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 w-full space-y-6">
                <section
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon"
                        class="h-16 w-16 mx-auto mb-4">
                    <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'User' }}</p>
                </section>

                <section class="flex flex-col md:flex-row gap-4">
                    <button id="btn-update-kb" type="button"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-save"></i><span>Update</span>
                    </button>
                    <button id="btn-cancel-kb" type="button"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-times"></i><span>Batalkan</span>
                    </button>
                </section>
            </aside>

        </form>
    </main>

    {{-- TinyMCE --}}
    <script src="https://cdn.tiny.cloud/1/xrupfgu8f5pudjwsjb06juo0bowil9b3qsjg9st7gz8fbcku/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
    tinymce.init({
        selector: '#isi',
        height: 400,
        plugins: 'lists link image preview code fullscreen',
        toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link image | preview code fullscreen',
        menubar: false
    });

    // Auto-slug dari judul
    document.addEventListener('DOMContentLoaded', () => {
        const j = document.getElementById('judul');
        const s = document.getElementById('slug');
        if (j && s) {
            j.addEventListener('input', function() {
                s.value = this.value.toLowerCase().trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            });
        }
    });
    </script>

    {{-- SweetAlert2 terbaru --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.5/dist/sweetalert2.all.min.js"></script>
    <script>
    // Konfirmasi Update
    document.getElementById('btn-update-kb').addEventListener('click', () => {
        Swal.fire({
            title: 'Simpan perubahan?',
            html: '<span class="font-semibold">Perubahan akan disimpan.</span>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-2xl p-8',
                confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-2 rounded-lg mr-2',
                cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-2 rounded-lg',
                actions: 'flex justify-center gap-4'
            }
        }).then(r => {
            if (r.isConfirmed) document.getElementById('edit-artikel-kb').submit();
        });
    });

    // Konfirmasi Batal -> kembali ke halaman detail
    document.getElementById('btn-cancel-kb').addEventListener('click', () => {
        Swal.fire({
            title: 'Batalkan perubahan?',
            html: '<span class="font-semibold">Perubahan yang belum disimpan akan hilang.</span>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, batalkan',
            cancelButtonText: 'Tidak',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-2xl p-8',
                confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-2 rounded-lg mr-2',
                cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-2 rounded-lg',
                actions: 'flex justify-center gap-4'
            }
        }).then(r => {
            if (r.isConfirmed) {
                window.location.href =
                    "{{ route('kepalabagian.artikelpengetahuan.show', $artikelpengetahuan->id) }}";
            }
        });
    });
    </script>
</x-app-layout>