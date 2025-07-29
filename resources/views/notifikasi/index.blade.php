<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Notifikasi
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-xl p-6">
                @if($notifikasi->isEmpty())
                    <div class="text-center text-gray-500 py-8">
                        <svg class="mx-auto w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                        </svg>
                        <p class="text-lg font-medium">Tidak ada notifikasi untuk saat ini.</p>
                    </div>
                @else
                    <ul class="space-y-5">
                        @foreach($notifikasi as $item)
                            <li class="flex justify-between items-start p-5 rounded-xl border 
                                       {{ !$item->sudahdibaca ? 'bg-blue-50 border-blue-300' : 'bg-gray-100 border-gray-300' }}">
                                <div class="w-full pr-4">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                        <i class="fas fa-bell text-blue-500 mr-2"></i>{{ $item->judul }}
                                    </h3>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        {{ $item->isi }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        {{ $item->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                @if(!$item->sudahdibaca)
                                    <form action="{{ route('notifikasi.dibaca', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                                            Tandai Dibaca
                                        </button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
