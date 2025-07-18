<aside class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 flex-col hidden lg:flex">
    
    {{-- Logo --}}
    <div class="flex items-center justify-center h-20 px-6 flex-shrink-0 border-b border-gray-200">
        <a href="/">
            <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" class="h-10" alt="Logo">
        </a>
    </div>

    {{-- Nav Links --}}
    <nav class="flex-1 px-4 py-4 space-y-2">
        
        {{-- Link Dashboard --}}
        <a href="{{ route('sekretaris.dashboard') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition 
                  {{ request()->routeIs('kasubbidang.dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-house w-6 text-center {{ request()->routeIs('kepalabagian.dashboard') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Dashboard</span>
        </a>

        
        {{-- Link Artikel Pengetahuan --}}
        <a href="{{ route('sekretaris.berbagipengetahuan.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                  {{ request()->routeIs('kasubbidang.berbagipengetahuan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-file-alt w-6 text-center {{ request()->routeIs('kepalabagian.artikelpengetahuan.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Artikel Pengetahuan</span>
        </a>

        
 
        
        {{-- Link Manajemen Dokumen --}}
        <a href="{{ route('kasubbidang.manajemendokumen.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                  {{ request()->routeIs('kasubbidang.manajemendokumen.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            
            {{-- PERBAIKAN: Operator ternary diperbaiki di baris ini --}}
            <i class="fa-solid fa-folder-open w-6 text-center {{ request()->routeIs('kepalabagian.manajemendokumen.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            
            <span>Manajemen Dokumen</span>
        </a>

        {{-- Link Forum Diskusi --}}
        <a href="{{ route('kasubbidang.forum.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                  {{ request()->routeIs('kasubbidang.forum.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-comments w-6 text-center {{ request()->routeIs('kepalabagian.forum.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Forum Diskusi</span>
        </a>



       <a href="{{ route('sekretaris.agenda.index') }}"
   class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
          {{ request()->routeIs('sekretaris.agenda.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
    <i class="fa-solid fa-users-gear w-6 text-center {{ request()->routeIs('manajemenpengguna.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
    <span>Manajemen Agenda</span>
</a>

    </nav>
</aside>