@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Artikel Pengetahuan Kepala Bagian')

{{-- ALERT Sukses --}}
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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

{{-- ALERT Hapus --}}
@if (session('deleted'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        position: 'top',
        icon: 'error',
        title: @json(session('deleted')),
        showConfirmButton: false,
        background: '#fef2f2',
        customClass: {
            popup: 'rounded-xl shadow-md px-8 py-5 border border-red-200',
            title: 'font-bold text-base md:text-lg text-red-800',
            icon: 'text-red-600'
        },
        timer: 2500
    });
});
</script>
@endif

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff]">

        {{-- HEADER --}}
        <header class="bg-[#eaf5ff] border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 md:px-6 py-6 md:py-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-800">Artikel Pengetahuan</h2>
                        <p class="text-slate-500 text-sm">{{ $tanggal }}</p>
                    </div>

                    <div class="flex items-center gap-4 w-full sm:w-auto">
                        {{-- Search (presentasional) --}}
                        <div class="relative flex-1 sm:flex-none sm:w-64">
                            <input type="text" placeholder="Cari artikel pengetahuan…"
                                class="w-full rounded-full border border-slate-300 bg-white pl-10 pr-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 shadow-sm" />
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>

                        {{-- Dropdown Profile --}}
                        <div x-data="{ open:false }" class="relative">
                            <button @click="open=!open"
                                class="w-10 h-10 bg-white border border-slate-300 rounded-full flex items-center justify-center text-slate-600 hover:border-blue-500 hover:text-blue-600 hover:shadow transition"
                                title="Profile">
                                <i class="fa-solid fa-user"></i>
                            </button>

                            <div x-show="open" @click.away="open=false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white border rounded-xl shadow-lg z-20"
                                style="display:none;">
                                <div class="py-1">
                                    <a href="{{ route('profile.edit') }}"
                                        class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Log
                                            Out</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        {{-- BODY --}}
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-6 md:py-8 grid grid-cols-1 xl:grid-cols-12 gap-8">

            {{-- LIST ARTIKEL --}}
            <section class="xl:col-span-8 w-full">
                @if ($artikels->count())
                {{-- <<< 2 KOLUM >>> --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                    @foreach ($artikels as $artikel)
                    <div
                        class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-300 transition overflow-hidden group flex flex-col">
                        <div class="flex flex-col sm:flex-row">
                            {{-- Thumbnail --}}
                            <div class="sm:w-40 md:w-44 lg:w-48 h-44 sm:h-auto bg-slate-100 overflow-hidden">
                                <a href="{{ route('kepalabagian.artikelpengetahuan.show', $artikel->id) }}"
                                    class="block h-full">
                                    @php $thumb = $artikel->thumbnail ? asset('storage/'.$artikel->thumbnail) :
                                    asset('assets/img/artikel-elemen.png'); @endphp
                                    <img src="{{ $thumb }}" alt="{{ $artikel->judul }}"
                                        class="w-full h-full object-cover transition duration-300 group-hover:scale-105" />
                                </a>
                            </div>

                            {{-- Text --}}
                            <div class="flex-1 p-4 flex flex-col">
                                <h3
                                    class="font-bold text-[15px] md:text-base text-slate-800 leading-tight mb-1.5 line-clamp-2">
                                    <a href="{{ route('kepalabagian.artikelpengetahuan.show', $artikel->id) }}"
                                        class="hover:text-blue-700">
                                        {{ $artikel->judul }}
                                    </a>
                                </h3>

                                <div class="text-[12px] text-slate-500 mb-1.5">
                                    Kategori:
                                    <span class="font-semibold text-slate-700">
                                        {{ $artikel->kategoriPengetahuan->nama_kategoripengetahuan ?? '-' }}
                                    </span>
                                </div>

                                <p class="text-sm text-slate-600 line-clamp-2">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($artikel->isi ?? ''), 140) }}
                                </p>

                                <div class="mt-auto pt-3 flex items-center justify-between text-[12px] text-slate-500">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="fas fa-eye"></i> {{ number_format($artikel->views_total) }}
                                    </span>
                                    <span>{{ \Carbon\Carbon::parse($artikel->created_at)->translatedFormat('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-white border rounded-2xl shadow-sm p-10 flex flex-col items-center text-center">
                    <img src="{{ asset('assets/img/empty-state.svg') }}" class="w-36 opacity-80 mb-4" alt="Kosong">
                    <h3 class="text-lg md:text-xl font-bold text-slate-700">Belum Ada Artikel</h3>
                    <p class="text-slate-500 mt-1">Silakan tambahkan artikel baru.</p>
                </div>
                @endif

                <div class="mt-8">
                    {{-- {{ $artikels->links() }} --}}
                </div>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-6 xl:pl-2 xl:sticky xl:top-24 h-fit">
                {{-- Kartu Role --}}
                <div
                    class="rounded-2xl p-7 text-white shadow-lg bg-gradient-to-br from-blue-600 to-blue-800 text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon"
                        class="h-16 w-16 mx-auto mb-4">
                    <p class="font-bold text-lg">{{ Auth::user()->role->nama_role ?? 'User' }}</p>
                </div>

                {{-- Aksi: hanya Tambah Artikel (Tambah Kategori DIHAPUS sesuai usecase) --}}
                <a href="{{ route('kepalabagian.artikelpengetahuan.create') }}"
                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition">
                    <i class="fa-solid fa-plus"></i> <span>Tambah Artikel</span>
                </a>

                {{-- Kategori + ikon edit/delete (dibatasi izin) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-blue-800 font-semibold text-lg border-b pb-2 mb-3">Kategori Pengetahuan</h3>
                    <ul class="space-y-2 max-h-80 overflow-auto pr-1">
                        @foreach ($kategori as $kat)
                        <li class="flex items-center justify-between gap-3">
                            <span class="text-sm text-slate-700 truncate">{{ $kat->nama_kategoripengetahuan }}-{{ $kat->subbidang->nama ?? '-' }}</span>
                            <div class="flex items-center gap-2">
                                <button type="button"
                                    class="btn-kat-edit p-1.5 rounded hover:bg-slate-50 text-slate-400"
                                    title="Edit tidak diizinkan" data-id="{{ $kat->id }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button type="button" class="btn-kat-del p-1.5 rounded hover:bg-slate-50 text-slate-400"
                                    title="Hapus tidak diizinkan" data-id="{{ $kat->id }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </aside>

        </div>

    </div>

    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                    class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>

    {{-- SweetAlert2 untuk tombol kategori yang dibatasi --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
    <script>
    document.querySelectorAll('.btn-kat-edit, .btn-kat-del').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            Swal.fire({
                icon: 'info',
                title: 'Aksi tidak diizinkan',
                html: 'Kepala Bagian tidak memiliki izin mengubah kategori.<br>Id kategori: <b>' +
                    String(id) + '</b>',
                confirmButtonText: 'Mengerti',
                customClass: {
                    confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg'
                },
                buttonsStyling: false
            });
        });
    });
    </script>
</x-app-layout>