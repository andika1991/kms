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
        <a href="{{ route('kadis.dashboard') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition 
                  {{ request()->routeIs('kadis.dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-house w-6 text-center {{ request()->routeIs('kadis.dashboard') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Dashboard</span>
        </a>

        
        {{-- Link Artikel Pengetahuan --}}
        <a href="{{ route('kadis.berbagipengetahuan.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                  {{ request()->routeIs('kadis.berbagipengetahuan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-file-alt w-6 text-center {{ request()->routeIs('kadis.berbagipengetahuan.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Berbagi Pengetahuan</span>
        </a>

        
    
      
        {{-- Link Forum Diskusi --}}
        <a href="{{ route('kadis.forum.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
                  {{ request()->routeIs('kadis.forum.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fa-solid fa-comments w-6 text-center {{ request()->routeIs('kadis.forum.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
            <span>Forum Diskusi</span>
        </a>


 <a href="{{ route('all_users') }}"
       class="flex items-center gap-4 px-4 py-3 rounded-lg font-semibold text-base transition
              {{ request()->routeIs('all_users.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
        <i class="fa-solid fa-calendar-check w-6 text-center {{ request()->routeIs('agenda.*') ? 'text-white' : 'text-gray-400' }} text-lg"></i>
        <span>Agenda</span>
    </a>
   

    </nav>
</aside>