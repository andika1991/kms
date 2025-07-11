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

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="block mt-1 w-full"
                          type="text"
                          name="name"
                          :value="old('name')"
                          required
                          autofocus
                          autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Tipe User -->
        <div class="mt-4">
            <x-input-label for="tipe_user" :value="__('Tipe Pendaftar')" />
            <select id="tipe_user"
                    name="tipe_user"
                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    required
                    onchange="toggleRoleOptions()">
                <option value="">-- Pilih Tipe Pendaftar --</option>
                <option value="pegawai" {{ old('tipe_user') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                <option value="magang" {{ old('tipe_user') == 'magang' ? 'selected' : '' }}>Magang</option>
            </select>
            <x-input-error :messages="$errors->get('tipe_user')" class="mt-2" />
        </div>

        <!-- Role Pegawai -->
        <div class="mt-4 {{ old('tipe_user') == 'pegawai' ? '' : 'hidden' }}" id="role-pegawai">
            <x-input-label for="role_id_pegawai" :value="__('Pilih Role Pegawai')" />
            <select id="role_id_pegawai"
                    name="{{ old('tipe_user') == 'pegawai' ? 'role_id' : '' }}"
                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">-- Pilih Role Pegawai --</option>
                @foreach ($rolesPegawai as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->nama_role }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
        </div>

        <!-- Role Magang -->
        <div class="mt-4 {{ old('tipe_user') == 'magang' ? '' : 'hidden' }}" id="role-magang">
            <x-input-label for="role_id_magang" :value="__('Pilih Role Magang')" />
            <select id="role_id_magang"
                    name="{{ old('tipe_user') == 'magang' ? 'role_id' : '' }}"
                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">-- Pilih Role Magang --</option>
                @foreach ($rolesMagang as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->nama_role }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation"
                          required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
               href="{{ route('login') }}">
                {{ __('Sudah punya akun? Masuk') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Daftar') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function toggleRoleOptions() {
            const tipeUser = document.getElementById('tipe_user').value;
            const rolePegawai = document.getElementById('role-pegawai');
            const roleMagang = document.getElementById('role-magang');

            const pegawaiSelect = document.getElementById('role_id_pegawai');
            const magangSelect = document.getElementById('role_id_magang');

            // Reset name attributes supaya hanya 1 dikirim
            pegawaiSelect.name = '';
            magangSelect.name = '';

            if (tipeUser === 'pegawai') {
                rolePegawai.classList.remove('hidden');
                roleMagang.classList.add('hidden');
                pegawaiSelect.name = 'role_id';
            } else if (tipeUser === 'magang') {
                roleMagang.classList.remove('hidden');
                rolePegawai.classList.add('hidden');
                magangSelect.name = 'role_id';
            } else {
                rolePegawai.classList.add('hidden');
                roleMagang.classList.add('hidden');
            }
        }

        // Panggil sekali saat load halaman untuk handle old() values
        document.addEventListener('DOMContentLoaded', () => {
            toggleRoleOptions();
        });
    </script>
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