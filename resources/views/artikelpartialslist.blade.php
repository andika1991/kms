@if ($artikels->isEmpty())
    <p class="text-gray-500 col-span-full">Tidak ada artikel ditemukan.</p>
@else
    @foreach ($artikels as $artikel)
        <div class="border rounded-xl overflow-hidden shadow hover:shadow-lg transition flex flex-col">
            <img src="/storage/{{ $artikel->thumbnail }}" class="w-full h-44 object-cover" alt="{{ $artikel->judul }}">
            <div class="p-4 flex flex-col flex-grow">
                <h4 class="font-semibold text-base text-gray-800 mb-1">{{ $artikel->judul }}</h4>
                <p class="text-sm text-gray-500 mb-2">Oleh: {{ $artikel->pengguna->name ?? '-' }}</p>
                <p class="text-gray-700 text-sm flex-grow">{{ \Illuminate\Support\Str::limit($artikel->isi, 100, '...') }}</p>
                <a href="/artikel/{{ $artikel->slug }}" class="mt-3 text-blue-600 text-sm hover:underline font-semibold">
                    Baca Selengkapnya
                </a>
            </div>
        </div>
    @endforeach
@endif
