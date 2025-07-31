@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

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
    <div class="bg-[#eaf5ff] min-h-screen w-full">
        <!-- HEADER -->
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Forum Diskusi</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari Forum..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                    {{-- Dropdown Profile --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition
                            style="display: none;">
                            <div class="py-1">
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
            </div>
        </div>

        <!-- BODY GRID -->
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 max-w-7xl mx-auto">
            <!-- KOLOM UTAMA (FORUM CARD LIST) -->
            <section class="xl:col-span-8 w-full">
                <!-- Tambah Forum Mobile -->
                <div class="block xl:hidden mb-4">
                    <a href="{{ route('kasubbidang.forum.create') }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Forum</span>
                    </a>
                </div>

                @forelse($grupchats as $grupchat)
                <div onclick="window.location='{{ route('kasubbidang.forum.show', $grupchat->id) }}'"
                    class="bg-white rounded-2xl shadow-lg border border-gray-200/80 p-6 mb-6 hover:shadow-xl hover:border-blue-300 transition-all cursor-pointer group relative"
                    style="transition:box-shadow 0.2s, border-color 0.2s">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-lg md:text-xl text-gray-800 mb-1 group-hover:text-[#2563eb]">
                                {{ $grupchat->nama_grup }}</div>
                            <div class="text-gray-600 text-sm mb-1 line-clamp-2">{{ $grupchat->deskripsi }}</div>
                            @if($grupchat->is_private)
                            <div
                                class="inline-block bg-gray-200 text-gray-700 text-xs font-semibold px-3 py-1 rounded-lg mb-1">
                                <i class="fa-solid fa-lock mr-1"></i> Private
                            </div>
                            @endif
                            <div class="text-xs text-gray-500">Role: <span
                                    class="font-semibold">{{ $grupchat->grup_role ?? '-' }}</span></div>
                        </div>
                        @if($grupchat->pengguna_id == auth()->id())
                        <div class="flex-shrink-0 flex items-center gap-2 mt-4 sm:mt-0">
                            <a href="{{ route('kasubbidang.forum.edit', $grupchat->id) }}"
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-yellow-400 hover:bg-yellow-500 text-white font-semibold transition text-xs shadow">
                                <i class="fa-solid fa-pencil"></i>
                                <span>Edit</span>
                            </a>
                            <form action="{{ route('kasubbidang.forum.destroy', $grupchat->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus forum ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-500 hover:bg-red-600 text-white font-semibold transition text-xs shadow">
                                    <i class="fa-solid fa-trash"></i>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div
                    class="flex flex-col items-center justify-center text-center h-full py-20 px-6 bg-white rounded-2xl shadow-lg border">
                    <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" class="w-48 mb-6 opacity-50"
                        alt="Logo Diskominfotik" />
                    <h3 class="text-xl font-bold text-gray-700">Belum ada forum</h3>
                    <p class="text-gray-500 mt-2">Mulai buat diskusi anda.</p>
                </div>
                @endforelse

                <div class="mt-8">
                    {{-- {{ $grupchats->links() }} --}}
                </div>
            </section>

            <!-- SIDEBAR KANAN -->
            <aside class="xl:col-span-4 w-full flex flex-col gap-8">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-7 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-comments text-4xl"></i>
                    </div>
                    <h3 class="font-bold text-lg">Forum Diskusi</h3>
                    <p class="text-xs mt-2">Diskusi, kolaborasi, dan knowledge sharing ke seluruh rekan dinas.</p>
                </div>
                <a href="{{ route('kasubbidang.forum.create') }}"
                    class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Forum</span>
                </a>
                <div class="bg-white rounded-2xl shadow-lg p-7">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Forum Diskusi merupakan fitur untuk mempermudah pegawai Dinas Komunikasi, Informatika dan
                        Statistik Provinsi Lampung dapat saling berbagi pengetahuan dan mempermudah komunikasi dengan
                        manajemen yang terstruktur dan mudah diakses.
                    </p>
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