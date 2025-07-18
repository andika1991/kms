<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Tambah Agenda Pimpinan
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow">
            <form action="{{ route('sekretaris.agenda.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Nama Agenda</label>
                    <input type="text" name="nama_agenda" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Tanggal</label>
                    <input type="date" name="date_agenda" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Waktu Mulai</label>
                    <input type="time" name="waktu_agenda" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Pimpinan (Kadis / Kepala Bagian)</label>
                    <select name="id_pengguna" class="w-full border px-3 py-2 rounded" required>
                        <option value="">-- Pilih Pengguna --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role->nama_role }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                    <a href="{{ route('sekretaris.agenda.index') }}" class="ml-2 text-gray-700">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
