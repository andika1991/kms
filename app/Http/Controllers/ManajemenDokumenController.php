<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManajemenDokumenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokumen::with(['kategoriDokumen', 'user']);

        if ($request->filled('search')) {
            $query->where('nama_dokumen', 'like', '%' . $request->search . '%');
        }

        $dokumen = $query->latest()->get();

        return view('kepalabagian.dokumen.index', compact('dokumen'));
    }

   public function create()
{
   
    $bidangId = auth()->user()->role->bidang_id;

     $kategori = \App\Models\KategoriDokumen::with('subbidang')
        ->where('bidang_id', $bidangId)
        ->get();
    return view('kepalabagian.dokumen.create', compact('kategori'));
}


    public function store(Request $request)
{
    // Validasi awal
    $validated = $request->validate([
        'nama_dokumen'         => ['required', 'string', 'max:255'],
        'deskripsi'            => ['required', 'string'],
        'kategori_dokumen_id'  => ['required', 'exists:kategori_dokumen,id'],
        'path_dokumen'         => ['required', 'file', 'max:10240'],
        'encrypted_key'        => ['nullable', 'string', 'max:255'],
    ]);

    // Ambil data kategori
    $kategori = KategoriDokumen::findOrFail($validated['kategori_dokumen_id']);

    // Cek apakah kategori adalah rahasia
    if (strtolower($kategori->nama_kategoridokumen) === 'rahasia') {
        // encrypted_key wajib diisi
        $request->validate([
            'encrypted_key' => ['required', 'string', 'max:255'],
        ]);

        $validated['encrypted_key'] = $request->encrypted_key;
    } else {
        // Pastikan encrypted_key NULL jika bukan rahasia
        $validated['encrypted_key'] = null;
    }

    if ($request->hasFile('path_dokumen')) {
        $validated['path_dokumen'] = $request->file('path_dokumen')->store('dokumen', 'public');
    }

    $validated['pengguna_id'] = auth()->id();

    Dokumen::create($validated);

    return redirect()->route('kepalabagian.manajemendokumen.index')
                     ->with('success', 'Dokumen berhasil ditambahkan.');
}


    public function show(Dokumen $dokumen)
    {
        return view('kepalabagian.dokumen.show', compact('dokumen'));
    }

    public function edit(Dokumen $dokumen)
    {
        $kategori = KategoriDokumen::all();

        return view('kepalabagian.dokumen.edit', compact('dokumen', 'kategori'));
    }

    public function update(Request $request, Dokumen $dokumen)
    {
        $validated = $request->validate([
            'nama_dokumen' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'kategori_dokumen_id' => ['required', 'exists:kategori_dokumen,id'],
            'path_dokumen' => ['nullable', 'file', 'max:10240'],
        ]);

        if ($request->hasFile('path_dokumen')) {
            if ($dokumen->path_dokumen && Storage::disk('public')->exists($dokumen->path_dokumen)) {
                Storage::disk('public')->delete($dokumen->path_dokumen);
            }
            $validated['path_dokumen'] = $request->file('path_dokumen')->store('dokumen', 'public');
        }

        $dokumen->update($validated);

        return redirect()->route('kepalabagian.manajemendokumen.index')
                         ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Dokumen $dokumen)
    {
        if ($dokumen->path_dokumen && Storage::disk('public')->exists($dokumen->path_dokumen)) {
            Storage::disk('public')->delete($dokumen->path_dokumen);
        }

        $dokumen->delete();

        return redirect()->route('kepalabagian.manajemendokumen.index')
                         ->with('success', 'Dokumen berhasil dihapus.');
    }
}

