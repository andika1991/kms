<?php

namespace App\Http\Controllers;

use App\Models\KategoriDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriDokumenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $role = $user->role;

        if (!$role || !$role->bidang_id) {
            return back()->withErrors(['error' => 'Bidang tidak ditemukan pada akun Anda.']);
        }

        $kategori = new KategoriDokumen();
        $kategori->nama_kategoridokumen = $request->nama_kategori;
        $kategori->bidang_id = $role->bidang_id;
        $kategori->subbidang_id = $role->subbidang_id ?? null;
        $kategori->save();

        return redirect()->back()->with('success', 'Kategori dokumen berhasil ditambahkan.');
    }

    // Form edit (ambil data kategori berdasarkan id)
    public function edit($id)
    {
        $kategori = KategoriDokumen::findOrFail($id);
        return response()->json($kategori); // nanti akan dipakai AJAX untuk isi form modal
    }

    // Update kategori
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $kategori = KategoriDokumen::findOrFail($id);
        $kategori->nama_kategoridokumen = $request->nama_kategori;
        $kategori->save();

        return redirect()->back()->with('success', 'Kategori dokumen berhasil diperbarui.');
    }

    
   public function destroy($id)
{
    $kategori = KategoriDokumen::findOrFail($id);
    $kategori->delete();

    return redirect()->route('kasubbidang.manajemendokumen.index')
                     ->with('success', 'Kategori dokumen berhasil dihapus.');
}

}
