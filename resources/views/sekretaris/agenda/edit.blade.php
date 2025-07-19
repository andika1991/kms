<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Edit Agenda Pimpinan
        </h2>
    </x-slot>
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow">
            <form action="{{ route('sekretaris.agenda.update', $agenda->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Nama Agenda</label>
                    <input type="text" name="nama_agenda" class="w-full border px-3 py-2 rounded"
                           value="{{ old('nama_agenda', $agenda->nama_agenda) }}" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Tanggal</label>
                    <input type="date" name="date_agenda" class="w-full border px-3 py-2 rounded"
                           value="{{ old('date_agenda', $agenda->date_agenda) }}" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Waktu Mulai</label>
                    <input type="time" name="waktu_agenda" class="w-full border px-3 py-2 rounded"
                           value="{{ old('waktu_agenda', $agenda->waktu_agenda) }}" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" class="w-full border px-3 py-2 rounded"
                           value="{{ old('waktu_selesai', $agenda->waktu_selesai) }}" required>
                </div>

           <div class="mb-4">
    <label class="block font-semibold mb-1">Pimpinan (Kadis / Kepala Bagian)</label>
    <select name="id_pengguna" class="w-full border px-3 py-2 rounded" required>
        <option value="">-- Pilih Pengguna --</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}"
                {{ old('id_pengguna', $agenda->id_pengguna) == $user->id ? 'selected' : '' }}>
                {{ $user->name }} ({{ $user->role->nama_role }})
            </option>
        @endforeach
    </select>
</div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Perbarui</button>
                    <a href="{{ route('sekretaris.agenda.index') }}" class="ml-2 text-gray-700">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
