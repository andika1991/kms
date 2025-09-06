@php
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');

$filePath = $dokumen->path_dokumen ? ('storage/'.$dokumen->path_dokumen) : null;
$ext = $dokumen->path_dokumen ? strtolower(pathinfo($dokumen->path_dokumen, PATHINFO_EXTENSION)) : '';
$isImage = in_array($ext, ['jpg','jpeg','png','gif','bmp','webp']);
@endphp

@section('title', 'Edit Dokumen Kepala Bagian')

<style>
[x-cloak] {
    display: none !important
}
</style>

<x-app-layout>
    <div class="min-h-screen bg-[#eaf5ff] pb-10">

        {{-- HEADER (konsisten & sejajar dengan icon profil) --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative w-full md:w-72">
                        <input placeholder="Cari nama dokumen..." class="h-10 w-full rounded-full border-gray-300 bg-white pl-10 pr-4 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>

                    {{-- Profile dropdown --}}
                    <div x-data="{open:false}" class="relative">
                        <button type="button" @click="open=!open" @keydown.escape.window="open=false"
                            class="w-10 h-10 grid place-items-center bg-white rounded-full border border-gray-300
                                       text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition" title="Profile" aria-haspopup="true" :aria-expanded="open">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <nav x-cloak x-show="open" @click.outside="open=false"
                            x-transition.opacity.scale.origin.top.right
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-50">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        {{-- BODY GRID --}}
        <main class="p-4 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">

            {{-- KARTU UTAMA: PREVIEW + FORM (1 FORM UTUH, logika tetap) --}}
            <form id="form-edit-kb" method="POST"
                action="{{ route('kepalabagian.manajemendokumen.update', $dokumen->id) }}" enctype="multipart/form-data"
                class="xl:col-span-8 bg-white rounded-2xl shadow-lg p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                @csrf
                @method('PUT')

                {{-- NOTIF VALIDASI --}}
                @if ($errors->any())
                <div class="md:col-span-2 px-4 py-3 rounded-lg bg-red-50 text-red-700 border border-red-200 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
                @endif

                {{-- PREVIEW + GANTI FILE (kiri) --}}
                <section>
                    <div class="rounded-2xl border overflow-hidden bg-white">
                        @if($filePath && Storage::disk('public')->exists($dokumen->path_dokumen))
                        @if($ext === 'pdf')
                        <iframe src="{{ asset($filePath) }}" class="w-full h-[440px] md:h-[520px]"
                            style="border:0;"></iframe>
                        @elseif($isImage)
                        <img src="{{ asset($filePath) }}" alt="Preview Dokumen"
                            class="w-full h-[440px] md:h-[520px] object-contain bg-gray-50">
                        @else
                        <div class="h-[320px] md:h-[360px] grid place-items-center bg-gray-50">
                            <i class="fa-solid fa-file text-6xl text-gray-400"></i>
                        </div>
                        <div class="px-4 py-3 text-xs text-gray-600 border-t">
                            File saat ini:
                            <a href="{{ asset($filePath) }}" target="_blank" class="text-blue-600 hover:underline">
                                Lihat Dokumen
                            </a>
                        </div>
                        @endif
                        @else
                        <div class="h-[320px] md:h-[360px] grid place-items-center bg-gray-50">
                            <span class="text-gray-400 text-sm italic">Preview tidak tersedia.</span>
                        </div>
                        @endif
                    </div>

                    <button type="button" id="btn-ganti-file" class="mt-4 inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl
                                   bg-[#2B6CB0] hover:bg-[#1f4e86] text-white font-semibold shadow-sm transition">
                        <i class="fa-solid fa-rotate"></i> Ganti Dokumen
                    </button>
                    @if($filePath && Storage::disk('public')->exists($dokumen->path_dokumen))
                    <p class="mt-2 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengganti file.</p>
                    @endif

                    {{-- INPUT FILE ASLI (dipicu tombol di atas) --}}
                    <input type="file" name="path_dokumen" id="path_dokumen" class="hidden"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">
                </section>

                {{-- FORM FIELD (kanan) --}}
                <section class="flex flex-col gap-5">
                    <label class="block">
                        <span class="font-semibold text-gray-800">Judul</span>
                        <input name="nama_dokumen" value="{{ old('nama_dokumen', $dokumen->nama_dokumen) }}"
                            class="mt-1 w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500"
                            required>
                    </label>

                    <label class="block">
                        <span class="font-semibold text-gray-800">Kategori</span>
                        <select id="kategoriSelect" name="kategori_dokumen_id"
                            class="mt-1 w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="">Pilih Kategori</option>
                            @forelse($kategori as $kat)
                            <option value="{{ $kat->id }}" data-nama="{{ strtolower($kat->nama_kategoridokumen) }}"
                                {{ (old('kategori_dokumen_id', $dokumen->kategori_dokumen_id) == $kat->id) ? 'selected' : '' }}>
                                {{ $kat->nama_kategoridokumen }}
                                @if($kat->subbidang) — {{ $kat->subbidang->nama }} @endif
                            </option>
                            @empty
                            <option disabled>Tidak ada kategori tersedia</option>
                            @endforelse
                        </select>
                    </label>

                    <label class="block">
                        <span class="font-semibold text-gray-800">Deskripsi</span>
                        <textarea name="deskripsi" rows="8"
                            class="mt-1 w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 resize-y"
                            required>{{ old('deskripsi', $dokumen->deskripsi) }}</textarea>
                    </label>

                    {{-- Field Kunci Rahasia (hanya jika kategori Rahasia) --}}
                    <label id="encrypted-key-field"
                        class="block {{ strtolower(optional($dokumen->kategoriDokumen)->nama_kategoridokumen) === 'rahasia' ? '' : 'hidden' }}">
                        <span class="font-semibold text-gray-800">Kunci Rahasia / Enkripsi</span>
                        <input name="encrypted_key" value="{{ old('encrypted_key', $dokumen->encrypted_key) }}"
                            class="mt-1 w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan kunci dokumen">
                    </label>
                </section>
            </form>

            {{-- SIDEBAR AKSI (peran + tombol) --}}
            <aside class="xl:col-span-4 flex flex-col gap-6">
                <section
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 text-center grid place-items-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'Kepala Bagian' }}
                    </p>
                    <p class="text-xs opacity-90 mt-1">Perbarui judul, kategori, deskripsi, atau ganti file dokumen.</p>
                </section>

                <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-1 gap-4">
                    <button id="btn-simpan" type="button"
                        class="px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm">
                        Simpan
                    </button>
                    <button id="btn-batal" type="button"
                        class="px-5 py-2.5 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm">
                        Batalkan
                    </button>
                </section>
            </aside>
        </main>

        {{-- FOOTER --}}
        <x-slot name="footer">
            <footer class="bg-[#2b6cb0] py-4 mt-8">
                <div class="max-w-7xl mx-auto px-4 grid place-items-center">
                    <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                        class="h-10 object-contain">
                </div>
            </footer>
        </x-slot>
    </div>

    {{-- SweetAlert2 (terbaru) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.5/dist/sweetalert2.all.min.js"></script>
    <script>
    // Trigger input file
    document.getElementById('btn-ganti-file')?.addEventListener('click', () => {
        document.getElementById('path_dokumen')?.click();
    });

    // Toggle field kunci saat kategori = Rahasia
    document.addEventListener('DOMContentLoaded', () => {
        const sel = document.getElementById('kategoriSelect');
        const key = document.getElementById('encrypted-key-field');

        function toggleKey() {
            const opt = sel.options[sel.selectedIndex];
            const nama = (opt?.getAttribute('data-nama') || '').toLowerCase();
            nama === 'rahasia' ? key.classList.remove('hidden') : key.classList.add('hidden');
        }
        sel?.addEventListener('change', toggleKey);
        toggleKey();
    });

    // Modal SIMPAN (sesuai Figma: icon sukses, Ya/Hijau – Tidak/Merah)
    document.getElementById('btn-simpan')?.addEventListener('click', () => {
        Swal.fire({
            title: 'Apakah Anda Yakin',
            html: '<span class="text-sm">perubahan akan disimpan</span>',
            iconHtml: '✅',
            customClass: {
                icon: 'text-3xl',
                popup: 'rounded-2xl p-8',
                confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-10 py-2 rounded-lg mr-2',
                cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-10 py-2 rounded-lg',
                actions: 'flex justify-center gap-4'
            },
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            buttonsStyling: false,
            reverseButtons: true
        }).then(r => {
            if (r.isConfirmed) document.getElementById('form-edit-kb')?.submit();
        });
    });

    // Modal BATAL (sesuai Figma: icon warning, Batal/Yakin biru)
    document.getElementById('btn-batal')?.addEventListener('click', () => {
        Swal.fire({
            title: 'Apakah Anda Yakin',
            html: '<span class="text-sm">perubahan tidak akan disimpan</span>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yakin',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl p-8',
                confirmButton: 'bg-[#2B6CB0] hover:bg-[#1f4e86] text-white font-semibold px-10 py-2 rounded-lg ml-2',
                cancelButton: 'bg-[#2B6CB0] hover:bg-[#1f4e86] text-white font-semibold px-10 py-2 rounded-lg',
                actions: 'flex justify-center gap-4'
            },
            buttonsStyling: false
        }).then(r => {
            if (r.isConfirmed) window.location.href =
                "{{ route('kepalabagian.manajemendokumen.index') }}";
        });
    });
    </script>
</x-app-layout>