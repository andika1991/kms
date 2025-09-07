<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Knowledge Management System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Form styling khusus halaman ini --}}
    <style>
    .auth-card {
        color-scheme: light;
    }

    .auth-card input,
    .auth-card select,
    .auth-card textarea {
        background-color: #f6f8fa !important;
        /* abu-abu muda */
        color: #111827 !important;
        /* gray-900 */
    }

    .auth-card input::placeholder,
    .auth-card textarea::placeholder {
        color: #6b7280;
        /* gray-500 */
    }

    /* Chrome autofill agar tidak gelap */
    .auth-card input:-webkit-autofill,
    .auth-card input:-webkit-autofill:hover,
    .auth-card input:-webkit-autofill:focus,
    .auth-card select:-webkit-autofill,
    .auth-card textarea:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 1000px #f6f8fa inset !important;
        box-shadow: 0 0 0 1000px #f6f8fa inset !important;
        -webkit-text-fill-color: #111827 !important;
        transition: background-color 9999s ease-in-out 0s;
    }
    </style>
</head>

<body class="font-figtree"
    style="background-image: url('{{ asset('/img/body-bg-pattern.png') }}'); background-repeat: repeat; background-size: auto;">

    {{-- HEADER --}}
    <header class="bg-white shadow-md sticky top-0 z-20 rounded-b-3xl">
        <div class="max-w-[1200px] mx-auto flex items-center justify-between px-6 py-3">
            <a href="/"><img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" alt="KMS DISKOMINFOTIK"
                    class="h-9"></a>
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-blue-700 text-sm font-semibold transition">Beranda</a>
                <a href="{{ route('about') }}" class="text-gray-600 text-sm hover:text-blue-700 transition">Tentang
                    Kami</a>
                <a href="{{ route('pengetahuan') }}"
                    class="text-gray-600 text-sm hover:text-blue-700 transition">Pengetahuan</a>
                <a href="{{ route('dokumen') }}"
                    class="text-gray-600 text-sm hover:text-blue-700 transition">Dokumen</a>
                <a href="{{ route('kegiatan') }}"
                    class="text-gray-600 text-sm hover:text-blue-700 transition">Kegiatan</a>
            </nav>
            <a href="{{ route('login') }}"
                class="bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded-lg shadow hover:bg-blue-800 transition">Masuk</a>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow flex flex-col items-center justify-center px-6 py-12">

        <div
            class="auth-card w-full max-w-4xl bg-white shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

            {{-- Form --}}
            <div class="p-10 order-2 md:order-1">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar untuk menggunakan KMS</h1>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Nama --}}
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="!text-gray-800" />
                        <x-text-input id="name"
                            class="block mt-1 w-full rounded-md border border-gray-300 text-gray-800 placeholder:text-gray-500 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-none transition"
                            type="text" name="name" :value="old('name')" placeholder="Nama Lengkap" required autofocus
                            autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Tipe User --}}
                    <div class="mt-4">
                        <x-input-label for="tipe_user" :value="__('Tipe Pendaftar')" class="!text-gray-800" />
                        <select id="tipe_user" name="tipe_user"
                            class="block mt-1 w-full rounded-md border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
                            required onchange="toggleRoleOptions()">
                            <option value="">-- Pilih Tipe Pendaftar --</option>
                            <option value="pegawai" {{ old('tipe_user') == 'pegawai' ? 'selected' : '' }}>Pegawai
                            </option>
                            <option value="magang" {{ old('tipe_user') == 'magang' ? 'selected' : '' }}>Magang</option>
                        </select>
                        <x-input-error :messages="$errors->get('tipe_user')" class="mt-2" />
                    </div>

                    {{-- Role Pegawai --}}
                    <div class="mt-4 {{ old('tipe_user') == 'pegawai' ? '' : 'hidden' }}" id="role-pegawai">
                        <x-input-label for="role_id_pegawai" :value="__('Pilih Role Pegawai')" class="!text-gray-800" />
                        <select id="role_id_pegawai" name="{{ old('tipe_user') == 'pegawai' ? 'role_id' : '' }}"
                            class="block mt-1 w-full rounded-md border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-400 focus:border-blue-500">
                            <option value="">-- Pilih Role Pegawai --</option>
                            @foreach ($rolesPegawai as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->nama_role }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                    </div>

                    {{-- Role Magang --}}
                    <div class="mt-4 {{ old('tipe_user') == 'magang' ? '' : 'hidden' }}" id="role-magang">
                        <x-input-label for="role_id_magang" :value="__('Pilih Role Magang')" class="!text-gray-800" />
                        <select id="role_id_magang" name="{{ old('tipe_user') == 'magang' ? 'role_id' : '' }}"
                            class="block mt-1 w-full rounded-md border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-400 focus:border-blue-500">
                            <option value="">-- Pilih Role Magang --</option>
                            @foreach ($rolesMagang as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->nama_role }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                    </div>

                    {{-- Email --}}
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" class="!text-gray-800" />
                        <x-text-input id="email"
                            class="block mt-1 w-full rounded-md border border-gray-300 text-gray-800 placeholder:text-gray-500 focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
                            type="email" name="email" :value="old('email')" placeholder="Email" required
                            autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div class="mt-4 relative">
                        <x-input-label for="password" :value="__('Password')" class="!text-gray-800" />
                        <x-text-input id="password"
                            class="block mt-1 w-full pr-12 rounded-md border border-gray-300 text-gray-800 placeholder:text-gray-500 focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
                            type="password" name="password" placeholder="Password" required
                            autocomplete="new-password" />
                        <button type="button" id="togglePassword"
                            class="absolute right-3 bottom-3 text-gray-400 hover:text-blue-600 focus:outline-none"
                            tabindex="-1">
                            <svg id="eyePassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mt-4 relative">
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')"
                            class="!text-gray-800" />
                        <x-text-input id="password_confirmation"
                            class="block mt-1 w-full pr-12 rounded-md border border-gray-300 text-gray-800 placeholder:text-gray-500 focus:ring-2 focus:ring-blue-400 focus:border-blue-500"
                            type="password" name="password_confirmation" placeholder="Konfirmasi Password" required
                            autocomplete="new-password" />
                        <button type="button" id="togglePasswordConfirm"
                            class="absolute right-3 bottom-3 text-gray-400 hover:text-blue-600 focus:outline-none"
                            tabindex="-1">
                            <svg id="eyePasswordConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            href="{{ route('login') }}">
                            {{ __('Sudah punya akun? Masuk') }}
                        </a>
                        <x-primary-button class="ms-4">{{ __('Daftar') }}</x-primary-button>
                    </div>
                </form>

                <script>
                function toggleRoleOptions() {
                    const tipeUser = document.getElementById('tipe_user').value;
                    const rolePegawai = document.getElementById('role-pegawai');
                    const roleMagang = document.getElementById('role-magang');
                    const pegawaiSelect = document.getElementById('role_id_pegawai');
                    const magangSelect = document.getElementById('role_id_magang');

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
                document.addEventListener('DOMContentLoaded', () => toggleRoleOptions());
                </script>
            </div>

            {{-- Ilustrasi --}}
            <div
                class="bg-[#1E40AF] p-10 flex-col items-center justify-center rounded-r-2xl hidden md:flex order-1 md:order-2">
                <img src="{{ asset('assets/img/register_figure.png') }}" alt="Register Illustration"
                    class="w-full max-w-[250px]">
            </div>
        </div>

        {{-- Link ke login --}}
        <div class="mt-8">
            <a class="text-sm text-gray-600 hover:text-gray-900 hover:underline rounded-md" href="{{ route('login') }}">
                {{ __('Sudah punya akun? Masuk') }}
            </a>
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-[#0B3C6A] text-white pt-12 pb-10 mt-8">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 md:px-8 flex flex-col items-center">
            <div class="mb-8">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Logo Diskominfo Footer"
                    class="h-16" />
            </div>
            <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                <div class="space-y-2 text-sm text-white/80 leading-relaxed">
                    <p class="font-bold text-white text-base">Dinas Komunikasi, Informatika dan Statistik Provinsi
                        Lampung</p>
                    <p>Alamat : Jl. WR Monginsidi No.69 Bandar Lampung</p>
                    <p>Telepon : (0721) 481107</p>
                    <p>Facebook : www.facebook.com/diskominfo.lpg</p>
                    <p>Instagram : www.instagram.com/diskominfotiklampung</p>
                </div>
                <div class="md:mx-auto">
                    <h4 class="font-bold text-white text-base mb-4">Menu</h4>
                    <ul class="space-y-2 text-sm text-white/80">
                        <li><a href="{{ route('home') }}" class="hover:underline hover:text-white">Home</a></li>
                        <li><a href="{{ route('about') }}" class="hover:underline hover:text-white">Tentang Kami</a>
                        </li>
                        <li><a href="{{ route('kegiatan') }}" class="hover:underline hover:text-white">Kegiatan</a></li>
                        <li><a href="{{ route('dokumen') }}" class="hover:underline hover:text-white">Dokumen</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Kontak</a></li>
                    </ul>
                </div>
                <div class="md:ml-auto md:text-right">
                    <h4 class="font-bold text-white text-base mb-4">Ikuti Kami</h4>
                    <div class="flex items-center justify-center md:justify-end gap-3">
                        <a href="https://www.facebook.com/share/175mUXN9ow/?mibextid=wwXIfr" target="_blank"
                            rel="noopener"
                            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full hover:bg-white/20 transition-colors"><i
                                class="fab fa-facebook-f text-white"></i></a>
                        <a href="https://www.instagram.com/diskominfotik.lampung?igsh=MTRqb3VlOWxzbG9yeQ=="
                            target="_blank" rel="noopener"
                            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full hover:bg-white/20 transition-colors"><i
                                class="fab fa-instagram text-white"></i></a>
                        <a href="https://youtube.com/@diskominfotiklampung?si=9-Py4fdTCy2hOeBX" target="_blank"
                            rel="noopener"
                            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full hover:bg-white/20 transition-colors"><i
                                class="fab fa-youtube text-white"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- JS kecil --}}
    <script>
    // Toggle Password
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyePassword = document.getElementById('eyePassword');
    let isPasswordVisible = false;

    togglePassword?.addEventListener('click', function() {
        isPasswordVisible = !isPasswordVisible;
        passwordInput.type = isPasswordVisible ? 'text' : 'password';
        eyePassword.innerHTML = isPasswordVisible ?
            `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.06 10.06 0 012.98-4.362m1.97-1.643A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.961 9.961 0 01-4.234 5.146M15 12a3 3 0 11-6 0 3 3 0 016 0z" /> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />` :
            `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
    });

    // Toggle Confirm Password
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const eyePasswordConfirm = document.getElementById('eyePasswordConfirm');
    let isPasswordConfirmVisible = false;

    togglePasswordConfirm?.addEventListener('click', function() {
        isPasswordConfirmVisible = !isPasswordConfirmVisible;
        passwordConfirmInput.type = isPasswordConfirmVisible ? 'text' : 'password';
        eyePasswordConfirm.innerHTML = isPasswordConfirmVisible ?
            `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.06 10.06 0 012.98-4.362m1.97-1.643A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.961 9.961 0 01-4.234 5.146M15 12a3 3 0 11-6 0 3 3 0 016 0z" /> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />` :
            `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
    });
    </script>
</body>

</html>