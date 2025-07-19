<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Exception;
class DokumenmagangController extends Controller
{
    public function index(Request $request)
    {
        $dokumenQuery = Dokumen::with(['kategoriDokumen', 'user'])
            ->where('pengguna_id', auth()->id());

        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $dokumenQuery->whereRaw('LOWER(nama_dokumen) LIKE ?', ["%{$search}%"]);
        }

        $dokumen = $dokumenQuery->latest()->get();

        $kategori = KategoriDokumen::all();

        return view('magang.dokumen.index', compact('dokumen', 'kategori'));
    }

    public function create()
    {
        $bidangId = auth()->user()->role->bidang_id ?? null;

        $kategori = KategoriDokumen::with('subbidang')
            ->when($bidangId, function ($query) use ($bidangId) {
                $query->where('bidang_id', $bidangId);
            })
            ->get();

        return view('magang.dokumen.create', compact('kategori'));
    }

 public function store(Request $request)
{
    $validated = $request->validate([
        'nama_dokumen'         => ['required', 'string', 'max:255'],
        'deskripsi'            => ['required', 'string'],
        'kategori_dokumen_id'  => ['required', 'exists:kategori_dokumen,id'],
        'path_dokumen'         => ['required', 'file', 'max:10240'],
        'encrypted_key'        => ['nullable', 'string', 'max:255'],
    ]);

    $kategori = KategoriDokumen::findOrFail($validated['kategori_dokumen_id']);

    if (strtolower($kategori->nama_kategoridokumen) === 'rahasia') {
        $request->validate([
            'encrypted_key' => ['required', 'string', 'max:255'],
        ]);
        $validated['encrypted_key'] = $request->encrypted_key;
    } else {
        $validated['encrypted_key'] = null;
    }

    if ($request->hasFile('path_dokumen')) {
        $validated['path_dokumen'] = $request->file('path_dokumen')->store('dokumen', 'public');
    } else {
        return back()->withErrors(['path_dokumen' => 'File dokumen wajib diunggah.']);
    }

    $validated['pengguna_id'] = auth()->id();

    Dokumen::create($validated);

    return redirect()->route('magang.manajemendokumen.index')
                     ->with('success', 'Dokumen berhasil ditambahkan.');
}

public function show($id)
{
    $dokumen = Dokumen::with(['kategoriDokumen', 'user'])->findOrFail($id);

    return view('magang.dokumen.show', compact('dokumen'));
}

    public function edit(Dokumen $manajemendokuman)
    {


        $kategori = KategoriDokumen::all();

        return view('magang.dokumen.edit', compact('manajemendokuman', 'kategori'));
    }

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'nama_dokumen'         => ['required', 'string', 'max:255'],
        'deskripsi'            => ['nullable', 'string'],
        'kategori_dokumen_id'  => ['required', 'exists:kategori_dokumen,id'],
        'path_dokumen'         => ['nullable', 'file', 'max:10240'],
    ]);

    $dokumen = Dokumen::findOrFail($id);

    if ($request->hasFile('path_dokumen')) {
        if (
            $dokumen->path_dokumen &&
            Storage::disk('public')->exists($dokumen->path_dokumen)
        ) {
            Storage::disk('public')->delete($dokumen->path_dokumen);
        }

        $validated['path_dokumen'] = $request->file('path_dokumen')
            ->store('dokumen', 'public');
    } else {
        unset($validated['path_dokumen']);
    }

    $dokumen->update($validated);

    return redirect()->route('magang.manajemendokumen.index')
        ->with('success', 'Dokumen berhasil diperbarui.');
}



public function destroy($id)
{
    try {
        // Ambil dokumen berdasarkan ID
        $dokumen = Dokumen::findOrFail($id);

        // Hapus file fisik jika ada
        if ($dokumen->path_dokumen && Storage::disk('public')->exists($dokumen->path_dokumen)) {
            Storage::disk('public')->delete($dokumen->path_dokumen);
        }

        // Soft delete dokumen
        $dokumen->delete();

        return redirect()->route('magang.manajemendokumen.index')
            ->with('success', 'Dokumen berhasil dihapus (soft delete).');
    } catch (\Exception $e) {
        return redirect()->route('magang.manajemendokumen.index')
            ->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
    }
}





  
}
