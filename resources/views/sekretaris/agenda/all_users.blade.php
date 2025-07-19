<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Jadwal Pimpinan Hari Ini
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 bg-teal-400 min-h-screen">
        <div class="text-center text-white mb-6">
            <h1 class="text-2xl font-bold">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</h1>
            <p class="text-lg font-mono">{{ \Carbon\Carbon::now()->format('H:i:s') }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($users as $user)
                <div class="bg-white rounded shadow p-4 flex flex-col items-center">
                    {{-- Foto --}}
                    @if(!empty($user->photo_url))
                        <img src="{{ asset('storage/' . $user->photo_url) }}" alt="Foto {{ $user->decrypted_name }}" class="w-24 h-24 object-cover rounded mb-3">
                    @else
                        <div class="w-24 h-24 bg-gray-300 rounded mb-3 flex items-center justify-center text-gray-500">
                            No Image
                        </div>
                    @endif

                    {{-- Nama dan Jabatan --}}
                    <h3 class="font-semibold text-center mb-1 text-sm">{{ $user->decrypted_name }}</h3>
                    <p class="text-xs text-center mb-3 text-gray-600">{{ $user->role->nama_role ?? '-' }}</p>

                    {{-- Jadwal Agenda --}}
                    <div class="text-xs w-full">
                        @forelse($user->agenda as $agenda)
                            <div class="border-t pt-2">
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($agenda->date_agenda)->format('H:i') }} s.d {{ $agenda->waktu_selesai ?? '-' }}</p>
                                <p>{{ $agenda->nama_agenda }}</p>
                            </div>
                        @empty
                            <p class="text-gray-400 italic text-center">Tidak ada agenda hari ini</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
