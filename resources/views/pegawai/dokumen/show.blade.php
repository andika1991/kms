@php
use Carbon\Carbon;

$carbon = Carbon::parse($dokumen->created_at)->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');

$ext = strtolower(pathinfo($dokumen->path_dokumen, PATHINFO_EXTENSION));
$namaFile = $dokumen->path_dokumen ? basename($dokumen->path_dokumen) : null;

/**
* Opsional: daftar user yang sudah melihat dokumen.
* Jika controller tidak mengirim $viewers, jadikan koleksi kosong agar aman dipanggil.
*/
$viewers = $viewers ?? collect();
@endphp

@section('title', 'Lihat Dokumen Pegawai')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] pb-12">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search (dummy) --}}
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari nama dokumen..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>

                    {{-- Profile --}}
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

        {{-- MAIN --}}
        <div class="p-4 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- KONTEN DOKUMEN --}}
            <section class="xl:col-span-8 w-full">
                <div class="bg-white rounded-2xl shadow-lg p-5 md:p-8">
                    {{-- Judul --}}
                    <h1 class="text-xl md:text-2xl font-bold text-gray-800">
                        {{ $dokumen->nama_dokumen }}
                    </h1>

                    {{-- Meta: Kategori | Uploader --}}
                    <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-600">
                        <div>
                            <span class="text-gray-600">Kategori:</span>
                            <span class="font-medium text-blue-700">
                                {{ $dokumen->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                            </span>
                        </div>
                        <span class="hidden sm:inline text-gray-400">|</span>
                        <div>
                            <span class="text-gray-600">Uploader:</span>
                            <span class="font-medium">
                                {{ $dokumen->user->name ?? 'Tidak diketahui' }}
                            </span>
                        </div>
                    </div>

                    {{-- Bar: Tanggal (kiri) + Viewer count (atas) & Bagikan (kanan) --}}
                    <div class="mt-4 flex items-end justify-between gap-6">
                        {{-- Tanggal kiri --}}
                        <div class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($dokumen->created_at)->translatedFormat('d/m/Y') }}
                        </div>

                        {{-- Kanan: jumlah viewer (angka + ikon) di atas tombol bagikan --}}
                        <div class="flex flex-col items-end gap-2">
                            <div class="flex items-center gap-1 text-gray-500 text-sm">
                                <span class="inline-flex items-center gap-1">
                                    <i class="fas fa-eye"></i> {{ number_format($dokumen->views_count) }}
                                </span>
                            </div>

                            @if($dokumen->pengguna_id == auth()->id())
                            <button id="btn-bagikan"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#2B6CB0] hover:bg-[#1f4e86] text-white font-semibold shadow transition text-sm">
                                <i class="fas fa-share-alt"></i>
                                Bagikan Dokumen
                            </button>
                            @endif
                        </div>
                    </div>

                    {{-- Garis pembatas tebal --}}
                    <div class="mt-4 border-b-2 md:border-b-[3px] border-gray-300"></div>

                    {{-- PREVIEW + DESKRIPSI (dua kolom) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        {{-- PREVIEW --}}
                        <div>
                            @if ($dokumen->path_dokumen &&
                            \Illuminate\Support\Facades\Storage::disk('public')->exists($dokumen->path_dokumen))
                            @if ($ext === 'pdf')
                            <iframe src="{{ asset('storage/'.$dokumen->path_dokumen) }}" width="100%" height="380"
                                class="rounded-xl border shadow bg-white"
                                style="min-height:320px; max-height:480px;"></iframe>
                            @elseif(in_array($ext, ['jpg','jpeg','png','webp','gif','bmp']))
                            <img src="{{ asset('storage/'.$dokumen->path_dokumen) }}" alt="Dokumen Gambar"
                                class="w-full h-80 object-contain rounded-xl border shadow bg-white" />
                            @else
                            <div
                                class="flex flex-col items-center justify-center h-72 bg-gray-50 rounded-xl border shadow">
                                <i class="fa-solid fa-file text-6xl text-gray-400 mb-2"></i>
                                <div class="text-xs text-gray-500">{{ $namaFile }}</div>
                            </div>
                            @endif

                            {{-- Aksi bawah preview --}}
                            <div class="mt-3 flex flex-wrap gap-3">
                                <a href="{{ asset('storage/'.$dokumen->path_dokumen) }}" target="_blank" download
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition text-sm">
                                    <i class="fa-solid fa-download"></i>
                                    Download Dokumen
                                </a>

                                <button type="button" id="btn-copy-link"
                                    data-link="{{ asset('storage/'.$dokumen->path_dokumen) }}"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold shadow-sm transition text-sm">
                                    <i class="fa-solid fa-link"></i>
                                    Salin Link
                                </button>
                            </div>
                            @else
                            <p class="italic text-gray-400">Dokumen tidak ditemukan.</p>
                            @endif
                        </div>

                        {{-- DESKRIPSI (polos, sejajar dengan judul "Deskripsi") --}}
                        <div>
                            <div class="font-semibold text-gray-800 mb-2">Deskripsi</div>
                            <div class="leading-relaxed text-gray-800">
                                {!! nl2br(e($dokumen->deskripsi)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8 mt-8 xl:mt-0">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight mb-2">{{ Auth::user()->role->nama_role ?? 'Pegawai' }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('pegawai.manajemendokumen.edit', $dokumen->id) }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-[#2B6CB0] hover:bg-[#1f4e86] text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit Dokumen</span>
                    </a>
                    <a href="{{ route('pegawai.manajemendokumen.index') }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali ke Daftar</span>
                    </a>
                </div>
            </aside>
        </div>

        <x-slot name="footer">
            <footer class="bg-[#2b6cb0] py-4 mt-8">
                <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                    <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                        class="h-10 object-contain">
                </div>
            </footer>
        </x-slot>
    </div>

    {{-- SweetAlert2 (terbaru) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
    <script>
    // Konfirmasi "Bagikan Dokumen"
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
            }).then((r) => {
                if (r.isConfirmed) {
                    window.location.href = @json(route('aksesdokumen.bagikan', $dokumen - > id));
                }
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
                    text: 'Link dokumen berhasil disalin ke clipboard.',
                    timer: 1800,
                    position: 'top',
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-xl shadow-md px-6 py-4'
                    }
                });
            } catch (e) {
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