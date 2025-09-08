@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Forum Diskusi Magang')

{{-- Toast sukses --}}
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.4/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
        position: 'top',
        icon: 'success',
        title: @json(session('success')),
        showConfirmButton: false,
        background: '#f0fff4',
        customClass: {
            popup: 'rounded-xl shadow px-6 py-4',
            title: 'font-bold text-green-800'
        },
        timer: 2200
    });
});
</script>
@endif

{{-- Toast terhapus --}}
@if (session('deleted'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.4/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
        position: 'top',
        icon: 'error',
        title: @json(session('deleted')),
        showConfirmButton: false,
        background: '#fff0f0',
        customClass: {
            popup: 'rounded-xl shadow px-6 py-4 border border-red-200',
            title: 'font-bold text-red-800'
        },
        timer: 2400
    });
});
</script>
@endif

<x-app-layout>
    <div class="bg-[#eaf5ff] min-h-screen w-full">

        {{-- HEADER --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Forum Diskusi</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <label class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari Forum..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </label>

                    {{-- Profile --}}
                    <div x-data="{open:false}" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open=false" x-transition style="display:none"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- BODY --}}
        <main class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 max-w-7xl mx-auto">

            {{-- LIST FORUM --}}
            <section class="xl:col-span-8 space-y-6">
                {{-- Tambah (mobile) --}}
                <a href="{{ route('magang.forum.create') }}"
                    class="xl:hidden w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-plus"></i> Tambah Forum
                </a>

                @forelse($grupchats as $grupchat)
                @php
                // TAMPILKAN tombol Edit/Hapus jika:
                // - ada kolom pembuat (pengguna_id) dan itu = user login; ATAU
                // - user login terdaftar sebagai anggota grup (fallback aman)
                $isOwner = isset($grupchat->pengguna_id) && $grupchat->pengguna_id == auth()->id();
                $isMember = $grupchat->users()->where('pengguna_id', auth()->id())->exists();
                $canManage = $isOwner || $isMember;
                $namaBidang = optional($grupchat->bidang)->nama_bidang ?? optional($grupchat->bidang)->nama;
                @endphp

                <article
                    class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:border-blue-300 hover:shadow-xl transition">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        {{-- AREA KLIK → SHOW --}}
                        <a href="{{ route('magang.forum.show', $grupchat->id) }}" class="group flex-1">
                            <h3
                                class="font-bold text-lg md:text-xl text-gray-800 mb-1 group-hover:text-blue-700 transition">
                                {{ $grupchat->nama_grup }}
                            </h3>
                            <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $grupchat->deskripsi }}</p>

                            {{-- Meta: Bidang (kiri) + Private (badge gembok) --}}
                            <div class="text-xs text-gray-600 flex items-center gap-3 flex-wrap">
                                @if($namaBidang)
                                <span>Bidang: <span class="font-semibold">{{ $namaBidang }}</span></span>
                                @endif

                                @if(!empty($grupchat->is_private))
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                                    <i class="fa-solid fa-lock text-[10px]"></i>
                                    <span class="text-[11px] font-medium">Private</span>
                                </span>
                                @endif
                            </div>
                        </a>

                        {{-- AKSI: Edit & Hapus (kuning/merah + ikon) --}}
                        @if($canManage)
                        <div class="flex gap-2 md:gap-3 mt-3 md:mt-0">
                            <a href="{{ route('magang.forum.edit', $grupchat->id) }}"
                                class="btn-edit inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-white bg-yellow-500 hover:bg-yellow-600 shadow-sm text-sm font-semibold"
                                title="Edit forum">
                                <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                                <span>Edit</span>
                            </a>

                            <form action="{{ route('magang.forum.destroy', $grupchat->id) }}" method="POST"
                                class="form-hapus inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-white bg-red-600 hover:bg-red-700 shadow-sm text-sm font-semibold"
                                    title="Hapus forum">
                                    <i class="fa-solid fa-trash text-[13px]"></i>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </article>
                @empty
                <section
                    class="flex flex-col items-center justify-center text-center py-20 bg-white rounded-2xl shadow-lg border">
                    <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" class="w-44 mb-6 opacity-50"
                        alt="Logo Diskominfotik">
                    <h3 class="text-xl font-bold text-gray-700">Belum ada forum</h3>
                    <p class="text-gray-500 mt-2">Mulai buat diskusi anda.</p>
                </section>
                @endforelse
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 space-y-6">
                <section
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fa-solid fa-comments text-4xl"></i>
                    </div>
                    <h3 class="font-bold text-lg">Forum Diskusi</h3>
                    <p class="text-xs mt-2">Diskusi, kolaborasi, dan berbagi pengetahuan peserta magang.</p>
                </section>

                <a href="{{ route('magang.forum.create') }}"
                    class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-plus"></i> Tambah Forum
                </a>

                <section class="bg-white rounded-2xl shadow-lg p-7">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Forum Diskusi mempermudah komunikasi dan knowledge sharing di Diskominfotik Lampung dengan
                        manajemen yang terstruktur dan mudah diakses.
                    </p>
                </section>
            </aside>
        </main>

        {{-- FOOTER --}}
        <x-slot name="footer">
            <footer class="bg-[#2b6cb0] py-4 mt-8">
                <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                    <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                        class="h-10 object-contain">
                </div>
            </footer>
        </x-slot>
    </div>

    {{-- SweetAlert2 konfirmasi Edit & Hapus --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.4/dist/sweetalert2.all.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Edit
        document.querySelectorAll('a.btn-edit').forEach((a) => {
            a.addEventListener('click', (e) => {
                e.preventDefault();
                const href = a.getAttribute('href');
                Swal.fire({
                    title: 'Edit forum ini?',
                    html: '<span class="text-gray-600">Anda akan diarahkan ke halaman edit.</span>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Edit',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-2xl p-8',
                        actions: 'flex justify-center gap-4',
                        confirmButton: 'bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-8 py-2 rounded-lg',
                        cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-8 py-2 rounded-lg'
                    }
                }).then(r => {
                    if (r.isConfirmed) window.location.href = href;
                });
            });
        });

        // Hapus
        document.querySelectorAll('form.form-hapus').forEach((form) => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus forum ini?',
                    text: 'Tindakan ini tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-2xl p-8',
                        actions: 'flex justify-center gap-4',
                        confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-2 rounded-lg',
                        cancelButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2 rounded-lg'
                    }
                }).then((r) => {
                    if (r.isConfirmed) form.submit();
                });
            });
        });
    });
    </script>
</x-app-layout>