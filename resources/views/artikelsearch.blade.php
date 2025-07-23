@extends('home.app')

@section('title', 'Hasil Pencarian Artikel')

@section('content')
<div class="container mx-auto px-6 py-6">
    <h2 class="text-2xl font-semibold mb-4">Hasil Pencarian Artikel</h2>

    {{-- Form Pencarian --}}
    <form action="{{ route('artikel.search') }}" method="GET" class="mb-6 flex gap-2 max-w-md">
        <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Cari artikel..."
            class="flex-grow border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            autofocus
        />
        <button
            type="submit"
            class="bg-blue-600 text-white px-4 rounded hover:bg-blue-700 transition"
        >Cari</button>
    </form>

    @if(!empty($keyword))
        <p class="mb-4">Menampilkan hasil untuk: <strong>{{ $keyword }}</strong></p>
    @endif

    @if($artikels->isEmpty())
        <div class="alert alert-warning text-yellow-700 bg-yellow-100 p-4 rounded mb-4">
            Tidak ada artikel yang ditemukan.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            @foreach($artikels as $artikel)
                <div class="border rounded-xl overflow-hidden shadow hover:shadow-lg transition flex flex-col">
                    <img src="{{ asset('storage/' . $artikel->thumbnail) }}" alt="{{ $artikel->judul }}" class="w-full h-44 object-cover">
                    <div class="p-4 flex flex-col flex-grow">
                        <h4 class="font-semibold text-base text-gray-800 mb-1">{{ $artikel->judul }}</h4>
                        <p class="text-sm text-gray-500 mb-2">Oleh: {{ $artikel->pengguna->name ?? '-' }}</p>
                        <p class="text-gray-700 text-sm flex-grow">{{ \Illuminate\Support\Str::limit(strip_tags($artikel->isi), 100) }}</p>
                        <a href="{{ url('/artikel/' . $artikel->slug) }}" class="mt-3 text-blue-600 text-sm hover:underline font-semibold">Baca Selengkapnya</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Tombol Kembali --}}
    <a href="{{ url()->previous() }}" class="inline-block bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition">
        &larr; Kembali
    </a>
</div>
@endsection
