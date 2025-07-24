@extends('home.app')
@section('title', 'Hasil Pencarian Dokumen')

@section('content')
<div class="max-w-[1100px] mx-auto px-6 py-8">
    <h2 class="text-xl font-bold mb-6">Hasil Pencarian: "{{ $keyword }}"</h2>

    @if($dokumens->isEmpty())
        <p class="text-gray-600">Tidak ada dokumen yang ditemukan.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($dokumens as $dok)
            <div class="bg-white rounded-lg shadow-md p-4">
                <h3 class="font-semibold text-blue-700 text-lg">{{ $dok->nama_dokumen }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ Str::limit(strip_tags($dok->deskripsi), 120) }}</p>
                <p class="text-sm text-gray-500 mt-2">Oleh: {{ $dok->user->name ?? '-' }}</p>
                <a href="{{ route('dokumen.show', $dok->id) }}" class="text-sm text-blue-600 mt-2 inline-block hover:underline">Lihat Detail</a>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
