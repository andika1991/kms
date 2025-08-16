@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Forum Diskusi Pegawai')

{{-- ALERT Sukses --}}
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
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

{{-- ALERT Terhapus --}}
@if (session('deleted'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
        position: 'top',
        icon: 'error',
        title: @json(session('deleted')),
        showConfirmButton: false,
        background: '#fff0f0',
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
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
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
                            style="display:none;">
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

        {{-- BODY GRID --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 max-w-7xl mx-auto">
            {{-- KOLOM UTAMA --}}
            <section class="xl:col-span-8 w-full">
                {{-- Tambah Forum (mobile) --}}
                <div class="block xl:hidden mb-4">
                    <a href="{{ route('pegawai.forum.create') }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Forum</span>
                    </a>
                </div>

                @forelse($grupchats as $grupchat)
                {{-- CARD FORUM --}}
                <div
                    class="bg-white rounded-2xl shadow-lg border border-gray-200/80 p-6 mb-6 hover:shadow-xl hover:border-blue-300 transition-all">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        {{-- KONTEN (klik ke detail) --}}
                        <div class="flex-1">
                            <a href="{{ route('pegawai.forum.show', $grupchat->id) }}" class="group block">
                                <div
                                    class="font-bold text-lg md:text-xl text-gray-800 mb-1 group-hover:text-blue-700 transition-colors">
                                    {{ $grupchat->nama_grup }}
                                </div>
                                <div class="text-gray-600 text-sm mb-2 line-clamp-2">
                                    {{ $grupchat->deskripsi }}
                                </div>
                            </a>

                            {{-- META: Bidang + Private di SAMPING --}}
                            <div class="text-xs text-gray-500 flex items-center flex-wrap">
                                @if(optional($grupchat->bidang)->nama_bidang ?? optional($grupchat->bidang)->nama ??
                                null)
                                <span>
                                    Bidang:
                                    <span class="font-semibold">
                                        {{ $grupchat->bidang->nama_bidang ?? $grupchat->bidang->nama }}
                                    </span>
                                </span>
                                @endif

                                @if(!empty($grupchat->is_private) && $grupchat->is_private)
                                <span class="mx-2 text-gray-400">•</span>
                                <span class="italic text-gray-600">Private</span>
                                @endif
                            </div>
                        </div>

                        {{-- AKSI (kanan) --}}
                        <div class="flex gap-2 md:gap-3 mt-3 md:mt-0">
                            @if($grupchat->pengguna_id == auth()->id())
                            <a href="{{ route('pegawai.forum.edit', $grupchat->id) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-white bg-yellow-500 hover:bg-yellow-600 shadow-sm text-sm font-semibold"
                                title="Edit forum" aria-label="Edit forum">
                                <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                                <span>Edit</span>
                            </a>

                            <form action="{{ route('pegawai.forum.destroy', $grupchat->id) }}" method="POST"
                                class="inline form-hapus">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-white bg-red-600 hover:bg-red-700 shadow-sm text-sm font-semibold"
                                    title="Hapus forum" aria-label="Hapus forum">
                                    <i class="fa-solid fa-trash text-[13px]"></i>
                                    <span>Hapus</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                {{-- EMPTY STATE --}}
                <div
                    class="flex flex-col items-center justify-center text-center h-full py-20 px-6 bg-white rounded-2xl shadow-lg border">
                    <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" class="w-48 mb-6 opacity-50"
                        alt="Logo Diskominfotik">
                    <h3 class="text-xl font-bold text-gray-700">Belum ada forum</h3>
                    <p class="text-gray-500 mt-2">Mulai buat diskusi anda.</p>
                </div>
                @endforelse
            </section>

            {{-- SIDEBAR KANAN --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-7 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-comments text-4xl"></i>
                    </div>
                    <h3 class="font-bold text-lg">Forum Diskusi</h3>
                    <p class="text-xs mt-2">Diskusi, kolaborasi, dan knowledge sharing pegawai.</p>
                </div>

                <a href="{{ route('pegawai.forum.create') }}"
                    class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Forum</span>
                </a>

                <div class="bg-white rounded-2xl shadow-lg p-7">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Forum Diskusi merupakan fitur untuk mempermudah pegawai Dinas Komunikasi, Informatika dan
                        Statistik
                        Provinsi Lampung dapat saling berbagi pengetahuan dan mempermudah komunikasi dengan manajemen
                        yang terstruktur dan mudah diakses.
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

    {{-- SweetAlert2 Confirm Delete --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form.form-hapus').forEach((form) => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();

                Swal.fire({
                    title: 'Hapus forum ini?',
                    text: 'Tindakan ini tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-2xl p-8',
                        title: 'mb-1',
                        actions: 'flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 w-full',
                        confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-2 rounded-lg',
                        cancelButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2 rounded-lg'
                    }
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    });
    </script>
</x-app-layout>