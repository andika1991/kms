<?php

namespace App\Http\Controllers;

use App\Models\ArtikelPengetahuan; 
use App\Models\KategoriPengetahuan;
use Illuminate\Http\Request;

class KategoriPengetahuankadisController extends Controller
{

    /**
     * Tampilkan seluruh kategori pengetahuan tanpa filter bidang.
     */
    public function index()
    {
        $kategoriPengetahuans = KategoriPengetahuan::all();
        $kategori = KategoriPengetahuan::with(['bidang', 'subbidang'])
            ->whereNull('bidang_id')
            ->whereNull('subbidang_id')
            ->latest()
            ->get();

        // Kirim data kategori ke view index
        return view('kadis.berbagipengetahuan.index', compact('kategoriPengetahuans'));
    }

    /**
     * Tampilkan form tambah kategori pengetahuan.
     */
    public function create()
    {
        return view('kadis.kategoripengetahuan.create');
    }

    /**
     * Simpan kategori pengetahuan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategoripengetahuan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ]);
        KategoriPengetahuan::create([
            'nama_kategoripengetahuan' => $request->nama_kategoripengetahuan,
            'deskripsi' => $request->deskripsi,
        ]);
        return redirect()->route('kadis.berbagipengetahuan.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

   
    public function edit(KategoriPengetahuan $kategoripengetahuan)
    {
        return view('kadis.kategoripengetahuan.edit', compact('kategoripengetahuan'));
    }

  
    public function update(Request $request, KategoriPengetahuan $kategoripengetahuan)
    {
        $request->validate([
            'nama_kategoripengetahuan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'bidang_id' => ['required', 'exists:bidang,id'],
            'subbidang_id' => ['nullable', 'exists:subbidang,id'],
        ]);

        $kategoripengetahuan->update([
            'nama_kategoripengetahuan' => $request->nama_kategoripengetahuan,
            'deskripsi' => $request->deskripsi,
            'bidang_id' => $request->bidang_id,
            'subbidang_id' => $request->subbidang_id,
        ]);

        return redirect()->route('kadis.kategoripengetahuan.index')->with('success', 'Kategori berhasil diperbarui.');
    }


    public function destroy(KategoriPengetahuan $kategoripengetahuan)
    {
        $kategoripengetahuan->delete();

        return redirect()->route('kadis.berbagipengetahuan.index')->with('deleted', 'Kategori berhasil dihapus.');
    }
}
