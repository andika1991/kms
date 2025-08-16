<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Knowledge Management System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-figtree bg-gray-100"
    style="background-image: url('{{ asset('assets/img/body-bg-pattern.png') }}'); background-repeat: repeat; background-size: auto;">

    <div class="flex flex-col min-h-screen">

        {{-- HEADER --}}
        <header class="bg-white shadow-md sticky top-0 z-20 rounded-b-3xl">
            <div class="max-w-[1200px] mx-auto flex items-center justify-between px-6 py-3">
                <a href="/">
                    <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" alt="KMS DISKOMINFOTIK" class="h-9">
                </a>
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
                {{-- Tombol Masuk --}}
                <a href="{{ route('login') }}"
                    class="bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded-lg shadow hover:bg-blue-800 transition">
                    Masuk
                </a>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="flex-grow flex flex-col items-center justify-center px-6 py-12">

            {{-- KARTU LOGIN --}}
            <div
                class="w-full max-w-4xl bg-white shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

                {{-- Bagian Kiri (Ilustrasi) --}}
                <div class="bg-[#406A8D] p-10 flex flex-col items-center justify-center rounded-l-2xl">
                    <img src="{{ asset('assets/img/login_figure.png') }}" alt="Login Illustration"
                        class="w-full max-w-[250px]">
                </div>

                {{-- Bagian Kanan (Form) --}}
                <div class="p-12">
                    <h1 class="text-2xl font-bold text-gray-800 mb-8">Selamat Datang</h1>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <div class="relative mb-5">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                {{-- Ikon User --}}
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username"
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Email">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="relative mb-5">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                {{-- Ikon Gembok --}}
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password"
                                class="block w-full pl-10 pr-12 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Password">
                            {{-- Ikon Show/Hide Password --}}
                            <button type="button" id="togglePassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600 focus:outline-none"
                                tabindex="-1">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-700 text-white font-semibold py-2.5 rounded-lg shadow-md hover:bg-blue-800 transition mt-4">
                            Masuk
                        </button>
                    </form>
                </div>
            </div>

            {{-- Link Daftar --}}
            <div class="mt-8 flex items-center justify-center gap-4 bg-white px-6 py-4 rounded-lg shadow-lg">
                <p class="text-sm text-gray-600">Belum punya akun?</p>
                <a href="{{ route('register') }}"
                    class="text-sm font-semibold bg-gray-200 text-gray-700 px-8 py-2 rounded-lg hover:bg-gray-300 transition">Daftar</a>
            </div>
        </main>

        {{-- FOOTER --}}
        <footer class="bg-[#0B3C6A] text-white pt-12 pb-10 mt-8">
            <div class="max-w-[1200px] mx-auto px-4 sm:px-6 md:px-8 flex flex-col items-center">

                {{-- Logo Tengah Atas --}}
                <div class="mb-8">
                    <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Logo Diskominfo Footer"
                        class="h-16" />
                </div>

                {{-- Konten Tiga Kolom --}}
                <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">

                    {{-- Kolom 1: Informasi Kontak --}}
                    <div class="space-y-2 text-sm text-white/80 leading-relaxed">
                        <p class="font-bold text-white text-base">Dinas Komunikasi, Informatika dan Statistik Provinsi
                            Lampung</p>
                        <p>Alamat : Jl. WR Monginsidi No.69 Bandar Lampung</p>
                        <p>Telepon : (0721) 481107</p>
                        <p>Facebook : www.facebook.com/diskominfo.lpg</p>
                        <p>Instagram : www.instagram.com/diskominfotiklampung</p>
                    </div>

                    {{-- Kolom 2: Menu Navigasi --}}
                    <div class="md:mx-auto">
                        <h4 class="font-bold text-white text-base mb-4">Menu</h4>
                        <ul class="space-y-2 text-sm text-white/80">
                            <li><a href="{{ route('home') }}" class="hover:underline hover:text-white">Home</a></li>
                            <li><a href="{{ route('about') }}" class="hover:underline hover:text-white">Tentang Kami</a>
                            </li>
                            <li><a href="{{ route('kegiatan') }}" class="hover:underline hover:text-white">Kegiatan</a>
                            </li>
                            <li><a href="{{ route('dokumen') }}" class="hover:underline hover:text-white">Dokumen</a>
                            </li>
                            <li><a href="#" class="hover:underline hover:text-white">Kontak</a></li>
                        </ul>
                    </div>

                    {{-- Kolom 3: Media Sosial --}}
                    <div class="md:ml-auto md:text-right">
                        <h4 class="font-bold text-white text-base mb-4">Ikuti Kami</h4>
                        <div class="flex items-center justify-center md:justify-end gap-3">
                            {{-- Facebook --}}
                            <a href="https://www.facebook.com/share/175mUXN9ow/?mibextid=wwXIfr" target="_blank"
                                rel="noopener"
                                class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full hover:bg-white/20 transition-colors">
                                <i class="fab fa-facebook-f text-white"></i>
                            </a>
                            {{-- Instagram --}}
                            <a href="https://www.instagram.com/diskominfotik.lampung?igsh=MTRqb3VlOWxzbG9yeQ=="
                                target="_blank" rel="noopener"
                                class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full hover:bg-white/20 transition-colors">
                                <i class="fab fa-instagram text-white"></i>
                            </a>
                            {{-- Youtube --}}
                            <a href="https://youtube.com/@diskominfotiklampung?si=9-Py4fdTCy2hOeBX" target="_blank"
                                rel="noopener"
                                class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full hover:bg-white/20 transition-colors">
                                <i class="fab fa-youtube text-white"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div>

</body>

<script>
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
const eyeIcon = document.getElementById('eyeIcon');
let isVisible = false;

if (togglePassword && passwordInput && eyeIcon) {
    togglePassword.addEventListener('click', function() {
        isVisible = !isVisible;
        passwordInput.type = isVisible ? 'text' : 'password';
        // Ganti ikon (bisa custom, berikut simpel tanpa import ikon baru)
        eyeIcon.innerHTML = isVisible ?
            `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.06 10.06 0 012.98-4.362m1.97-1.643A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.961 9.961 0 01-4.234 5.146M15 12a3 3 0 11-6 0 3 3 0 016 0z" /> 
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />` :
            `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
    });
}
</script>

</html>