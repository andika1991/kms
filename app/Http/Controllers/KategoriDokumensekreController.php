<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriDokumen;

class KategoriDokumensekreController extends Controller
{
    // Menampilkan data kategori untuk modal edit (format JSON)
    public function edit($id)
    {
        $kategori = KategoriDokumen::findOrFail($id);
        return response()->json($kategori);
    }

    // Mengupdate kategori
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ]);

        $kategori = KategoriDokumen::findOrFail($id);
        $kategori->nama_kategoridokumen = $request->nama_kategori;
        $kategori->save();

        return redirect()->route('sekretaris.manajemendokumen.index')->with('success', 'Kategori Dokumen berhasil diupdate!');
    }

    // Menghapus kategori
    public function destroy($id)
    {
        $kategori = KategoriDokumen::findOrFail($id);
        $kategori->delete();

        return redirect()->route('sekretaris.manajemendokumen.index')->with('deleted', 'Kategori Dokumen berhasil dihapus!');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ]);

        KategoriDokumen::create([
            'nama_kategoridokumen' => $request->nama_kategori
        ]);

        return redirect()->route('sekretaris.manajemendokumen.index')
            ->with('success', 'Kategori dokumen berhasil ditambahkan!');
    }
}
