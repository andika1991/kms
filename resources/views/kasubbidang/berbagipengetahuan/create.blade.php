@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');

/* Helper kelas border/ring agar tidak menulis dua kelas border sekaligus (menghilangkan warning Tailwind) */
$ctrlClass = function(string $name) use ($errors) {
    return $errors->has($name)
        ? 'border-red-400 focus:ring-red-500'
        : 'border-gray-300 focus:ring-blue-500';
};
@endphp

@section('title', 'Tambah Artikel Pengetahuan Kasubbidang')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] pb-32">
        {{-- HEADER --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Artikel Pengetahuan</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <label class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <span class="sr-only">Cari artikel pengetahuan</span>
                        <input
                            type="text"
                            placeholder="Cari artikel pengetahuan..."
                            class="w-full rounded-full border border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition"
                        />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </label>

                    {{-- Dropdown Profile --}}
                    <div x-data="{ open: false }" class="relative">
                        <button
                            @click="open = !open"
                            class="w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile"
                            aria-haspopup="menu"
                            aria-expanded="false"
                        >
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <nav
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20"
                            style="display:none;"
                        >
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        {{-- PESAN ERROR VALIDASI --}}
        @if ($errors->any())
            <section class="max-w-4xl mx-auto mt-6 mb-2">
                <div class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-red-700 text-sm shadow">
                    <strong>Periksa kembali inputan Anda:</strong>
                    <ul class="list-disc pl-5 mt-1">
                        @foreach ($errors->all() as $error)
                            <li class="mb-1">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif

        {{-- FORM TAMBAH ARTIKEL --}}
        <form
            id="form-tambah-artikel"
            method="POST"
            action="{{ route('kasubbidang.berbagipengetahuan.store') }}"
            enctype="multipart/form-data"
            class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8"
        >
            @csrf

            {{-- KOLOM KIRI --}}
            <section class="xl:col-span-8 flex flex-col gap-8">
                {{-- THUMBNAIL --}}
                <section class="bg-white rounded-2xl shadow-lg p-6">
                    <label for="thumbnail" class="block font-semibold text-gray-800 mb-2">Thumbnail</label>
                    <div x-data="{ preview: null }">
                        <label
                            for="thumbnail"
                            role="button"
                            tabindex="0"
                            class="w-full h-48 md:h-56 border-2 border-dashed {{ $errors->has('thumbnail') ? 'border-red-400' : 'border-gray-300' }} rounded-xl grid place-items-center text-center cursor-pointer hover:bg-gray-50 transition"
                            @keydown.enter.prevent="$refs.thumb.click()"
                        >
                            <template x-if="preview">
                                <img :src="preview" alt="Preview Thumbnail" class="w-full h-full object-contain rounded-xl" />
                            </template>
                            <template x-if="!preview">
                                <div>
                                    <i class="fa-solid fa-image text-4xl text-gray-400"></i>
                                    <p class="mt-2 text-sm text-gray-500">Tambahkan foto</p>
                                </div>
                            </template>
                            <input
                                x-ref="thumb"
                                type="file"
                                name="thumbnail"
                                id="thumbnail"
                                accept="image/*"
                                class="hidden"
                                @change="if($event.target.files.length){let r=new FileReader();r.onload=e=>preview=e.target.result;r.readAsDataURL($event.target.files[0]);}"
                                aria-invalid="{{ $errors->has('thumbnail') ? 'true' : 'false' }}"
                            >
                        </label>
                        @error('thumbnail') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>
                </section>

                {{-- JUDUL --}}
                <section class="bg-white rounded-2xl shadow-lg p-6">
                    <label for="judul" class="block font-semibold text-gray-800 mb-1">Judul Artikel</label>
                    <input
                        type="text"
                        id="judul"
                        name="judul"
                        value="{{ old('judul') }}"
                        placeholder="Masukkan Judul"
                        required
                        aria-invalid="{{ $errors->has('judul') ? 'true' : 'false' }}"
                        class="w-full rounded-xl border bg-gray-50 focus:outline-none focus:ring-2 {{ $ctrlClass('judul') }}"
                    >
                    @error('judul') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </section>

                {{-- KATEGORI & SLUG --}}
                <section class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <label for="kategori_pengetahuan_id" class="block font-semibold text-gray-800 mb-1">Kategori</label>
                        <select
                            name="kategori_pengetahuan_id"
                            id="kategori_pengetahuan_id"
                            required
                            aria-invalid="{{ $errors->has('kategori_pengetahuan_id') ? 'true' : 'false' }}"
                            class="w-full rounded-xl border bg-gray-50 focus:outline-none focus:ring-2 {{ $ctrlClass('kategori_pengetahuan_id') }}"
                        >
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_pengetahuan_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategoripengetahuan }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_pengetahuan_id') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <label for="slug" class="block font-semibold text-gray-800 mb-1">Slug</label>
                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            value="{{ old('slug') }}"
                            placeholder="Masukkan Slug"
                            required
                            aria-invalid="{{ $errors->has('slug') ? 'true' : 'false' }}"
                            class="w-full rounded-xl border bg-gray-50 focus:outline-none focus:ring-2 {{ $ctrlClass('slug') }}"
                        >
                        @error('slug') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>
                </section>

                {{-- ISI ARTIKEL --}}
                <section class="bg-white rounded-2xl shadow-lg p-6">
                    <label for="isi" class="block font-semibold text-gray-800 mb-2">Isi Artikel</label>
                    <textarea
                        id="isi"
                        name="isi"
                        rows="10"
                        placeholder="Masukkan isi artikel"
                        aria-invalid="{{ $errors->has('isi') ? 'true' : 'false' }}"
                        class="w-full rounded-xl border bg-gray-50 focus:outline-none focus:ring-2 {{ $ctrlClass('isi') }}"
                    >{{ old('isi') }}</textarea>
                    @error('isi') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </section>

                {{-- FILE DOKUMEN --}}
                <section class="bg-white rounded-2xl shadow-lg p-6">
                    <label for="filedok" class="block font-semibold text-gray-800 mb-2">File Dokumen</label>
                    <div x-data="{ fileName: null }">
                        <label
                            for="filedok"
                            role="button"
                            tabindex="0"
                            class="w-full h-32 border-2 border-dashed {{ $errors->has('filedok') ? 'border-red-400' : 'border-gray-300' }} rounded-xl grid place-items-center text-center cursor-pointer hover:bg-gray-50 transition"
                            @keydown.enter.prevent="$refs.fd.click()"
                        >
                            <i class="fa-solid fa-file-arrow-up text-4xl text-gray-400"></i>
                            <span class="mt-2 text-sm text-gray-500" x-show="!fileName">Tambah dokumen</span>
                            <span class="mt-2 text-sm text-gray-700 font-semibold" x-text="fileName" x-show="fileName"></span>
                            <input
                                x-ref="fd"
                                type="file"
                                name="filedok"
                                id="filedok"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar"
                                class="hidden"
                                @change="fileName = $event.target.files.length ? $event.target.files[0].name : null"
                                aria-invalid="{{ $errors->has('filedok') ? 'true' : 'false' }}"
                            >
                        </label>
                        @error('filedok') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>
                </section>
            </section>

            {{-- KOLOM KANAN (SIDEBAR) --}}
            <aside class="xl:col-span-4 flex flex-col gap-6">
                <section class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 grid place-items-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}</p>
                </section>

                <nav class="flex flex-col md:flex-row items-center gap-4">
                    <button
                        id="btn-create-artikel"
                        type="button"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base"
                    >
                        <i class="fa-solid fa-save"></i>
                        <span>Tambah</span>
                    </button>
                    <a
                        href="{{ url()->previous() }}"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base"
                    >
                        <i class="fa-solid fa-times"></i>
                        <span>Batalkan</span>
                    </a>
                </nav>
            </aside>
        </form>
    </div>

    {{-- FOOTER --}}
    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik" class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>

    {{-- TinyMCE --}}
    <script src="https://cdn.tiny.cloud/1/5tsdsuoydzm2f0tjnkrffxszmoas3as1xlmcg5ujs82or4wz/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#isi',
            height: 300,
            plugins: 'lists link image preview',
            toolbar: 'undo redo | formatselect | bold italic underline | bullist numlist | link image | preview',
            menubar: false,
        });

        // Generate slug otomatis
        document.getElementById('judul').addEventListener('keyup', function () {
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script>
        document.getElementById('btn-create-artikel').addEventListener('click', function () {
            Swal.fire({
                title: 'Apakah Anda Yakin',
                html: '<span class="font-semibold">perubahan akan disimpan</span>',
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl p-8',
                    confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-10 py-2 rounded-lg text-base mr-2',
                    cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-10 py-2 rounded-lg text-base',
                    actions: 'flex justify-center gap-4',
                },
                buttonsStyling: false,
            }).then((r) => r.isConfirmed && document.getElementById('form-tambah-artikel').submit());
        });
    </script>
</x-app-layout>
