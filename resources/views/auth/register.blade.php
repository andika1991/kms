<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Knowledge Management System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-figtree bg-gray-100"
    style="background-image: url('{{ asset('assets/img/body-bg-pattern.png') }}'); background-repeat: repeat; background-size: auto;">

    {{-- WRAPPER UTAMA --}}
    <div class="flex flex-col min-h-screen">

        {{-- HEADER --}}
        <header class="w-full px-6 py-4">
            <div
                class="max-w-6xl mx-auto bg-white/90 backdrop-blur-sm shadow-lg rounded-full flex items-center justify-between p-2">
                <a href="/">
                    <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" alt="KMS DISKOMINFOTIK" class="h-9 ml-4">
                </a>
                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-gray-600 text-sm hover:text-blue-700 transition">Beranda</a>
                    <a href="{{ route('about') }}" class="text-gray-600 text-sm hover:text-blue-700 transition">Tentang Kami</a>
                    <a href="{{ route('pengetahuan') }}" class="text-gray-600 text-sm hover:text-blue-700 transition">Pengetahuan</a>
                    <a href="{{ route('dokumen') }}" class="text-gray-600 text-sm hover:text-blue-700 transition">Dokumen</a>
                </nav>
                <a href="{{ route('login') }}"
                    class="bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded-full shadow-md hover:bg-blue-800 transition">Masuk</a>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="flex-grow flex flex-col items-center justify-center px-6 py-12">

            <div
                class="w-full max-w-4xl bg-white shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

                {{-- Bagian Kiri (Form) --}}
                <div class="p-10 order-2 md:order-1">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar untuk menggunakan KMS</h1>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="relative mb-4">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                {{-- Ikon User --}}
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                autocomplete="name"
                                class="block w-full pl-10 pr-3 py-2.5 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Nama Lengkap">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="relative mb-4">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                {{-- Ikon Users --}}
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M7 8a3 3 0 100-6 3 3 0 000 6zM14.5 9a3.5 3.5 0 100-7 3.5 3.5 0 000 7zM1.496 18.286A5.494 5.494 0 016 15h8a5.494 5.494 0 014.504 3.286A5.5 5.5 0 0114.5 14h-9a5.5 5.5 0 01-4.004 4.286z" />
                                </svg>
                            </span>
                            <select id="tipe_user" name="tipe_user"
                                class="block w-full pl-10 pr-3 py-2.5 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 appearance-none"
                                required onchange="toggleRoleOptions()">
                                <option value="">-- Pilih Tipe Pendaftar --</option>
                                <option value="pegawai" @if(old('tipe_user')=='pegawai' ) selected @endif>Pegawai
                                </option>
                                <option value="magang" @if(old('tipe_user')=='magang' ) selected @endif>Magang</option>
                            </select>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none">
                                {{-- Ikon Panah Dropdown --}}
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>

                        <div class="relative mb-4 hidden" id="role-pegawai">
                            <select name="role_id_pegawai"
                                class="block w-full py-2.5 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Role Pegawai --</option>
                                <option value="1">Admin</option>
                                <option value="2">Editor</option>
                            </select>
                        </div>

                        <div class="relative mb-4 hidden" id="role-magang">
                            <select name="role_id_magang"
                                class="block w-full py-2.5 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Role Magang --</option>
                                <option value="3">Kontributor</option>
                                <option value="4">Viewer</option>
                            </select>
                        </div>

                        <div class="relative mb-4">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                {{-- Ikon Email --}}
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
                                    <path
                                        d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" />
                                </svg>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autocomplete="username"
                                class="block w-full pl-10 pr-3 py-2.5 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Email">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="relative mb-4">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                {{-- Ikon Gembok --}}
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="block w-full pl-10 pr-3 py-2.5 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="relative mb-4">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                {{-- Ikon Gembok --}}
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                autocomplete="new-password"
                                class="block w-full pl-10 pr-3 py-2.5 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Konfirmasi Password">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <button type="submit"
                            class="w-full bg-[#4A5568] text-white font-semibold py-2.5 rounded-lg shadow-md hover:bg-gray-800 transition mt-4">
                            Daftar
                        </button>
                    </form>
                </div>

                {{-- Bagian Kanan (Ilustrasi) --}}
                <div
                    class="bg-[#1E40AF] p-10 flex-col items-center justify-center rounded-r-2xl hidden md:flex order-1 md:order-2">
                    <img src="{{ asset('assets/img/register_figure.png') }}" alt="Register Illustration"
                        class="w-full max-w-[250px]">
                </div>
            </div>

            {{-- Link kembali ke Login --}}
            <div class="mt-8">
                <a class="text-sm text-gray-600 hover:text-gray-900 hover:underline rounded-md"
                    href="{{ route('login') }}">
                    {{ __('Sudah punya akun? Masuk') }}
                </a>
            </div>
        </main>

        {{-- FOOTER --}}
        <footer class="bg-[#0B3C6A] text-white pt-10 pb-8">
            <div class="max-w-[1200px] mx-auto grid md:grid-cols-3 gap-8 px-6 text-sm">
                <div class="space-y-3">
                    <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" alt="Logo Diskominfo"
                        class="h-10">
                    <p class="font-semibold">Dinas Komunikasi, Informatika dan Statistik Provinsi Lampung</p>
                    <div class="text-white/70 text-xs leading-relaxed">
                        <p>Alamat : Jl. WR Monginsidi No.69 Bandar Lampung</p>
                        <p>Telepon : (0721) 481107</p>
                        <p>Facebook : www.facebook.com/diskominfo.lpg</p>
                        <p>Instagram : www.instagram.com/diskominfotiklampung</p>
                    </div>
                </div>
                <div class="md:mx-auto">
                    <h4 class="font-bold mb-4">Menu</h4>
                    <ul class="space-y-2 text-white/80">
                        <li><a href="/" class="hover:underline hover:text-white">Home</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Tentang Kami</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Kegiatan</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Dokumen</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Kontak</a></li>
                    </ul>
                </div>
                <div class="md:mx-auto">
                    <h4 class="font-bold mb-4">Ikuti Kami</h4>
                    <div class="flex items-center gap-4">
                        <a href="#" class="hover:opacity-80 transition"><img
                                src="{{ asset('assets/img/facebook-icon.svg') }}" alt="Facebook" class="h-8"></a>
                        <a href="#" class="hover:opacity-80 transition"><img
                                src="{{ asset('assets/img/instagram-icon.svg') }}" alt="Instagram" class="h-8"></a>
                        <a href="#" class="hover:opacity-80 transition"><img
                                src="{{ asset('assets/img/youtube-icon.svg') }}" alt="YouTube" class="h-8"></a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    {{-- SCRIPT UNTUK DROPDOWN --}}
    <script>
    function toggleRoleOptions() {
        const tipeUser = document.getElementById('tipe_user').value;
        const rolePegawai = document.getElementById('role-pegawai');
        const roleMagang = document.getElementById('role-magang');

        // Mengambil input select untuk role
        const rolePegawaiSelect = rolePegawai.querySelector('select');
        const roleMagangSelect = roleMagang.querySelector('select');

        // Reset nama agar hanya satu 'role_id' yang terkirim
        rolePegawaiSelect.name = '';
        roleMagangSelect.name = '';

        if (tipeUser === 'pegawai') {
            rolePegawai.classList.remove('hidden');
            roleMagang.classList.add('hidden');
            rolePegawaiSelect.name = 'role_id'; // Aktifkan nama untuk form submission
        } else if (tipeUser === 'magang') {
            roleMagang.classList.remove('hidden');
            rolePegawai.classList.add('hidden');
            roleMagangSelect.name = 'role_id'; // Aktifkan nama untuk form submission
        } else {
            rolePegawai.classList.add('hidden');
            roleMagang.classList.add('hidden');
        }
    }

    // Jalankan fungsi saat halaman pertama kali dimuat untuk menangani old('value')
    document.addEventListener('DOMContentLoaded', function() {
        toggleRoleOptions();
    });
    </script>
</body>

</html>