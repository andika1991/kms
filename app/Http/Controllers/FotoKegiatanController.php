<?php

namespace App\Http\Controllers;

use App\Models\FotoKegiatan;
use Illuminate\Support\Facades\Storage;

class FotoKegiatanController extends Controller
{
    /**
     * Remove the specified photo from storage.
     */
    public function destroy(FotoKegiatan $foto)
    {
        // Hapus file fisik
        if (Storage::disk('public')->exists($foto->path_foto)) {
            Storage::disk('public')->delete($foto->path_foto);
        }

        // Hapus record database
        $foto->delete();

        return back()->with('success', 'Foto kegiatan berhasil dihapus.');
    }
}
