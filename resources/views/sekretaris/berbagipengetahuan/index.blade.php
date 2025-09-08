@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Artikel Pengetahuan Sekretaris')

{{-- ALERT Sukses --}}
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        position: 'top',
        icon: 'success',
        title: '{{ session("success") }}',
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        position: 'top',
        icon: 'error',
        title: '{{ session("deleted") }}',
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
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Artikel Pengetahuan</h2>
                    <p class="text-gray-500 text-sm font-normal">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 mt-4 sm:mt-0 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <form method="GET" action="{{ route('sekretaris.berbagipengetahuan.index') }}"
                        class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari artikel pengetahuan..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
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
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95" style="display: none;">
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

        {{-- BODY KONTEN GRID --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 bg-[#eaf5ff] min-h-[calc(100vh-120px)]">
            {{-- KOLOM KIRI (LIST ARTIKEL - gaya admin/figma: kartu horizontal) --}}
            <section class="xl:col-span-8 w-full">
                <div class="flex flex-col gap-6">
                    @forelse($artikels as $artikel)
                    <a href="{{ route('sekretaris.berbagipengetahuan.show', $artikel->id) }}"
                        class="block bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-4 border-4 border-white group">
                        <div class="flex flex-col sm:flex-row gap-5">
                            {{-- Gambar --}}
                            @php
                            $thumb = $artikel->thumbnail ? asset('storage/'.$artikel->thumbnail) :
                            asset('assets/img/artikel-elemen.png');
                            @endphp
                            <img src="{{ $thumb }}" alt="{{ $artikel->judul }}"
                                class="w-full sm:w-48 h-40 sm:h-auto object-cover rounded-lg flex-shrink-0" />

                            {{-- Konten --}}
                            <div class="flex flex-col flex-grow">
                                <h3 class="font-bold text-lg text-gray-800 group-hover:text-blue-700 transition-colors mb-2 line-clamp-2"
                                    title="{{ $artikel->judul }}">
                                    {{ $artikel->judul }}
                                </h3>

                                <p class="text-xs font-semibold text-blue-600 mb-2">
                                    Kategori: {{ $artikel->kategoriPengetahuan->nama_kategoripengetahuan ?? '-' }}
                                </p>

                                <p class="text-sm text-gray-600 line-clamp-2">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($artikel->isi), 150) }}
                                </p>

                                <div class="flex justify-between items-center text-xs text-gray-500 mt-auto pt-4">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="fas fa-eye"></i> {{ number_format($artikel->views_total) }}
                                    </span>
                                    <span>{{ \Carbon\Carbon::parse($artikel->created_at)->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div
                        class="col-span-full flex flex-col items-center justify-center text-center h-full py-20 px-6 bg-white rounded-2xl shadow-lg border">
                        <img src="{{ asset('assets/img/empty-state.svg') }}" class="mx-auto w-40 opacity-70 mb-4"
                            alt="Empty">
                        <h3 class="text-xl font-bold text-gray-700">Belum Ada Artikel Pengetahuan</h3>
                        <p class="text-gray-500 mt-2">Silakan tambahkan artikel baru untuk memulai.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $artikels->appends(request()->query())->links() }}
                </div>
            </section>

            {{-- KOLOM KANAN (SIDEBAR) --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8 xl:pl-2">
                {{-- Kartu Role --}}
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'Sekretaris' }}
                        </p>
                    </div>
                </div>

                {{-- Kartu Aksi --}}
                <div class="flex flex-col gap-3 mt-6 mb-2">
                    <a href="{{ route('sekretaris.berbagipengetahuan.create') }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Artikel</span>
                    </a>
                    {{-- warna tombol kategori -> biru (match admin & Figma) --}}
                    <a href="{{ route('sekretaris.kategoripengetahuan.create') }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-folder-plus"></i>
                        <span>Tambah Kategori Pengetahuan</span>
                    </a>
                </div>

                {{-- Kartu Kategori --}}
                <div class="bg-white rounded-2xl shadow-lg p-7 mt-4">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Kategori Pengetahuan</h3>
                    <ul class="space-y-2">
                        @foreach ($kategoriPengetahuans as $kat)
                        <li class="flex items-center justify-between group">
                            <span class="text-sm text-gray-700">{{ $kat->nama_kategoripengetahuan }}</span>
                            <span class="flex gap-1 opacity-70 group-hover:opacity-100 transition">
                                <a href="{{ route('sekretaris.kategoripengetahuan.edit', $kat->id) }}"
                                    class="inline-flex items-center justify-center w-7 h-7 rounded hover:bg-blue-100 text-blue-600"
                                    title="Edit Kategori">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button type="button"
                                    class="inline-flex items-center justify-center w-7 h-7 rounded hover:bg-red-100 text-red-600"
                                    title="Hapus Kategori"
                                    onclick="hapusKategori('{{ route('sekretaris.kategoripengetahuan.destroy', $kat->id) }}')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </span>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script>
    function hapusKategori(url) {
        Swal.fire({
            title: 'Apakah Anda Yakin',
            text: 'data akan dihapus',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batalkan',
            customClass: {
                popup: 'rounded-xl p-7',
                confirmButton: 'text-base font-semibold px-10 py-2 rounded-lg mr-2 bg-[#A44D3A] text-white hover:bg-[#8c3e2e] focus:ring-2 focus:ring-[#A44D3A] focus:ring-offset-2',
                cancelButton: 'text-base font-semibold px-10 py-2 rounded-lg bg-[#3971A6] text-white hover:bg-[#295480] focus:ring-2 focus:ring-[#3971A6] focus:ring-offset-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = url;
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    </script>
</x-app-layout>