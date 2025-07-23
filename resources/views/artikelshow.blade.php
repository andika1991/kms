@extends('home.app')

@section('title', $artikel->judul)

@section('content')
<div class="max-w-[900px] mx-auto px-6 py-10 bg-white rounded-2xl shadow">

    {{-- Judul Artikel --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $artikel->judul }}</h1>

    {{-- Info Penulis --}}
    <p class="text-sm text-gray-500 mb-4">
        Oleh: {{ $artikel->pengguna->name ?? 'Anonim' }}
    </p>

    {{-- Thumbnail --}}
    @if ($artikel->thumbnail)
        <img src="{{ asset('storage/' . $artikel->thumbnail) }}" alt="{{ $artikel->judul }}"
            class="w-full h-auto rounded mb-6 shadow">
    @endif

    {{-- Isi Artikel --}}
    <div class="prose max-w-full text-gray-700">
        {!! $artikel->isi !!}
    </div>

    {{-- File Dokumen --}}
    @if ($artikel->filedok)
    <div class="mt-8">
        <h3 class="font-semibold text-lg mb-2 text-gray-800">Lampiran Dokumen:</h3>
        <a href="{{ asset('storage/' . $artikel->filedok) }}" target="_blank"
            class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm">
            <i class="fas fa-file-download"></i> Lihat Dokumen
        </a>
    </div>
    @endif

</div>
@endsection

@push('scripts')
@endpush
