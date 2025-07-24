@extends('home.app')
@section('title', 'Detail Dokumen')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10 bg-white shadow-xl rounded-xl">

    <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $dokumen->nama_dokumen }}</h2>

    <p class="text-sm text-gray-600 mb-2">
        Kategori: {{ $dokumen->kategoriDokumen->nama_kategoridokumen }}<br>
        Bidang: {{ $dokumen->kategoriDokumen->bidang->nama ?? '-' }}<br>
        Subbidang: {{ $dokumen->kategoriDokumen->subbidang->nama ?? '-' }}
    </p>

    <div class="my-4 border rounded overflow-hidden" style="height: 600px;">
        <embed src="{{ asset('storage/' . $dokumen->path_dokumen) }}" 
               type="application/pdf" 
               width="100%" 
               height="100%" />
    </div>

    <a href="{{ asset('storage/' . $dokumen->path_dokumen) }}"
       class="inline-block bg-blue-600 text-white px-4 py-2 rounded mt-4 hover:bg-blue-700"
       target="_blank" download>
        Download Dokumen
    </a>

</div>
@endsection
