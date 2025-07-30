@php
use Carbon\Carbon;
$carbon = Carbon::now('Asia/Jakarta')->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
$waktu = $carbon->format('H:i:s') . ' WIB';
@endphp

<x-app-layout>
    <div class="bg-[#eaf5ff] min-h-screen w-full flex flex-col">
        <!-- HEADER -->
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Agenda</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari Agenda..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                    <!-- Profile dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
                                        Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BODY GRID -->
        <div class="flex flex-col lg:flex-row gap-8 px-4 md:px-12 pt-8 pb-10 flex-1 w-full max-w-7xl mx-auto">
            <!-- LIST PIMPINAN DAN AGENDA -->
            <div class="flex-1">
                <div class="mb-6">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-700 mb-1">Daftar Agenda Pimpinan Hari Ini</h3>
                    <span class="text-base text-[#2563a9] font-mono">{{ $waktu }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($users as $user)
                    <div class="bg-white rounded-2xl shadow flex items-center gap-6 p-6 hover:shadow-lg transition">
                        <!-- Avatar -->
                        <div
                            class="flex-shrink-0 flex items-center justify-center w-20 h-20 rounded-full bg-[#c9ecd7] border">
                            @if(!empty($user->photo_url))
                            <img src="{{ asset('storage/' . $user->photo_url) }}" alt="Foto {{ $user->decrypted_name }}"
                                class="w-20 h-20 object-cover rounded-full">
                            @else
                            <img src="{{ asset('assets/img/avatar_icon.svg') }}"
                                alt="Avatar {{ $user->decrypted_name }}" class="w-16 h-16 rounded-full object-cover">
                            @endif
                        </div>
                        <!-- Info -->
                        <div class="flex-1">
                            <div class="font-bold text-lg text-gray-900 leading-tight">{{ $user->decrypted_name }}</div>
                            <div class="text-gray-500 text-sm mb-1">{{ $user->role->nama_role ?? '-' }}</div>
                            @if($user->agenda && $user->agenda->count() > 0)
                            @foreach($user->agenda as $agenda)
                            <div class="text-sm text-[#2563a9] font-semibold mb-1">
                                {{ \Carbon\Carbon::parse($agenda->date_agenda)->format('H:i') }} -
                                {{ $agenda->waktu_selesai }}
                            </div>
                            <div class="text-gray-800 text-sm mb-1">{{ $agenda->nama_agenda }}</div>
                            @endforeach
                            @else
                            <div class="italic text-gray-400 text-sm">Tidak ada agenda hari ini</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- SIDEBAR KANAN -->
            <aside class="w-full lg:w-80 flex flex-col gap-6 mt-8 lg:mt-0">
                <!-- Kartu Role -->
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'Sekretaris' }}
                        </p>
                    </div>
                </div>
                <!-- Tombol Tambah Agenda -->
                @if (Auth::check() && Auth::user()->roleGroup && Auth::user()->roleGroup->nama_role_group === 'sekretaris')
    <!-- Tombol Tambah Agenda -->
    <a href="{{ route('sekretaris.agenda.create') }}"
        class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition text-base">
        <i class="fa-solid fa-plus"></i> Tambah Agenda
    </a>
@endif

            </aside>
        </div>
    </div>
    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                    class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>
</x-app-layout>