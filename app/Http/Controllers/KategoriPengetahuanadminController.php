<?php

namespace App\Http\Controllers;

use App\Models\KategoriPengetahuan;
use App\Models\Bidang;
use Illuminate\Http\Request;

class KategoriPengetahuanadminController extends Controller
{
    public function index()
    {
        $kategori = KategoriPengetahuan::with('bidang')->get(); // Jika ada relasi bidang
        return view('admin.kategoripengetahuan.index', compact('kategori'));
    }

    public function create()
    {
        $bidangs = Bidang::all();
        return view('admin.kategoripengetahuan.create', compact('bidangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategoripengetahuan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'bidang_id' => ['nullable', 'exists:bidang,id'], // admin harus pilih bidang
        ]);

        KategoriPengetahuan::create([
            'nama_kategoripengetahuan' => $request->nama_kategoripengetahuan,
            'deskripsi' => $request->deskripsi,
            'bidang_id' => $request->bidang_id,
        ]);

        return redirect()->route('admin.berbagipengetahuan.index')
                         ->with('success', 'Kategori Pengetahuan berhasil ditambahkan.');
    }

    public function edit(KategoriPengetahuan $kategoripengetahuan)
    {
        $bidangs = Bidang::all();
        return view('admin.kategoripengetahuan.edit', compact('kategoripengetahuan', 'bidangs'));
    }

    public function update(Request $request, KategoriPengetahuan $kategoripengetahuan)
    {
        $request->validate([
            'nama_kategoripengetahuan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'bidang_id' => ['required', 'exists:bidang,id'],
        ]);

        $kategoripengetahuan->update([
            'nama_kategoripengetahuan' => $request->nama_kategoripengetahuan,
            'deskripsi' => $request->deskripsi,
            'bidang_id' => $request->bidang_id,
        ]);

        return redirect()->route('admin.berbagipengetahuan.index')
                         ->with('success', 'Kategori Pengetahuan berhasil diupdate.');
    }

    public function destroy(KategoriPengetahuan $kategoripengetahuan)
    {
        $kategoripengetahuan->delete();

        return redirect()->route('admin.berbagipengetahuan.index')
                         ->with('success', 'Kategori Pengetahuan berhasil dihapus.');
    }
}
