@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Berbagi Pengetahuan Kepala Dinas')

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

@if (session('deleted'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        position: 'top',
        icon: 'error',
        title: '{{ session("deleted") }}',
        showConfirmButton: false,
        background: '#f0fff4',
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
                    <form method="GET" action="{{ route('kadis.berbagipengetahuan.index') }}"
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
                            x-transition:leave-end="transform opacity-0 scale-95" style="display:none;">
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

        {{-- BODY KONTEN --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- KOLOM KIRI (LIST ARTIKEL HORIZONTAL) --}}
            <section class="xl:col-span-8 w-full">
                <div class="flex flex-col gap-6">
                    @forelse($artikels as $artikel)
                    <a href="{{ route('kadis.berbagipengetahuan.show', $artikel->id) }}"
                        class="block bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-4 border-4 border-white group">
                        <div class="flex flex-col sm:flex-row gap-5">
                            {{-- Gambar Artikel --}}
                            <img src="{{ asset('storage/' . ($artikel->thumbnail ?? '')) }}"
                                onerror="this.src='{{ asset('assets/img/artikel-elemen.png') }}'"
                                alt="{{ $artikel->judul }}"
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
                                    <span class="flex items-center gap-1.5">
                                        <i class="fas fa-eye"></i> {{ $artikel->views ?? 0 }}
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

                {{-- Pagination (aktifkan jika diperlukan) --}}
                <div class="mt-8">
                    {{-- {{ $artikels->appends(request()->query())->links() }} --}}
                </div>
            </section>

            {{-- KOLOM KANAN (SIDEBAR + MODAL DELETE) --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8">
                <div x-data="{ showDeleteModal: false, deleteAction: '', deleteName: '' }" class="relative">
                    {{-- Card Role --}}
                    <div
                        class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                        <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon"
                            class="h-16 w-16 mb-4">
                        <div>
                            <p class="font-bold text-lg leading-tight">
                                {{ Auth::user()->role->nama_role ?? 'Kepala Dinas' }}
                            </p>
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="flex flex-col gap-3 mt-6 mb-2">
                        <a href="{{ route('kadis.berbagipengetahuan.create') }}"
                            class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                            <i class="fa-solid fa-plus"></i>
                            <span>Tambah Artikel</span>
                        </a>
                        <a href="{{ route('kadis.kategoripengetahuan.create') }}"
                            class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition text-base">
                            <i class="fa-solid fa-folder-plus"></i>
                            <span>Tambah Kategori Pengetahuan</span>
                        </a>
                    </div>

                    {{-- Kategori --}}
                    <div class="bg-white rounded-2xl shadow-lg p-7 mt-4">
                        <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Kategori Pengetahuan</h3>
                        <ul class="space-y-2">
                            @foreach ($kategoriPengetahuans as $kat)
                            <li class="flex items-center justify-between group">
                                <span class="text-sm text-gray-700">{{ $kat->nama_kategoripengetahuan }}</span>
                                <span class="flex gap-1 opacity-70 group-hover:opacity-100 transition">
                                    <a href="{{ route('kadis.kategoripengetahuan.edit', $kat->id) }}"
                                        class="inline-flex items-center justify-center w-7 h-7 rounded hover:bg-blue-100 text-blue-600"
                                        title="Edit Kategori">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    {{-- Trigger Modal Delete --}}
                                    <button type="button"
                                        @click="showDeleteModal=true; deleteAction='{{ route('kadis.kategoripengetahuan.destroy', $kat->id) }}'; deleteName='{{ $kat->nama_kategoripengetahuan }}';"
                                        class="inline-flex items-center justify-center w-7 h-7 rounded hover:bg-red-100 text-red-600"
                                        title="Hapus Kategori">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Modal Hapus Kategori (Alpine) --}}
                    <div x-show="showDeleteModal"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm"
                        style="display:none;">
                        <div
                            class="bg-white rounded-2xl shadow-xl max-w-xs w-full mx-2 p-6 text-center animate-fade-in">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-2" fill="none" viewBox="0 0 48 48">
                                    <circle cx="24" cy="24" r="22" fill="#fffbe6" stroke="#ffb800" stroke-width="3" />
                                    <path d="M24 14v12m0 6h.01" stroke="#ffb800" stroke-width="3"
                                        stroke-linecap="round" />
                                </svg>
                                <h2 class="font-bold text-lg text-gray-900 mb-1">Apakah Anda Yakin</h2>
                                <p class="text-gray-600 text-sm mb-6">data <span class="font-semibold"
                                        x-text="deleteName"></span> akan dihapus</p>
                            </div>
                            <form :action="deleteAction" method="POST" class="flex gap-2 justify-center">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-[#a44d3a] hover:bg-[#943b2b] text-white px-6 py-2 rounded-lg font-semibold w-1/2">
                                    Hapus
                                </button>
                                <button type="button" @click="showDeleteModal=false"
                                    class="bg-[#3971a6] hover:bg-[#295985] text-white px-6 py-2 rounded-lg font-semibold w-1/2">
                                    Batalkan
                                </button>
                            </form>
                        </div>
                    </div>

                    <style>
                    @keyframes fade-in {
                        from {
                            opacity: 0;
                            transform: scale(0.98)
                        }

                        to {
                            opacity: 1;
                            transform: scale(1)
                        }
                    }

                    .animate-fade-in {
                        animation: fade-in .20s cubic-bezier(0.4, 0, 0.2, 1) both;
                    }
                    </style>
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
</x-app-layout>