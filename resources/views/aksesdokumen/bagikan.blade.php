@section('title', 'Bagikan Dokumen')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📄 Bagikan Dokumen: <span class="text-blue-600">{{ $dokumen->nama_dokumen }}</span>
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 transition-all duration-300">

                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6">
                    👥 Pilih Pengguna yang Ingin Diberi Akses
                </h3>

                {{-- Form pencarian --}}
                <form method="GET" action="{{ route('aksesdokumen.bagikan', $dokumen->id) }}" class="mb-6">
                    <div class="flex gap-3 items-center">
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Cari nama atau email pengguna..."
                            class="w-full md:w-2/3 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" />
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
                            🔍 Cari
                        </button>
                    </div>
                </form>

                {{-- Form memilih pengguna --}}
                <form action="{{ route('aksesdokumen.bagikan.proses', $dokumen->id) }}" method="POST">
                    @csrf
<input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
                    <div class="grid md:grid-cols-2 gap-4 mb-6">
                       @foreach ($users as $user)
    <label class="flex items-center space-x-2">
        <input 
            type="checkbox" 
            name="user_ids[]" 
            value="{{ $user->id }}" 
            class="rounded border-gray-300 text-blue-600"
            {{ in_array($user->id, $aksesUserIds) ? 'checked' : '' }}
        >
        <span>{{ $user->name }} ({{ $user->email }})</span>
    </label>
@endforeach

                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition duration-200 shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12H3m0 0l3.293-3.293a1 1 0 011.414 0L12 12l-4.293 4.293a1 1 0 01-1.414 0L3 12z" />
                            </svg>
                            Bagikan Dokumen
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
