@php
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

$carbon = Carbon::parse($dokumen->created_at)->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');

$ext = strtolower(pathinfo($dokumen->path_dokumen, PATHINFO_EXTENSION));
$namaFile = $dokumen->path_dokumen ? basename($dokumen->path_dokumen) : null;

/** controller tidak kirim viewers → aman: 0 */
$viewerCount = isset($viewers) ? $viewers->count() : 0;
@endphp

@section('title', 'Detail Dokumen Kepala Bagian')

<style>
[x-cloak] {
    display: none !important
}
</style>

<x-app-layout>
    <div class="min-h-screen bg-[#eaf5ff] pb-12">

        {{-- HEADER --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    {{-- Search (dummy agar konsisten header) --}}
                    <div class="relative w-full md:w-72">
                        <input placeholder="Cari nama dokumen..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm" />
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>

                    {{-- Profile dropdown (klik berfungsi) --}}
                    <div x-data="{open:false}" class="relative">
                        <button type="button" @click="open=!open" @keydown.escape.window="open=false"
                            class="w-10 h-10 grid place-items-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            aria-haspopup="true" :aria-expanded="open">
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
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
                                    Out</button>
                            </form>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        {{-- MAIN --}}
        <main class="p-4 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">

            {{-- KONTEN DOKUMEN --}}
            <section class="xl:col-span-8">
                <article class="bg-white rounded-2xl shadow-lg p-5 md:p-8">
                    {{-- Judul --}}
                    <h1 class="text-xl md:text-2xl font-bold text-gray-800">{{ $dokumen->nama_dokumen }}</h1>

                    {{-- Meta: kategori & uploader --}}
                    <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-600">
                        <span>Kategori:
                            <span
                                class="font-medium text-blue-700">{{ $dokumen->kategoriDokumen->nama_kategoridokumen ?? '-' }}</span>
                        </span>
                        <span class="hidden sm:inline text-gray-400">|</span>
                        <span>Uploader: <span
                                class="font-medium">{{ $dokumen->user->name ?? 'Tidak diketahui' }}</span></span>
                    </div>

                    {{-- Bar tanggal + viewer + bagikan --}}
                    <div class="mt-4 flex items-end justify-between gap-6">
                        <span
                            class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($dokumen->created_at)->translatedFormat('d/m/Y') }}</span>

                        <div class="flex flex-col items-end gap-2">
                            <div class="flex items-center gap-1 text-gray-500 text-sm">
                                <span class="inline-flex items-center gap-1">
                                    <i class="fas fa-eye"></i> {{ number_format($dokumen->views_count) }}
                                </span>
                            </div>
                            @if($dokumen->pengguna_id == auth()->id())
                            <button id="btn-bagikan"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#2B6CB0] hover:bg-[#1f4e86] text-white font-semibold shadow transition text-sm">
                                <i class="fas fa-share-alt"></i> Bagikan Dokumen
                            </button>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 border-b-2 md:border-b-[3px] border-gray-300"></div>

                    {{-- PREVIEW + DESKRIPSI --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

                        {{-- PREVIEW --}}
                        <section>
                            @if ($dokumen->path_dokumen && Storage::disk('public')->exists($dokumen->path_dokumen))
                            @if ($ext === 'pdf')
                            <iframe src="{{ asset('storage/'.$dokumen->path_dokumen) }}"
                                class="w-full rounded-xl border shadow bg-white"
                                style="min-height:320px; height:380px; max-height:480px; border:0;"></iframe>
                            @elseif(in_array($ext, ['jpg','jpeg','png','webp','gif','bmp']))
                            <img src="{{ asset('storage/'.$dokumen->path_dokumen) }}" alt="Dokumen"
                                class="w-full h-80 object-contain rounded-xl border shadow bg-white" />
                            @else
                            <div
                                class="flex flex-col items-center justify-center h-72 bg-gray-50 rounded-xl border shadow">
                                <i class="fa-solid fa-file text-6xl text-gray-400 mb-2"></i>
                                <div class="text-xs text-gray-500">{{ $namaFile }}</div>
                            </div>
                            @endif

                            <div class="mt-3 flex flex-wrap gap-3">
                                <a href="{{ asset('storage/'.$dokumen->path_dokumen) }}" target="_blank" download
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm text-sm">
                                    <i class="fa-solid fa-download"></i> Download Dokumen
                                </a>
                                <button type="button" id="btn-copy-link"
                                    data-link="{{ asset('storage/'.$dokumen->path_dokumen) }}"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold shadow-sm text-sm">
                                    <i class="fa-solid fa-link"></i> Salin Link
                                </button>
                            </div>
                            @else
                            <p class="italic text-gray-400">Dokumen tidak ditemukan.</p>
                            @endif
                        </section>

                        {{-- DESKRIPSI --}}
                        <section>
                            <h3 class="font-semibold text-gray-800 mb-2">Deskripsi</h3>
                            <div class="leading-relaxed text-gray-800">{!! nl2br(e($dokumen->deskripsi)) !!}</div>
                        </section>
                    </div>
                </article>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 flex flex-col gap-8 mt-8 xl:mt-0">
                <section
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 text-center grid place-items-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'Kepala Bagian' }}
                    </p>
                </section>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('kepalabagian.manajemendokumen.edit', $dokumen->id) }}"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-[#2B6CB0] hover:bg-[#1f4e86] text-white font-semibold shadow-sm transition">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Dokumen
                    </a>
                    <a href="{{ route('kepalabagian.manajemendokumen.index') }}"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-semibold shadow-sm transition">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
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

    {{-- SweetAlert2 (untuk Bagikan & Salin link) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.5/dist/sweetalert2.all.min.js"></script>
    <script>
    // Bagikan dokumen (konfirmasi)
    const btnShare = document.getElementById('btn-bagikan');
    if (btnShare) {
        btnShare.addEventListener('click', () => {
            Swal.fire({
                title: 'Bagikan Dokumen?',
                html: 'Anda akan diarahkan ke halaman <b>Bagikan Dokumen</b>.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl p-8',
                    confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2 rounded-lg mr-2',
                    cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-8 py-2 rounded-lg',
                    actions: 'flex justify-center gap-3'
                },
                buttonsStyling: false
            }).then(r => {
                if (r.isConfirmed) window.location.href = @json(route('aksesdokumen.bagikan', $dokumen -
                    >
                    id));
            });
        });
    }

    // Salin tautan dokumen
    const btnCopy = document.getElementById('btn-copy-link');
    if (btnCopy) {
        btnCopy.addEventListener('click', async () => {
            const link = btnCopy.dataset.link;
            try {
                await navigator.clipboard.writeText(link);
                Swal.fire({
                    icon: 'success',
                    title: 'Tersalin!',
                    text: 'Link dokumen berhasil disalin.',
                    timer: 1700,
                    position: 'top',
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-xl shadow-md px-6 py-4'
                    }
                });
            } catch {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal menyalin',
                    text: 'Salin manual: ' + link,
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'rounded-xl px-6 py-5',
                        confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg'
                    },
                    buttonsStyling: false
                });
            }
        });
    }
    </script>
</x-app-layout>