<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Manajemen Agenda Pimpinan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif

            <div class="mb-4">
                <a href="{{ route('sekretaris.agenda.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah Agenda</a>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <table class="min-w-full border">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">#</th>
                            <th class="border px-4 py-2">Nama Agenda</th>
                            <th class="border px-4 py-2">Tanggal</th>
                            <th class="border px-4 py-2">Waktu</th>
                            <th class="border px-4 py-2">Pimpinan</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agendas as $agenda)
                            <tr>
                                <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2">{{ $agenda->nama_agenda }}</td>
                                <td class="border px-4 py-2">{{ $agenda->date_agenda }}</td>
                                <td class="border px-4 py-2">{{ $agenda->waktu_agenda }} - {{ $agenda->waktu_selesai }}</td>
                                <td class="border px-4 py-2">{{ $agenda->pengguna->name ?? '-' }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('sekretaris.agenda.edit', $agenda->id) }}" class="text-blue-500">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                        @if($agendas->isEmpty())
                            <tr><td colspan="6" class="text-center py-4">Belum ada agenda.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
