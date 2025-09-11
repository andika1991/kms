@php
use Carbon\Carbon;
$carbon = Carbon::parse($kegiatan->created_at)->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Detail Kegiatan Magang')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] pb-12">
        {{-- HEADER (match pegawai) --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Detail Kegiatan</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- (opsional) Search ke index agar tidak ubah logika --}}
                    <form action="{{ route('magang.kegiatan.index') }}" method="GET"
                        class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" name="search" placeholder="Cari kegiatan..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition"
                            value="{{ request('search') }}">
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
                            style="display:none">
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
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- MAIN SECTION --}}
            <section class="xl:col-span-8 w-full">
                <div class="bg-white rounded-2xl shadow-lg p-6 md:p-10 flex flex-col gap-6">
                    {{-- Thumbnail / Cover (foto pertama) --}}
                    @php $thumb = optional($kegiatan->fotokegiatan)->first(); @endphp
                    @if ($thumb)
                    <img src="{{ asset('storage/'.$thumb->path_foto) }}" alt="{{ $kegiatan->nama_kegiatan }}"
                        class="w-full h-56 md:h-72 object-cover rounded-xl border mb-4" />
                    @else
                    <img src="{{ asset('assets/img/empty-photo.png') }}" alt="Tidak ada gambar"
                        class="w-full h-56 md:h-72 object-cover rounded-xl border opacity-60 mb-2">
                    @endif

                    {{-- Judul --}}
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 leading-tight">
                        {{ $kegiatan->nama_kegiatan }}
                    </h1>

                    {{-- Info kecil --}}
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500">
                        <div>
                            <span>Kategori:</span>
                            <span
                                class="font-semibold text-blue-600">{{ ucfirst($kegiatan->kategori_kegiatan) ?? '-' }}</span>
                        </div>
                        <span class="hidden sm:inline">|</span>
                        <div>
                            <span>Tanggal:</span>
                            <span>{{ $tanggal }}</span>
                        </div>
                    </div>

                    <hr class="border-gray-300 my-3">

                    {{-- Deskripsi --}}
                    <div class="text-gray-800 text-base leading-relaxed">
                        {!! $kegiatan->deskripsi_kegiatan !!}
                    </div>

                    <hr class="border-gray-200 my-4">

                    {{-- Dokumentasi (foto ke-2 dst) --}}
                    @if ($kegiatan->fotokegiatan && $kegiatan->fotokegiatan->count() > 1)
                    <label class="font-bold text-gray-800 mb-2 block text-center">Dokumentasi Kegiatan</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach ($kegiatan->fotokegiatan->slice(1) as $foto)
                        <img src="{{ asset('storage/'.$foto->path_foto) }}"
                            class="w-full h-24 sm:h-28 md:h-32 object-cover rounded-md border"
                            alt="Dokumentasi Kegiatan">
                        @endforeach
                    </div>
                    @endif
                </div>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8 mt-8 xl:mt-0">
                {{-- Role Card --}}
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <p class="font-bold text-lg leading-tight">
                        {{ Auth::user()->role->nama_role ?? 'Magang' }}
                    </p>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-col gap-4">
                    <a href="{{ route('magang.kegiatan.edit', $kegiatan->id) }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-blue-700 hover:bg-blue-900 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit Kegiatan</span>
                    </a>
                    <button id="btn-hapus-kegiatan"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-red-600 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-trash"></i>
                        <span>Hapus Kegiatan</span>
                    </button>

                    {{-- Form hapus (submit via SweetAlert) --}}
                    <form id="delete-kegiatan-form" action="{{ route('magang.kegiatan.destroy', $kegiatan->id) }}"
                        method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>

                {{-- Tips Card --}}
                <div class="bg-white rounded-2xl shadow-lg p-7">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Tips Produktif Magang</h3>
                    <ul class="list-disc list-inside text-sm text-gray-600 leading-relaxed space-y-1">
                        <li>Update laporan kegiatan secara rutin.</li>
                        <li>Berkolaborasi aktif dengan rekan magang.</li>
                        <li>Konsultasikan kendala ke pembimbing.</li>
                        <li>Jangan lupa dokumentasi kegiatan.</li>
                    </ul>
                </div>
            </aside>
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
    </div>

    {{-- SweetAlert2 (latest) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>

    {{-- Toast sukses / deleted (opsional) --}}
    @if (session('success'))
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: @json(session('success')),
            showConfirmButton: false,
            background: '#f0fff4',
            customClass: {
                popup: 'rounded-xl shadow-md px-8 py-5',
                title: 'font-bold text-base md:text-lg text-green-800',
                icon: 'text-green-500'
            },
            timer: 2200
        });
    });
    </script>
    @endif

    @if (session('deleted'))
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
            position: 'top',
            icon: 'error',
            title: @json(session('deleted')),
            showConfirmButton: false,
            background: '#fff5f5',
            customClass: {
                popup: 'rounded-xl shadow-md px-8 py-5 border border-red-200',
                title: 'font-bold text-base md:text-lg text-red-800',
                icon: 'text-red-600'
            },
            timer: 2400
        });
    });
    </script>
    @endif

    {{-- Konfirmasi hapus (danger red confirm + blue cancel) --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const btnHapus = document.getElementById('btn-hapus-kegiatan');
        const formDel = document.getElementById('delete-kegiatan-form');

        btnHapus.addEventListener('click', (e) => {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Apakah Anda yakin?',
                html: '<span class="text-gray-600 text-base">Data akan dihapus</span>',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batalkan',
                reverseButtons: false,
                focusCancel: true,
                customClass: {
                    popup: 'rounded-2xl px-8 pt-5 pb-6',
                    icon: 'mt-3 mb-2',
                    title: 'mb-1',
                    htmlContainer: 'mb-3',
                    confirmButton: 'bg-[#D32F2F] hover:bg-[#B71C1C] text-white font-semibold px-10 py-2 rounded-lg text-base mr-2',
                    cancelButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-10 py-2 rounded-lg text-base',
                    actions: 'flex justify-center gap-4',
                },
                buttonsStyling: false,
            }).then((res) => {
                if (res.isConfirmed) formDel.submit();
            });
        });
    });
    </script>
</x-app-layout>