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
        <div class="p-4 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Pengguna || Admin</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
            </div><a href="{{ route('admin.manajemenpengguna.create') }}"
    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition mb-4">
    + Tambah Pengguna
</a>

        </div>

        <!-- BODY -->
        <div class="p-2 sm:p-4 md:p-8 2xl:p-12 w-full">
            <div class="w-full grid grid-cols-1 xl:grid-cols-12 gap-6 xl:gap-8 max-w-[1800px] mx-auto">
                <!-- KOLOM UTAMA (TABLE USER) -->
                <section class="xl:col-span-8 w-full">
                    <div class="flex justify-between items-center mb-6">
                        <span class="font-bold text-lg text-[#2171b8]">Daftar Pengguna</span>
                    </div>
                    <div class="overflow-x-auto rounded-2xl">
                        <table class="min-w-full bg-white rounded-2xl shadow border mb-2">
                            <thead>
                                <tr class="text-left bg-gray-100">
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
                                        <form action="{{ route('admin.manajemenpengguna.verifikasi', $user->id) }}" method="POST" class="inline"
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
                                        <a href="{{ route('admin.manajemenpengguna.edit', $user->id) }}"
                                            class="px-4 py-1.5 rounded-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold transition text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.manajemenpengguna.destroy', $user->id) }}" method="POST" class="inline-block"
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
                            <p class="text-xs">Manajemen akses pengguna, verifikasi akun, dan kontrol keamanan subbidang di sini.</p>
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