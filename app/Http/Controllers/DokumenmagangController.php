<?php

namespace App\Http\Controllers;
use App\Models\Dokumen;
use Illuminate\Http\Request;

class DokumenmagangController extends Controller
{
    public function index(Request $request)
{
    $dokumenQuery = Dokumen::with(['kategoriDokumen', 'user']);
    $dokumen = $dokumenQuery->latest()->get();

    if ($request->filled('search')) {
        $search = strtolower($request->search);

        $dokumen = $dokumen->filter(function ($item) use ($search) {
            return str_contains(strtolower($item->nama_dokumen ?? ''), $search);
        });
    }

    return view('magang.dokumen.index', compact('dokumen'));
}
}
