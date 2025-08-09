@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Tambah Pengguna Baru Admin')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] flex flex-col">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Tambah Pengguna Baru || Administrator</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <form action="{{ route('admin.manajemenpengguna.index') }}" method="GET"
                        class="relative flex-grow sm:flex-grow-0 sm:w-64 hidden sm:block">
                        <input type="text" name="search" placeholder="Cari pengguna..."
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

        {{-- MAIN CONTENT --}}
        <div
            class="w-full min-h-[80vh] bg-[#eaf5ff] flex flex-col lg:flex-row gap-8 px-2 py-10 justify-center items-start">
            {{-- FORM UTAMA --}}
            <div class="w-full max-w-2xl">
                {{-- FLASH & ERROR --}}
                @if(session('success'))
                <div
                    class="bg-green-100 border border-green-400 text-green-800 p-4 rounded-xl mb-4 text-sm font-semibold text-center">
                    {{ session('success') }}
                </div>
                @endif
                @if($errors->any())
                <div
                    class="bg-red-100 border border-red-400 text-red-800 p-4 rounded-xl mb-4 text-sm font-semibold text-center">
                    <ul class="list-disc list-inside text-left">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form id="form-pengguna" action="{{ route('admin.manajemenpengguna.store') }}" method="POST"
                    class="bg-white rounded-2xl shadow-lg p-6 md:p-10 w-full flex flex-col gap-6">
                    @csrf

                    <div class="flex flex-col gap-2">
                        <label for="name" class="font-semibold text-gray-800">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="email" class="font-semibold text-gray-800">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <!-- Role Group -->
                    <div class="flex flex-col gap-2">
                        <label for="role_group" class="font-semibold text-gray-800">Role Group</label>
                        <select name="role_group" id="role_group" required
                            class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">-- Pilih Role Group --</option>
                            @foreach ($roleGroups as $group)
                            <option value="{{ $group }}">{{ ucfirst($group) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Role -->
                    <div class="flex flex-col gap-2">
                        <label for="role_id" class="font-semibold text-gray-800">Role</label>
                        <select name="role_id" id="role_id" required
                            class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">-- Pilih Role --</option>
                            {{-- Diisi via JS --}}
                        </select>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="password" class="font-semibold text-gray-800">Password</label>
                        <input type="password" name="password" id="password" required
                            class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </form>
            </div>

            {{-- SIDEBAR --}}
            <aside class="flex flex-col gap-6 w-full max-w-sm mt-10 lg:mt-0">
                {{-- CARD ROLE --}}
                <div
                    class="bg-gradient-to-br from-blue-700 to-blue-500 text-white rounded-2xl shadow-lg p-7 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-14 w-14 mb-3">
                    <p class="font-bold text-base leading-tight">Bidang
                        {{ Auth::user()->role->nama_role ?? 'Administrator' }}</p>
                </div>
                {{-- Tombol Simpan & Batalkan --}}
                <div class="flex gap-3 w-full">
                    <button type="button" id="btnSimpan"
                        class="flex-1 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition text-base">
                        Simpan
                    </button>
                    <a href="{{ route('admin.manajemenpengguna.index') }}" id="btnBatal"
                        class="flex-1 px-4 py-2 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow transition text-base text-center">
                        Batalkan
                    </a>
                </div>
            </aside>
        </div>
    </div>

    <script>
    // Ambil semua data role dari controller (sudah di-passing via Blade)
    const allRoles = @json($roles);

    const roleGroupSelect = document.getElementById('role_group');
    const roleSelect = document.getElementById('role_id');

    // Event saat role_group dipilih
    roleGroupSelect.addEventListener('change', function() {
        const selectedGroup = this.value;

        // Kosongkan dropdown role dulu
        roleSelect.innerHTML = '<option value="">-- Pilih Role --</option>';

        if (selectedGroup === '') return;

        // Filter role berdasarkan role_group
        const filteredRoles = allRoles.filter(role => role.role_group === selectedGroup);

        // Tambahkan ke dropdown role
        filteredRoles.forEach(role => {
            const option = document.createElement('option');
            option.value = role.id;
            option.textContent = role.nama_role;
            roleSelect.appendChild(option);
        });
    });
    </script>

    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('form-pengguna');
        const btnSimpan = document.getElementById('btnSimpan');
        const btnBatal = document.getElementById('btnBatal');

        btnSimpan.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                icon: 'success',
                title: 'Simpan Data?',
                text: 'Apakah data sudah benar dan ingin disimpan?',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg mx-2',
                    cancelButton: 'bg-red-700 hover:bg-red-800 text-white font-semibold px-6 py-2 rounded-lg mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        btnBatal.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Batalkan Input?',
                text: 'Data yang diisi akan dibatalkan dan tidak disimpan.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Kembali',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'bg-red-700 hover:bg-red-800 text-white font-semibold px-6 py-2 rounded-lg mx-2',
                    cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-900 font-semibold px-6 py-2 rounded-lg mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('admin.manajemenpengguna.index') }}";
                }
            });
        });
    });
    </script>

    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                    class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>

</x-app-layout>