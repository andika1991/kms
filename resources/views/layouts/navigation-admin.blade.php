<aside class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 flex flex-col lg:flex overflow-y-auto h-screen">
    
    {{-- Logo --}}
    <div class="flex items-center justify-center h-20 px-6 flex-shrink-0 border-b border-gray-200">
        <a href="/">
            <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" class="h-10" alt="Logo">
        </a>
    </div>

    {{-- Nav Links --}}
    <nav class="flex-1 px-4 py-4 space-y-2">
        
        {{-- Link Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition 
                  {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-house w-6 text-center {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Dashboard</span>
        </a>

        {{-- Link Artikel Pengetahuan --}}
        <a href="{{ route('admin.berbagipengetahuan.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                  {{ request()->routeIs('admin.berbagipengetahuan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-file-alt w-6 text-center {{ request()->routeIs('admin.berbagipengetahuan.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Berbagi Pengetahuan</span>
        </a>

        {{-- Link Manajemen Dokumen --}}
        <a href="{{ route('admin.manajemendokumen.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                  {{ request()->routeIs('admin.manajemendokumen.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-file-alt w-6 text-center {{ request()->routeIs('admin.manajemendokumen.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Manajemen Dokumen</span>
        </a>

        {{-- Link Manajemen Kegiatan --}}
        <a href="{{ route('admin.kegiatan.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                  {{ request()->routeIs('admin.kegiatan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-file-alt w-6 text-center {{ request()->routeIs('admin.kegiatan.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Manajemen Kegiatan</span>
        </a>

        {{-- Link Manajemen Pengguna --}}
        <a href="{{ route('admin.manajemenpengguna.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                  {{ request()->routeIs('admin.manajemenpengguna.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-file-alt w-6 text-center {{ request()->routeIs('admin.manajemenpengguna.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Manajemen Pengguna</span>
        </a>
      
        {{-- Link Forum Diskusi --}}

    </nav>
</aside>
