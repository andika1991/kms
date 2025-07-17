<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @hasSection('title')
            @yield('title') | KMS Diskominfotik Lampung
        @else
            KMS Diskominfotik Lampung
        @endif
    </title>

    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    @stack('styles')

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    {{-- 1. Tambahkan state management Alpine.js di sini --}}
    <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-[#f8fafc]">
        
        {{-- Sidebar Navigasi --}}
        @if (Auth::check())
        @php
        $roleGroup = Auth::user()->role->role_group ?? '';
        @endphp

        @if ($roleGroup === 'admin')
        @include('layouts.navigation-admin')
        @elseif ($roleGroup === 'magang')
        @include('layouts.navigation-magang')
        @elseif ($roleGroup === 'kepalabagian')
        @include('layouts.navigation-kepalabagian')
         @elseif ($roleGroup === 'pegawai')
        @include('layouts.navigation-pegawai')
        @else
        @include('layouts.navigation-default')
        @endif
        @endif

        {{-- Konten Utama --}}
        <div class="lg:ml-64">
            {{-- 2. Tambahkan Header Mobile dengan Tombol Hamburger --}}
            <header class="sticky top-0 z-10 lg:hidden bg-white shadow-sm">
                <div class="flex items-center justify-between p-4 border-b">
                    <a href="/">
                        <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" class="h-8" alt="Logo">
                    </a>
                    {{-- Tombol ini akan mengubah state 'sidebarOpen' --}}
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <footer>
                {{ $footer ?? '' }}
            </footer>
            
        </div>
    </div>
    @stack('scripts')
</body>

</html>