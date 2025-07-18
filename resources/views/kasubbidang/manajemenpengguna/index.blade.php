<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manajemen Pengguna Subbidang
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Flash Message --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tabel Pengguna --}}
        @if($penggunas->count())
            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded shadow p-4">
                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700 text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">No</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">Nama</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">Email</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">Verifikasi</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penggunas as $index => $user)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700' }}">
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-200">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-200">{{ $user->name }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-200">{{ $user->email }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-200">
                                @if($user->verified)
                                    <span class="text-green-600 font-semibold">Terverifikasi</span>
                                @else
                                    <form action="{{ route('kasubbidang.manajemenpengguna.verifikasi', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memverifikasi akun ini?')">
    @csrf
    @method('PATCH')
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs" type="submit">
        Verifikasi
    </button>
</form>

                                @endif
                            </td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-200 flex gap-2">
                                <a href="{{ route('kasubbidang.manajemenpengguna.edit', $user->id) }}"
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                                    Edit
                                </a>

                                <form action="{{ route('kasubbidang.manajemenpengguna.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-700 dark:text-gray-300">
                Belum ada pengguna yang terdaftar pada subbidang ini.
            </p>
        @endif
    </div>
</x-app-layout>
