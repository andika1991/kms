@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Manajemen Pengguna Kasubbidang')

<x-app-layout>
    @if(session('success'))
    <div class="max-w-2xl mx-auto mt-4 px-4">
        <div class="rounded-xl bg-green-100 border border-green-400 text-green-800 p-4 text-center text-sm font-semibold shadow">
            {{ session('success') }}
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="max-w-2xl mx-auto mt-4 px-4">
        <div class="rounded-xl bg-red-100 border border-red-400 text-red-800 p-4 text-center text-sm font-semibold shadow">
            {{ session('error') }}
        </div>
    </div>
    @endif

    <div class="w-full min-h-screen bg-[#eaf5ff]">
        <!-- HEADER -->
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Pengguna Kasubbidang</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <!-- SEARCH BAR -->
                    <form action="{{ route('kasubbidang.manajemenpengguna.index') }}" method="GET"
                        class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" name="search" placeholder="Cari pengguna..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition"
                            value="{{ request('search') }}">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </form>
                    <!-- PROFILE DROPDOWN -->
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
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BODY -->
        <div class="p-2 sm:p-4 md:p-8 2xl:p-12 w-full">
            <div class="w-full grid grid-cols-1 xl:grid-cols-12 gap-6 xl:gap-8 max-w-[1800px] mx-auto">
                <!-- KOLOM UTAMA (TABLE USER) -->
                <section class="xl:col-span-8 w-full">
                    <div class="overflow-x-auto rounded-2xl">
                        <table class="min-w-full bg-white rounded-2xl shadow border mb-2">
                            <thead>
                                <tr class="text-left bg-[#2262A9] text-white">
                                    <th class="px-6 py-4 text-base font-semibold">No</th>
                                    <th class="px-6 py-4 text-base font-semibold">Nama</th>
                                    <th class="px-6 py-4 text-base font-semibold">Email</th>
                                    <th class="px-6 py-4 text-base font-semibold text-center">Verifikasi</th>
                                    <th class="px-6 py-4 text-base font-semibold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penggunas as $index => $user)
                                <tr class="@if($loop->even) bg-[#eaf3fa] @endif border-b border-gray-100">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($user->verified)
                                        <span class="inline-block rounded-lg px-3 py-1 bg-green-100 text-green-700 text-xs font-bold">Terverifikasi</span>
                                        @else
                                        <form action="{{ route('kasubbidang.manajemenpengguna.verifikasi', $user->id) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Verifikasi akun ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-4 py-1.5 rounded-full bg-blue-600 hover:bg-blue-800 text-white font-semibold transition text-xs">
                                                Verifikasi
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 flex gap-2 justify-center">
                                        <a href="{{ route('kasubbidang.manajemenpengguna.edit', $user->id) }}"
                                            class="px-4 py-1.5 rounded-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold transition text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        <form action="{{ route('kasubbidang.manajemenpengguna.destroy', $user->id) }}" method="POST" class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-4 py-1.5 rounded-full bg-red-600 hover:bg-red-700 text-white font-semibold transition text-xs flex items-center gap-1">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-gray-500 text-center py-12">Belum ada pengguna yang terdaftar.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- KOLOM SIDEBAR -->
                <aside class="xl:col-span-4 w-full flex flex-col gap-8">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-7 flex flex-col items-center justify-center text-center">
                        <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                        <div>
                            <p class="font-bold text-lg leading-tight mb-2">
                                {{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}</p>
                            <p class="text-xs">Manajemen akses pengguna, verifikasi akun, dan kontrol aksi subbidang di sini.</p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                    class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>
</x-app-layout>