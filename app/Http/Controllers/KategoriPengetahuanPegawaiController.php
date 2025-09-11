<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ArtikelPengetahuan;
use App\Models\KategoriPengetahuan;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriPengetahuanPegawaiController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriPengetahuan $kategoripengetahuan)
    {
        $user = Auth::user();
        $role = $user->role;

        // Pastikan kategori memang milik bidang/subbidang user (atau umum= null)
        if (
            ($role?->bidang_id && $kategoripengetahuan->bidang_id && $kategoripengetahuan->bidang_id != $role->bidang_id) ||
            ($role?->subbidang_id && $kategoripengetahuan->subbidang_id && $kategoripengetahuan->subbidang_id != $role->subbidang_id)
        ) {
            abort(403, 'Anda tidak berhak mengubah kategori ini.');
        }

        $data = $request->validate([
            'nama_kategoripengetahuan' => ['required', 'string', 'max:255'],
        ]);

        $kategoripengetahuan->update($data);

        return back()->with('success', 'Kategori Pengetahuan berhasil diperbarui.');
    }

    public function destroy(KategoriPengetahuan $kategoripengetahuan)
    {
        $user = Auth::user();
        $role = $user->role;

        if (
            ($role?->bidang_id && $kategoripengetahuan->bidang_id && $kategoripengetahuan->bidang_id != $role->bidang_id) ||
            ($role?->subbidang_id && $kategoripengetahuan->subbidang_id && $kategoripengetahuan->subbidang_id != $role->subbidang_id)
        ) {
            abort(403, 'Anda tidak berhak menghapus kategori ini.');
        }

        // Cegah hapus jika sedang dipakai oleh artikel
        $dipakai = ArtikelPengetahuan::where('kategori_pengetahuan_id', $kategoripengetahuan->id)->exists();
        if ($dipakai) {
            return back()->with('deleted', 'Kategori tidak bisa dihapus karena sedang dipakai oleh artikel.');
        }

        $kategoripengetahuan->delete();

        return back()->with('deleted', 'Kategori Pengetahuan berhasil dihapus.');
    }
        
}
