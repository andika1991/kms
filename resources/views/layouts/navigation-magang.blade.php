{{-- AlpineJS harus sudah di-load di layout utama --}}
<div x-data="{ sidebarOpen: false }">
    {{-- Sidebar - Desktop --}}
    <aside class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 flex-col hidden lg:flex">
        {{-- Logo --}}
        <div class="flex items-center justify-center h-20 px-6 flex-shrink-0 border-b border-gray-200">
            <a href="{{ route('magang.dashboard') }}">
                <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" class="h-10" alt="Logo">
            </a>
        </div>
        {{-- Nav Links --}}
        <nav class="flex-1 px-4 py-4 space-y-2">
            <a href="{{ route('magang.dashboard') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                    {{ request()->routeIs('magang.dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fa-solid fa-house w-6 text-center {{ request()->routeIs('magang.dashboard') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
                <span>Dashboard Magang</span>
            </a>
            <a href="{{ route('magang.berbagipengetahuan.index') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                    {{ request()->routeIs('magang.berbagipengetahuan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fa-solid fa-share-nodes w-6 text-center {{ request()->routeIs('magang.berbagipengetahuan.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
                <span>Berbagi Pengetahuan</span>
            </a>
            <a href="{{ route('magang.kegiatan.index') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                    {{ request()->routeIs('magang.kegiatan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fa-solid fa-list-check w-6 text-center {{ request()->routeIs('magang.kegiatan.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
                <span>Kegiatan</span>
            </a>
            <a href="{{ route('magang.manajemendokumen.index') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                    {{ request()->routeIs('magang.manajemendokumen.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fa-solid fa-folder-open w-6 text-center {{ request()->routeIs('magang.manajemendokumen.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
                <span>Manajemen Dokumen</span>
            </a>
            <a href="{{ route('magang.forum.index') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                    {{ request()->routeIs('magang.forum.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fa-solid fa-comments w-6 text-center {{ request()->routeIs('magang.forum.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
                <span>Forum Diskusi</span>
            </a>
        </nav>
    </aside>

    {{-- Topbar - Mobile --}}
    <nav class="lg:hidden bg-white border-b border-gray-200 px-4 py-2 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <button @click="sidebarOpen = true" class="text-gray-600 focus:outline-none">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>
            <a href="{{ route('magang.dashboard') }}">
                <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" class="h-8" alt="Logo">
            </a>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm font-semibold text-gray-600">{{ Auth::user()->name }}</span>
            <i class="fa-solid fa-user-circle text-2xl text-gray-400"></i>
        </div>
    </nav>

    {{-- Drawer Sidebar - Mobile --}}
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-black/40" @click="sidebarOpen = false"></div>
    <aside x-show="sidebarOpen" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 p-4 flex flex-col space-y-2"
        @click.away="sidebarOpen = false" x-transition>
        <div class="flex items-center justify-between h-16 mb-6">
            <a href="{{ route('magang.dashboard') }}">
                <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" class="h-8" alt="Logo">
            </a>
            <button @click="sidebarOpen = false" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>
        <a href="{{ route('magang.dashboard') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                {{ request()->routeIs('magang.dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-house w-6 text-center {{ request()->routeIs('magang.dashboard') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Dashboard Magang</span>
        </a>
        <a href="{{ route('magang.berbagipengetahuan.index') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                {{ request()->routeIs('magang.berbagipengetahuan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-share-nodes w-6 text-center {{ request()->routeIs('magang.berbagipengetahuan.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Berbagi Pengetahuan</span>
        </a>
        <a href="{{ route('magang.kegiatan.index') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                {{ request()->routeIs('magang.kegiatan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-list-check w-6 text-center {{ request()->routeIs('magang.kegiatan.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Kegiatan</span>
        </a>
        <a href="{{ route('magang.manajemendokumen.index') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                {{ request()->routeIs('magang.manajemendokumen.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-folder-open w-6 text-center {{ request()->routeIs('magang.manajemendokumen.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Manajemen Dokumen</span>
        </a>
        <a href="{{ route('magang.forum.index') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                {{ request()->routeIs('magang.forum.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-comments w-6 text-center {{ request()->routeIs('magang.forum.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Forum Diskusi</span>
        </a>
    </aside>
</div>