@php
    use Carbon\Carbon;
    $carbon = Carbon::now()->locale('id');
    $carbon->settings(['formatFunction' => 'translatedFormat']);
    $tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Manajemen Agenda Sekretaris')

{{-- ALERT Sukses --}}
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        position: 'top',
        icon: 'success',
        title: @json(session('success')),
        showConfirmButton: false,
        background: '#f0fff4',
        customClass: {
            popup: 'rounded-xl shadow-md px-8 py-5',
            title: 'font-bold text-base md:text-lg text-green-800',
            icon: 'text-green-500'
        },
        timer: 2200
    });
});
</script>
@endif

{{-- ALERT Terhapus --}}
@if (session('deleted'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        position: 'top',
        icon: 'error',
        title: @json(session('deleted')),
        showConfirmButton: false,
        background: '#fff0f0',
        customClass: {
            popup: 'rounded-xl shadow-md px-8 py-5 border border-red-200',
            title: 'font-bold text-base md:text-lg text-red-800',
            icon: 'text-red-600'
        },
        timer: 2500
    });
});
</script>
@endif

<x-app-layout>
    <div class="bg-[#eaf5ff] min-h-screen w-full flex flex-col">
        <!-- HEADER -->
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
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
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BODY -->
        <div class="flex flex-col lg:flex-row gap-8 px-4 md:px-12 pt-8 pb-10 flex-1 w-full max-w-7xl mx-auto">
            <!-- TABEL AGENDA (tanpa pembungkus putih) -->
            <div class="flex-1 overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm md:text-base rounded-2xl overflow-hidden shadow-lg border border-gray-200 bg-white">
                    <thead>
                        <tr class="bg-[#2563a9] text-white">
                            <th class="px-6 py-4 font-semibold">Tanggal dan Waktu</th>
                            <th class="px-6 py-4 font-semibold">Nama Agenda</th>
                            <th class="px-6 py-4 font-semibold">Pimpinan</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agendas as $agenda)
                            <tr class="odd:bg-white even:bg-[#f5f7fa]">
                                <td class="px-6 py-4 align-top whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($agenda->date_agenda)->translatedFormat('l, d F Y') }}<br>
                                    <span class="text-xs md:text-sm text-gray-500">Pukul {{ $agenda->waktu_agenda }} - {{ $agenda->waktu_selesai }}</span>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    {{ $agenda->nama_agenda }}
                                </td>
                                <td class="px-6 py-4 align-top">
                                    {{ $agenda->pengguna->name ?? '-' }}<br>
                                    <span class="text-xs md:text-sm text-gray-500">({{ $agenda->pengguna->role->nama_role ?? '-' }})</span>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('sekretaris.agenda.edit', $agenda->id) }}"
                                           class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-yellow-400 hover:bg-yellow-500 text-white shadow transition"
                                           title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        {{-- ganti confirm bawaan dengan sweetalert --}}
                                        <form action="{{ route('sekretaris.agenda.destroy', $agenda->id) }}" method="POST" class="inline form-hapus">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-500 hover:bg-red-600 text-white shadow transition"
                                                    title="Hapus">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-500">Belum ada agenda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- SIDEBAR KANAN -->
            <aside class="w-full lg:w-80 flex flex-col gap-6 mt-8 lg:mt-0">
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight">Bidang {{ Auth::user()->role->nama_role ?? 'Sekretaris' }}</p>
                    </div>
                </div>

                <a href="{{ route('sekretaris.agenda.create') }}"
                   class="w-full flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Agenda
                </a>

                <a href="{{ route('sekretaris.all_users') }}"
                   class="w-full flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-[#2563a9] hover:bg-[#1e4776] text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-users"></i>
                    Lihat Semua Pimpinan & Agenda Hari Ini
                </a>

                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Manajemen Agenda mempermudah pegawai dalam melihat, menambah, dan mengelola jadwal kegiatan pimpinan secara terstruktur & mudah diakses.
                    </p>
                </div>
            </aside>
        </div>
    </div>

    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik" class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>

    {{-- SweetAlert2 Confirm Delete --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form.form-hapus').forEach((form) => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();

                Swal.fire({
                    title: 'Hapus agenda ini?',
                    text: 'Tindakan ini tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-2xl p-8',
                        title: 'mb-1',
                        // jarak tombol dan responsif
                        actions: 'flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 w-full',
                        confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-2 rounded-lg w-full sm:w-auto',
                        cancelButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2 rounded-lg w-full sm:w-auto',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
    </script>
</x-app-layout>
