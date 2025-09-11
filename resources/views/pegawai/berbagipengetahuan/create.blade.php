@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Tambah Artikel Pengetahuan Pegawai')

<x-app-layout>
    <main class="min-h-screen bg-[#eaf5ff] pb-32">
        {{-- HEADER --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <section class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Artikel Pengetahuan</h1>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search (visual only) --}}
                    <form class="relative grow sm:grow-0 sm:w-64" role="search" aria-label="Cari artikel">
                        <input placeholder="Cari artikel pengetahuan..."
                            class="w-full rounded-full border border-gray-300 bg-white pl-10 pr-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i
                                class="fa fa-search"></i></span>
                    </form>

                    {{-- Profile Dropdown --}}
                    <nav x-data="{open:false}" class="relative">
                        <button @click="open=!open" title="Profile"
                            class="size-10 grid place-content-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <ul x-show="open" @click.away="open=false" style="display:none;"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20 overflow-hidden">
                            <li><a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-50">Profile</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm hover:bg-gray-50">Log
                                        Out</button>
                                </form>
                            </li>
                        </ul>
                    </nav>
                </div>
            </section>
        </header>

        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())
        <aside role="alert"
            class="max-w-4xl mx-auto mt-6 mb-2 rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-red-700 text-sm shadow">
            <strong>Periksa kembali inputan Anda:</strong>
            <ul class="list-disc pl-5 mt-1">
                @foreach ($errors->all() as $error)
                <li class="mb-1">{{ $error }}</li>
                @endforeach
            </ul>
        </aside>
        @endif

        {{-- FORM --}}
        <form id="form-create-artikel-pegawai" method="POST" action="{{ route('pegawai.berbagipengetahuan.store') }}"
            enctype="multipart/form-data"
            class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 max-w-[1400px] mx-auto">
            @csrf

            {{-- KIRI --}}
            <section class="xl:col-span-8 grid gap-8">

                {{-- THUMBNAIL --}}
                <section class="bg-white rounded-2xl shadow-lg p-6" x-data="{ preview:null }">
                    <label for="thumbnail" class="block font-semibold text-gray-800 mb-2">Thumbnail</label>

                    <label for="thumbnail"
                        class="w-full h-48 md:h-56 border-2 border-dashed border-gray-300 rounded-xl grid place-content-center text-center cursor-pointer hover:bg-gray-50 transition relative">
                        <template x-if="preview">
                            <img :src="preview" alt="Preview Thumbnail"
                                class="absolute inset-0 w-full h-full object-contain rounded-xl" />
                        </template>
                        <template x-if="!preview">
                            <div>
                                <i class="fa-solid fa-image text-4xl text-gray-400"></i>
                                <p class="mt-2 text-sm text-gray-500">Tambahkan foto</p>
                            </div>
                        </template>
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="hidden"
                            @change="if($event.target.files.length){let r=new FileReader();r.onload=e=>preview=e.target.result;r.readAsDataURL($event.target.files[0]);}">
                    </label>
                    @error('thumbnail')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </section>

                {{-- JUDUL --}}
                <fieldset class="bg-white rounded-2xl shadow-lg p-6">
                    <label for="judul" class="block font-semibold text-gray-800 mb-1">Judul Artikel</label>
                    <input id="judul" name="judul" value="{{ old('judul') }}" required
                        @class([ 'w-full rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 border'
                        , 'border-red-400'=> $errors->has('judul'),
                    'border-gray-300' => ! $errors->has('judul'),
                    ])
                    placeholder="Masukkan Judul">
                    @error('judul') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </fieldset>

                {{-- KATEGORI & SLUG --}}
                <section class="grid md:grid-cols-2 gap-6">
                    <fieldset class="bg-white rounded-2xl shadow-lg p-6">
                        <label for="kategori_pengetahuan_id"
                            class="block font-semibold text-gray-800 mb-1">Kategori</label>
                        <select id="kategori_pengetahuan_id" name="kategori_pengetahuan_id" required
                            @class([ 'w-full rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 border'
                            , 'border-red-400'=> $errors->has('kategori_pengetahuan_id'),
                            'border-gray-300' => ! $errors->has('kategori_pengetahuan_id'),
                            ])>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}"
                                {{ old('kategori_pengetahuan_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategoripengetahuan }}
                            </option>
                            @endforeach
                        </select>
                        @error('kategori_pengetahuan_id') <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <fieldset class="bg-white rounded-2xl shadow-lg p-6">
                        <label for="slug" class="block font-semibold text-gray-800 mb-1">Slug</label>
                        <input id="slug" name="slug" value="{{ old('slug') }}" required
                            @class([ 'w-full rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 border'
                            , 'border-red-400'=> $errors->has('slug'),
                        'border-gray-300' => ! $errors->has('slug'),
                        ])
                        placeholder="masukkan-slug">
                        @error('slug') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </fieldset>
                </section>

                {{-- ISI --}}
                <fieldset class="bg-white rounded-2xl shadow-lg p-6">
                    <label for="isi" class="block font-semibold text-gray-800 mb-2">Isi Artikel</label>
                    <textarea id="isi" name="isi" rows="10"
                        @class([ 'w-full rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 border'
                        , 'border-red-400'=> $errors->has('isi'),
                      'border-gray-300' => ! $errors->has('isi'),
                    ])
                    placeholder="Masukkan isi artikel">{{ old('isi') }}</textarea>
                    @error('isi') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </fieldset>

                {{-- FILE DOKUMEN --}}
                <section class="bg-white rounded-2xl shadow-lg p-6" x-data="{fileName:null}">
                    <label for="filedok" class="block font-semibold text-gray-800 mb-2">File Dokumen</label>
                    <label for="filedok"
                        class="w-full h-32 border-2 border-dashed border-gray-300 rounded-xl grid place-content-center text-center cursor-pointer hover:bg-gray-50 transition">
                        <i class="fa-solid fa-file-arrow-up text-4xl text-gray-400"></i>
                        <span class="mt-2 text-sm text-gray-500" x-show="!fileName">Tambah dokumen</span>
                        <span class="mt-2 text-sm text-gray-700 font-semibold" x-text="fileName"
                            x-show="fileName"></span>
                        <input type="file" name="filedok" id="filedok"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar" class="hidden"
                            @change="fileName = $event.target.files.length ? $event.target.files[0].name : null">
                    </label>
                    @error('filedok') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </section>
            </section>

            {{-- KANAN --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-6">
                <article
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon"
                        class="h-16 w-16 mx-auto mb-4">
                    <h2 class="font-bold text-lg">{{ Auth::user()->role->nama_role ?? 'User' }}</h2>
                </article>

                <nav class="grid md:grid-cols-2 gap-4">
                    <button id="btn-simpan-artikel" type="button"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-save"></i><span>Tambah</span>
                    </button>
                    <a id="btn-cancel-artikel" href="{{ url()->previous() }}" data-href="{{ url()->previous() }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-times"></i>
                        <span>Batalkan</span>
                    </a>
                </nav>
            </aside>
        </form>
    </main>

    {{-- TinyMCE --}}
    <script src="https://cdn.tiny.cloud/1/5tsdsuoydzm2f0tjnkrffxszmoas3as1xlmcg5ujs82or4wz/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
    tinymce.init({
        selector: '#isi',
        height: 300,
        plugins: 'lists link image preview',
        toolbar: 'undo redo | formatselect | bold italic underline | bullist numlist | link image | preview',
        menubar: false,
        content_css: false,
        skin: 'oxide-dark',
    });

    // Slug otomatis
    document.getElementById('judul').addEventListener('keyup', function() {
        const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        document.getElementById('slug').value = slug;
    });
    </script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
    <script>
    document.getElementById('btn-simpan-artikel').addEventListener('click', () => {
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            html: '<span class="font-semibold">Perubahan akan disimpan.</span>',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl p-8',
                confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-2 rounded-lg text-base mr-2',
                cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-2 rounded-lg text-base',
                actions: 'flex justify-center gap-4',
            },
            buttonsStyling: false,
        }).then(r => {
            if (r.isConfirmed) document.getElementById('form-create-artikel-pegawai').submit();
        });
    });

    // Modal BATALKAN
    document.addEventListener('DOMContentLoaded', () => {
        const cancelBtn = document.getElementById('btn-cancel-artikel');
        if (!cancelBtn) return;

        cancelBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const targetUrl = cancelBtn.dataset.href || cancelBtn.getAttribute('href');

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
            }).then((res) => {
                if (res.isConfirmed) window.location.href = targetUrl;
            });
        });
    }); 
    </script>
</x-app-layout>