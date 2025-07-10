<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Knowledge Management System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-figtree bg-gray-100" style="background-image: url('{{ asset('assets/img/body-bg-pattern.png') }}'); background-repeat: repeat; background-size: auto;">

    {{-- WRAPPER UTAMA UNTUK SELURUH HALAMAN --}}
    <div class="flex flex-col min-h-screen">

        {{-- HEADER --}}
        <header class="w-full px-6 py-4">
            <div class="max-w-6xl mx-auto bg-white/90 backdrop-blur-sm shadow-lg rounded-full flex items-center justify-between p-2">
                <a href="/">
                    <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" alt="KMS DISKOMINFOTIK" class="h-9 ml-4">
                </a>
                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-gray-600 text-sm hover:text-blue-700 transition">Beranda</a>
                    <a href="{{ route('about') }}" class="text-gray-600 text-sm hover:text-blue-700 transition">Tentang Kami</a>
                    <a href="{{ route('pengetahuan') }}" class="text-gray-600 text-sm hover:text-blue-700 transition">Pengetahuan</a>
                    <a href="{{ route('dokumen') }}" class="text-gray-600 text-sm hover:text-blue-700 transition">Dokumen</a>
                </nav>
                {{-- Tombol Masuk di-highlight sebagai halaman aktif --}}
                <a href="{{ route('login') }}" class="bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded-full shadow-md hover:bg-blue-800 transition">Masuk</a>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="flex-grow flex flex-col items-center justify-center px-6 py-12">

            {{-- KARTU LOGIN --}}
            <div class="w-full max-w-4xl bg-white shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">
                
                {{-- Bagian Kiri (Ilustrasi) --}}
                <div class="bg-[#406A8D] p-10 flex flex-col items-center justify-center rounded-l-2xl">
                    <img src="{{ asset('assets/img/login_figure.png') }}" alt="Login Illustration" class="w-full max-w-[250px]">
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
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                   class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Email">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="relative mb-5">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                {{-- Ikon Gembok --}}
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                   class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-2.5 rounded-lg shadow-md hover:bg-blue-800 transition mt-4">
                            Masuk
                        </button>
                    </form>
                </div>
            </div>

            {{-- Link Daftar --}}
            <div class="mt-8 flex items-center justify-center gap-4 bg-white px-6 py-4 rounded-lg shadow-lg">
                <p class="text-sm text-gray-600">Belum punya akun?</p>
                <a href="{{ route('register') }}" class="text-sm font-semibold bg-gray-200 text-gray-700 px-8 py-2 rounded-lg hover:bg-gray-300 transition">Daftar</a>
            </div>
        </main>

        {{-- FOOTER --}}
        <footer class="bg-[#0B3C6A] text-white pt-10 pb-8">
            <div class="max-w-[1200px] mx-auto grid md:grid-cols-3 gap-8 px-6 text-sm">
                <div class="space-y-3">
                    <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" alt="Logo Diskominfo" class="h-10">
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
                        <li><a href="#" class="hover:underline hover:text-white">Pengetahuan</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Dokumen</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Kontak</a></li>
                    </ul>
                </div>
                <div class="md:mx-auto">
                    <h4 class="font-bold mb-4">Ikuti Kami</h4>
                    <div class="flex items-center gap-4">
                        <a href="#" class="hover:opacity-80 transition"><img src="{{ asset('assets/img/facebook-icon.svg') }}" alt="Facebook" class="h-8"></a>
                        <a href="#" class="hover:opacity-80 transition"><img src="{{ asset('assets/img/instagram-icon.svg') }}" alt="Instagram" class="h-8"></a>
                        <a href="#" class="hover:opacity-80 transition"><img src="{{ asset('assets/img/youtube-icon.svg') }}" alt="YouTube" class="h-8"></a>
                    </div>
                </div>
            </div>
        </footer>

    </div>

</body>
</html>