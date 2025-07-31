@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'View Forum Diskusi Kasubbidang')

<x-app-layout>
    <div class="bg-[#eaf5ff] min-h-screen w-full flex flex-col">
        <!-- HEADER -->
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Forum Diskusi</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari Forum..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                    {{-- Dropdown Profile --}}
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
            <!-- MAIN FORUM CHAT BOX -->
            <div class="flex-1 bg-white rounded-2xl shadow-xl px-0 sm:px-8 py-8 flex flex-col min-h-[600px]">
                <!-- Judul Forum & Anggota -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6 px-6">
                    <div class="w-full">
                        {{-- Nama Anggota di atas --}}
                        <div class="flex flex-wrap gap-2 text-sm mb-1 justify-center md:justify-start">
                            @forelse($anggota as $user)
                            <span
                                class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">{{ $user->name }}</span>
                            @empty
                            <span class="text-gray-400">Belum ada anggota.</span>
                            @endforelse
                        </div>
                        {{-- Nama Forum di tengah --}}
                        <h3 class="text-lg sm:text-xl font-bold text-blue-700 text-center mt-1">
                            {{ $grupChat->nama_grup }}
                        </h3>
                    </div>
                </div>
                <!-- Box Info Topik Forum -->
                <div class="rounded-xl bg-blue-700/90 text-white px-6 py-5 font-medium mb-6 mx-6">
                    {{ $grupChat->deskripsi ?? 'Diskusi topik dan update seputar grup.' }}
                </div>
                <!-- Chat Messages -->
                <div id="chat-messages"
                    class="flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-[#f6fafd] border border-gray-200 rounded-2xl mb-3"
                    style="max-height: 430px;">
                    @forelse($messages as $message)
                    <div class="flex {{ $message->pengguna_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        {{-- Icon user lain di kiri --}}
                        @if($message->pengguna_id !== auth()->id())
                        <div class="flex-shrink-0 mr-2">
                            <div class="w-8 h-8 rounded-full bg-blue-200 flex items-center justify-center">
                                <i class="fa-solid fa-user text-blue-600 text-lg"></i>
                            </div>
                        </div>
                        @endif
                        <div
                            class="max-w-[70%] {{ $message->pengguna_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-900' }} rounded-xl p-4 shadow relative">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-xs">
                                    {{ $message->pengguna?->name ?? 'User' }}
                                </span>
                                <span
                                    class="text-[10px] {{ $message->pengguna_id === auth()->id() ? 'text-blue-200' : 'text-blue-600' }}">
                                    {{ $message->created_at->format('d M Y H:i') }}
                                </span>
                            </div>
                            <div class="text-sm leading-normal">
                                {{ $message->decrypted_message }}
                            </div>
                            @if($message->file)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $message->file) }}"
                                    class="underline text-xs {{ $message->pengguna_id === auth()->id() ? 'text-white' : 'text-blue-800' }}"
                                    target="_blank">📎 Lihat Lampiran</a>
                            </div>
                            @endif
                        </div>
                        {{-- Icon user sendiri di kanan --}}
                        @if($message->pengguna_id === auth()->id())
                        <div class="flex-shrink-0 ml-2">
                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center">
                                <i class="fa-solid fa-user text-white text-lg"></i>
                            </div>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-center text-gray-500">Belum ada pesan di grup ini.</div>
                    @endforelse
                </div>
                <!-- Form Input Chat -->
                <form method="POST" action="{{ route('kasubbidang.grupchat.pesan.store', $grupChat->id) }}"
                    enctype="multipart/form-data" class="flex flex-col sm:flex-row items-end gap-2 px-6">
                    @csrf
                    <div class="flex-1 flex flex-col sm:flex-row gap-2">
                        <input type="text" name="message"
                            class="flex-1 rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 text-sm"
                            placeholder="Tulis pesan..." autocomplete="off">
                        <input type="file" name="file" class="hidden" id="file-input">
                        <button type="button" onclick="document.getElementById('file-input').click()"
                            class="bg-gray-200 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-xl text-xl transition">
                            <i class="fa-solid fa-paperclip"></i>
                        </button>
                    </div>
                    <div class="sm:ml-0">
                        <button type="submit"
                            class="px-6 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow transition text-base w-full sm:w-auto">
                            Kirim
                        </button>
                    </div>
                    <p id="file-name" class="text-xs text-gray-600 mt-2 ml-2"></p>
                </form>
            </div>

            <!-- SIDEBAR KANAN -->
            <aside class="w-full lg:w-80 flex flex-col gap-6 mt-8 lg:mt-0">
                <!-- Kartu Role/Info -->
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight">Bidang {{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}
                        </p>
                    </div>
                </div>
                <!-- Kartu Forum List -->
                <div class="bg-[#2563a9] text-white rounded-2xl shadow-lg p-7">
                    <h3 class="font-bold text-lg mb-3">Forum</h3>
                    <ul class="space-y-2 text-left">
                        @foreach($forumList as $forum)
                        <li>
                            <a href="{{ route('kasubbidang.forum.show', $forum->id) }}"
                                class="hover:underline text-sm block {{ $forum->id == $grupChat->id ? 'font-semibold underline' : '' }}">
                                # {{ $forum->nama_grup }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <!-- Tombol Tambah Forum -->
                <a href="{{ route('kasubbidang.forum.create') }}"
                    class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Forum</span>
                </a>
                <!-- Deskripsi Forum Diskusi -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Forum Diskusi merupakan fitur untuk mempermudah pegawai Dinas Komunikasi, Informatika dan
                        Statistik Provinsi Lampung dapat saling berbagi pengetahuan dan mempermudah komunikasi dengan
                        manajemen yang terstruktur dan mudah diakses.
                    </p>
                </div>
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

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        let chatBox = document.getElementById('chat-messages');
        chatBox.scrollTop = chatBox.scrollHeight;

        const fileInput = document.getElementById('file-input');
        const fileNameDisplay = document.getElementById('file-name');

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileNameDisplay.textContent = "📎 " + this.files[0].name;
            } else {
                fileNameDisplay.textContent = "";
            }
        });
    });
    </script>
</x-app-layout>