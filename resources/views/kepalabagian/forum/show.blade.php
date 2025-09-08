@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Lihat Forum Diskusi Kepala Bagian')

<x-app-layout>
    <div class="bg-[#eaf5ff] min-h-screen w-full flex flex-col">

        {{-- HEADER --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Forum Diskusi</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search --}}
                    <label class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari Forum..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </label>

                    {{-- Profile dropdown (rata sejajar dengan search) --}}
                    <div x-data="{open:false}" class="relative">
                        <button type="button" @click="open=!open" @keydown.escape.window="open=false"
                            class="w-10 h-10 grid place-items-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile" aria-haspopup="true" :aria-expanded="open">
                            <i class="fa-solid fa-user"></i>
                        </button>

                        <nav x-cloak x-show="open" @click.outside="open=false"
                            x-transition.opacity.scale.origin.top.right
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-50">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
                                    Out</button>
                            </form>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        {{-- BODY --}}
        <main class="flex flex-col lg:flex-row gap-8 px-4 md:px-12 pt-8 pb-10 flex-1 w-full max-w-7xl mx-auto">
            {{-- MAIN CHAT BOX --}}
            <section class="flex-1 bg-white rounded-2xl shadow-xl px-0 sm:px-8 py-8 flex flex-col min-h-[600px]">
                {{-- Anggota + Judul --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6 px-6">
                    <div class="w-full">
                        <div class="flex flex-wrap gap-2 text-sm mb-1 justify-center md:justify-start">
                            @forelse($anggota as $user)
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">
                                {{ $user->name ?? $user->decrypted_name ?? 'User' }}
                            </span>
                            @empty
                            <span class="text-gray-400">Belum ada anggota.</span>
                            @endforelse
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-blue-700 text-center mt-1">
                            {{ $grupChat->nama_grup }}
                        </h3>
                    </div>
                </div>

                {{-- Box Info Topik --}}
                <p class="rounded-xl bg-blue-700/90 text-white px-6 py-5 font-medium mb-6 mx-6">
                    {{ $grupChat->deskripsi ?? 'Diskusi topik dan update seputar grup.' }}
                </p>

                {{-- Pesan --}}
                <div id="chat-messages"
                    class="flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-[#f6fafd] border border-gray-200 rounded-2xl mb-3"
                    style="max-height:430px;">
                    @forelse($messages as $message)
                    <div class="flex {{ $message->pengguna_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        {{-- Icon user lain (kiri) --}}
                        @if($message->pengguna_id !== auth()->id())
                        <span class="flex-shrink-0 mr-2 w-8 h-8 rounded-full bg-blue-200 grid place-items-center">
                            <i class="fa-solid fa-user text-blue-600 text-lg"></i>
                        </span>
                        @endif

                        <article
                            class="max-w-[70%] {{ $message->pengguna_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-900' }} rounded-xl p-4 shadow">
                            <header class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-xs">{{ $message->pengguna?->name ?? 'User' }}</span>
                                <time
                                    class="text-[10px] {{ $message->pengguna_id === auth()->id() ? 'text-blue-200' : 'text-blue-600' }}">
                                    {{ $message->created_at->format('d M Y H:i') }}
                                </time>
                            </header>
                            <p class="text-sm leading-normal">{{ $message->decrypted_message }}</p>

                            @if($message->file)
                            <p class="mt-2">
                                <a href="{{ asset('storage/' . $message->file) }}" target="_blank"
                                    class="underline text-xs {{ $message->pengguna_id === auth()->id() ? 'text-white' : 'text-blue-800' }}">
                                    📎 Lihat Lampiran
                                </a>
                            </p>
                            @endif
                        </article>

                        {{-- Icon user sendiri (kanan) --}}
                        @if($message->pengguna_id === auth()->id())
                        <span class="flex-shrink-0 ml-2 w-8 h-8 rounded-full bg-blue-600 grid place-items-center">
                            <i class="fa-solid fa-user text-white text-lg"></i>
                        </span>
                        @endif
                    </div>
                    @empty
                    <p class="text-center text-gray-500">Belum ada pesan di grup ini.</p>
                    @endforelse
                </div>

                {{-- Input pesan --}}
                <form method="POST" action="{{ route('kepalabagian.grupchat.pesan.store', $grupChat->id) }}"
                    enctype="multipart/form-data" class="flex flex-col sm:flex-row items-end gap-2 px-6">
                    @csrf
                    <div class="flex-1 flex flex-col sm:flex-row gap-2">
                        <input type="text" name="message" autocomplete="off" placeholder="Tulis pesan..."
                            class="flex-1 rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 text-sm">
                        <input type="file" name="file" id="file-input" class="hidden">
                        <button type="button" onclick="document.getElementById('file-input').click()"
                            class="bg-gray-200 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-xl text-xl transition">
                            <i class="fa-solid fa-paperclip"></i>
                        </button>
                    </div>
                    <button type="submit"
                        class="px-6 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow transition text-base w-full sm:w-auto">
                        Kirim
                    </button>
                    <span id="file-name" class="text-xs text-gray-600 sm:ml-2"></span>
                </form>
            </section>

            {{-- SIDEBAR --}}
            <aside class="w-full lg:w-80 flex flex-col gap-6 mt-8 lg:mt-0">
                <section
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 grid place-items-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'Kepala Bagian' }}
                    </p>
                </section>

                <a href="{{ route('kepalabagian.forum.create') }}"
                    class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-plus"></i><span>Tambah Forum</span>
                </a>

                @if(isset($forumList) && $forumList->count())
                <section class="bg-[#2563a9] text-white rounded-2xl shadow-lg p-7">
                    <h3 class="font-bold text-lg mb-3 text-center">Forum</h3>
                    <ul class="space-y-2">
                        @foreach($forumList as $forum)
                        <li>
                            <a href="{{ route('kepalabagian.forum.show', $forum->id) }}"
                                class="hover:underline text-sm block {{ $forum->id == $grupChat->id ? 'font-semibold underline' : '' }}">
                                # {{ $forum->nama_grup }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </section>
                @endif

                <section class="bg-white rounded-2xl shadow-lg p-6">
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Forum Diskusi mempermudah pegawai Diskominfotik Provinsi Lampung berbagi pengetahuan
                        dan berkomunikasi dengan manajemen yang terstruktur serta mudah diakses.
                    </p>
                </section>
            </aside>
        </main>
    </div>

    {{-- FOOTER --}}
    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                    class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const box = document.getElementById('chat-messages');
        if (box) box.scrollTop = box.scrollHeight;

        const fi = document.getElementById('file-input');
        const fn = document.getElementById('file-name');
        fi?.addEventListener('change', () => {
            fn.textContent = fi.files?.length ? '📎 ' + fi.files[0].name : '';
        });
    });
    </script>
</x-app-layout>