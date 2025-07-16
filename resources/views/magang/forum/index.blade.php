<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Forum Diskusi
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <div class="mb-4">
                <a href="{{ route('magang.forum.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Tambah Forum
                </a>
            </div>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Grup</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
    @forelse($grupchats as $grupchat)
        <tr>
            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $loop->iteration }}</td>
            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $grupchat->nama_grup }}</td>
            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $grupchat->deskripsi }}</td>
            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $grupchat->grup_role }}</td>
            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">
                <a href="{{ route('magang.forum.show', $grupchat->id) }}"
                   class="text-blue-600 hover:underline">Lihat</a>

                @if($grupchat->created_by == auth()->id())
                    |
                    <a href="{{ route('magang.forum.edit', $grupchat->id) }}"
                       class="text-yellow-600 hover:underline">Edit</a>
                    |
                    <form action="{{ route('magang.forum.destroy', $grupchat->id) }}"
                          method="POST" style="display:inline;"
                          onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                    </form>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="px-4 py-4 text-center text-gray-500 dark:text-gray-300">
                Tidak ada forum tersedia.
            </td>
        </tr>
    @endforelse
</tbody>

            </table>
        </div>
    </div>
</x-app-layout>
