<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function index()
    {
        $penggunaId = Auth::user()->id;

        $notifikasi = Notifikasi::where('pengguna_id', $penggunaId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function tandaiSudahDibaca($id)
    {
        $notifikasi = Notifikasi::findOrFail($id);

        if ($notifikasi->pengguna_id === Auth::id()) {
            $notifikasi->update(['sudahdibaca' => true]);
        }

        return redirect()->back()->with('success', 'Notifikasi ditandai sebagai sudah dibaca.');
    }
}
