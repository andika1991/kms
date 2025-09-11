@php
use Carbon\Carbon;

$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');

$path = $manajemendokuman->path_dokumen ? asset('storage/'.$manajemendokuman->path_dokumen) : null;
$ext = strtolower(pathinfo($manajemendokuman->path_dokumen ?? '', PATHINFO_EXTENSION));
$isImg = in_array($ext, ['jpg','jpeg','png','gif','bmp','webp']);
@endphp

@section('title', 'Edit Dokumen Magang')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] pb-10">

        {{-- HEADER (seragam dengan pegawai/Figma) --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <form method="GET" action="{{ route('magang.manajemendokumen.index') }}"
                        class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input name="search" value="{{ request('search') }}" placeholder="Cari nama dokumen..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i
                                class="fa fa-search"></i></span>
                    </form>

                    <div x-data="{open:false}" class="relative">
                        <button @click="open=!open" title="Profile"
                            class="w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open=false" x-transition style="display:none"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20">
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
        </header>

        {{-- BODY --}}
        <main class="px-4 md:px-8 grid grid-cols-1 xl:grid-cols-12 gap-8 mt-6">
            {{-- FORM + PREVIEW --}}
            <section class="xl:col-span-8 bg-white rounded-2xl shadow-lg p-6 md:p-8">
                @if($errors->any())
                <div class="mb-6 px-4 py-3 rounded-lg bg-red-50 text-red-700 border border-red-200 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    {{-- PREVIEW --}}
                    <div>
                        <div class="rounded-2xl border bg-white overflow-hidden">
                            @if($path)
                            @if($ext==='pdf')
                            <iframe src="{{ $path }}" class="w-full h-[440px] md:h-[520px]" style="border:0;"></iframe>
                            @elseif($isImg)
                            <img src="{{ $path }}" alt="Preview Dokumen"
                                class="w-full h-[440px] md:h-[520px] object-contain bg-gray-50">
                            @else
                            <div class="h-[320px] md:h-[360px] flex items-center justify-center bg-gray-50">
                                <i class="fa-solid fa-file text-6xl text-gray-400"></i>
                            </div>
                            <div class="px-4 py-3 text-xs text-gray-600 border-t">
                                File saat ini: <a href="{{ $path }}" target="_blank"
                                    class="text-blue-600 hover:underline">Lihat Dokumen</a>
                            </div>
                            @endif
                            @else
                            <div class="h-[320px] md:h-[360px] flex items-center justify-center bg-gray-50">
                                <span class="text-gray-400 text-sm italic">Preview tidak tersedia.</span>
                            </div>
                            @endif
                        </div>

                        <button type="button" id="btn-ganti-file"
                            class="mt-4 inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#2B6CB0] hover:bg-[#1f4e86] text-white font-semibold shadow-sm transition">
                            <i class="fa-solid fa-rotate"></i> Ganti Dokumen
                        </button>
                        @if($path)
                        <span class="ml-3 text-sm text-gray-500">File saat ini: <a href="{{ $path }}" target="_blank"
                                class="text-blue-600 hover:underline">Lihat</a></span>
                        @endif
                        <p class="text-xs text-gray-500 mt-1">Kosongkan bila tidak ingin mengganti file.</p>
                    </div>

                    {{-- FORM (struktur & name tetap) --}}
                    <form id="form-edit-dokumen" method="POST"
                        action="{{ route('magang.manajemendokumen.update', $manajemendokuman->id) }}"
                        enctype="multipart/form-data" class="flex flex-col gap-5">
                        @csrf
                        @method('PUT')

                        <label class="block">
                            <span class="block font-semibold text-gray-800 mb-1">Judul</span>
                            <input name="nama_dokumen"
                                value="{{ old('nama_dokumen', $manajemendokuman->nama_dokumen) }}"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500"
                                required>
                        </label>

                        <label class="block">
                            <span class="block font-semibold text-gray-800 mb-1">Kategori</span>
                            <select id="kategoriSelect" name="kategori_dokumen_id"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" data-nama="{{ strtolower($kat->nama_kategoridokumen) }}"
                                    {{ old('kategori_dokumen_id', $manajemendokuman->kategori_dokumen_id)==$kat->id ? 'selected':'' }}>
                                    {{ $kat->nama_kategoridokumen }} @if($kat->subbidang) — {{ $kat->subbidang->nama }}
                                    @endif
                                </option>
                                @endforeach
                            </select>
                        </label>

                        {{-- FILE INPUT (disembunyikan, dipicu tombol ganti) --}}
                        <input type="file" id="path_dokumen" name="path_dokumen" class="hidden"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">

                        <label class="block">
                            <span class="block font-semibold text-gray-800 mb-1">Deskripsi</span>
                            <textarea name="deskripsi" rows="7"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 resize-y"
                                required>{{ old('deskripsi', $manajemendokuman->deskripsi) }}</textarea>
                        </label>

                        <label id="encrypted-key-field"
                            class="block {{ strtolower(optional($manajemendokuman->kategoriDokumen)->nama_kategoridokumen)==='rahasia' ? '' : 'hidden' }}">
                            <span class="block font-semibold text-gray-800 mb-1">Kunci Rahasia / Encrypted Key</span>
                            <input name="encrypted_key"
                                value="{{ old('encrypted_key', $manajemendokuman->encrypted_key) }}"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500">
                        </label>

                        {{-- Tombol submit asli (tetap untuk akses keyboard/enter) --}}
                        <button type="submit" class="hidden">Update</button>
                    </form>
                </div>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 flex flex-col gap-8 mt-8 xl:mt-0">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon"
                        class="h-16 w-16 mx-auto mb-4">
                    <p class="font-bold text-lg mb-1">{{ Auth::user()->role->nama_role ?? 'Magang' }}</p>
                </div>

                <div class="flex flex-col md:flex-row items-center gap-4">
                    <button id="btn-update" type="button"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-floppy-disk"></i><span>Simpan</span>
                    </button>
                    <a id="btn-cancel" href="{{ route('magang.manajemendokumen.index') }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-xmark"></i><span>Batalkan</span>
                    </a>
                </div>
            </aside>
        </main>
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

    {{-- SweetAlert2 (terbaru) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.4/dist/sweetalert2.all.min.js"></script>
    <script>
    // Trigger file chooser
    document.getElementById('btn-ganti-file')?.addEventListener('click', () => {
        document.getElementById('path_dokumen')?.click();
    });

    // Toggle field kunci jika kategori "Rahasia"
    document.addEventListener('DOMContentLoaded', () => {
        const sel = document.getElementById('kategoriSelect');
        const key = document.getElementById('encrypted-key-field');
        const toggle = () => {
            const nama = (sel.options[sel.selectedIndex]?.dataset.nama || '').toLowerCase();
            nama === 'rahasia' ? key.classList.remove('hidden') : key.classList.add('hidden');
        };
        sel?.addEventListener('change', toggle);
        toggle();
    });

    // MODAL SIMPAN (mengikuti Figma: Tidak = merah, Ya = hijau)
    document.getElementById('btn-update')?.addEventListener('click', () => {
        Swal.fire({
            icon: 'success',
            title: 'Apakah Anda Yakin',
            html: '<span class="text-gray-600 text-base">Perubahan akan disimpan</span>',
            showCancelButton: true,
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
            buttonsStyling: false
        }).then(r => {
            if (r.isConfirmed) document.getElementById('form-edit-dokumen')?.submit();
        });
    });

    // MODAL BATAL (mengikuti Figma: dua tombol biru: Batal & Yakin)
    document.getElementById('btn-cancel')?.addEventListener('click', function(e) {
        e.preventDefault();
        const href = this.getAttribute('href');
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
            html: '<div class="text-gray-600 text-lg">perubahan tidak akan disimpan</div>',
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
            focusCancel: true
        }).then(r => {
            if (r.isConfirmed) window.location.href = href;
        });
    });
    </script>
</x-app-layout>