<div x-data="{ sidebarOpen: false }">
    {{-- SIDEBAR DESKTOP --}}
    <aside class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 flex-col hidden lg:flex">
        {{-- Logo --}}
        <div class="flex items-center justify-center h-20 px-6 flex-shrink-0 border-b border-gray-200">
            <a href="{{ route('home') }}">
                <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" class="h-10" alt="Logo">
            </a>
        </div>
        <nav class="flex-1 px-4 py-4 space-y-2">
            <a href="{{ route('kadis.dashboard') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition 
                    {{ request()->routeIs('kadis.dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fa-solid fa-house w-6 text-center {{ request()->routeIs('kadis.dashboard') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('kadis.berbagipengetahuan.index') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                    {{ request()->routeIs('kadis.berbagipengetahuan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fa-solid fa-file-alt w-6 text-center {{ request()->routeIs('kadis.berbagipengetahuan.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
                <span>Berbagi Pengetahuan</span>
            </a>
            <a href="{{ route('kadis.forum.index') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                    {{ request()->routeIs('kadis.forum.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fa-solid fa-comments w-6 text-center {{ request()->routeIs('kadis.forum.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
                <span>Forum Diskusi</span>
            </a>
        </nav>
    </aside>

    {{-- TOPBAR MOBILE --}}
    <nav class="lg:hidden bg-white border-b border-gray-200 px-4 py-2 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <button @click="sidebarOpen = true" class="text-gray-600 focus:outline-none">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>
            <a href="{{ route('kadis.dashboard') }}">
                <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" class="block h-8 w-auto" alt="Logo">
            </a>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm font-semibold text-gray-600">{{ Auth::user()->name }}</span>
            <i class="fa-solid fa-user-circle text-2xl text-gray-400"></i>
        </div>
    </nav>

    {{-- DRAWER SIDEBAR MOBILE --}}
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-black/40 lg:hidden" @click="sidebarOpen = false"></div>
    <aside x-show="sidebarOpen" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 p-4 flex flex-col space-y-2 lg:hidden"
        @click.away="sidebarOpen = false" x-transition>
        <div class="flex items-center justify-between h-16 mb-6">
            <a href="{{ route('kadis.dashboard') }}">
                <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" class="block h-8 w-auto" alt="Logo">
            </a>
            <button @click="sidebarOpen = false" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>
        <a href="{{ route('kadis.dashboard') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition 
                {{ request()->routeIs('kadis.dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-house w-6 text-center {{ request()->routeIs('kadis.dashboard') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('kadis.berbagipengetahuan.index') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                {{ request()->routeIs('kadis.berbagipengetahuan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-file-alt w-6 text-center {{ request()->routeIs('kadis.berbagipengetahuan.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Berbagi Pengetahuan</span>
        </a>
        <a href="{{ route('kadis.forum.index') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                {{ request()->routeIs('kadis.forum.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-comments w-6 text-center {{ request()->routeIs('kadis.forum.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Forum Diskusi</span>
        </a>
        <div class="border-t mt-4 pt-4">
            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            <a href="{{ route('profile.edit') }}" class="block py-2 text-sm text-gray-700 hover:text-blue-600">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" class="block py-2 text-sm text-gray-700 hover:text-red-600"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    Log Out
                </a>
            </form>
        </div>
    </aside>
</div>