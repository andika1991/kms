@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Edit Manajemen Pengguna')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] flex flex-col">
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

        <!-- FLASH MESSAGES -->
        <div class="max-w-2xl mx-auto w-full mt-4 px-4">
            @if(session('success'))
            <div class="rounded-xl bg-green-100 border border-green-400 text-green-800 p-4 text-center text-sm font-semibold shadow mb-4">
                {{ session('success') }}
            </div>
            @endif
            @if($errors->any())
            <div class="rounded-xl bg-red-100 border border-red-400 text-red-800 p-4 text-center text-sm font-semibold shadow mb-4">
                <ul class="list-disc list-inside text-left">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <!-- FORM CONTENT -->
        <div class="w-full px-2 md:px-10 py-10">
            <form action="{{ route('kasubbidang.manajemenpengguna.update', $pengguna->id) }}" method="POST" autocomplete="off"
                  class="bg-white rounded-2xl shadow-lg p-6 md:p-10 w-full max-w-5xl mx-auto flex flex-col gap-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Nama -->
                    <div class="flex flex-col gap-2">
                        <label for="name" class="font-semibold text-gray-800">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $pengguna->name) }}"
                            class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition"
                            required>
                    </div>
                    <!-- Email -->
                    <div class="flex flex-col gap-2">
                        <label for="email" class="font-semibold text-gray-800">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $pengguna->email) }}"
                            class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition"
                            required>
                    </div>
                </div>

                <!-- Password -->
                <div class="flex flex-col gap-2 md:w-2/3">
                    <label for="password" class="font-semibold text-gray-800">
                        Password <span class="text-gray-500 text-xs font-normal">(Kosongkan jika tidak ingin diubah)</span>
                    </label>
                    <input type="password" name="password" id="password"
                        class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Masukkan password baru jika ingin mengganti">
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col md:flex-row md:justify-end gap-3 pt-4">
                    <a href="{{ route('kasubbidang.manajemenpengguna.index') }}"
                        class="px-6 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white font-semibold transition text-base text-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition text-base text-center">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
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