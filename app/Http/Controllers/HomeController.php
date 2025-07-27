<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bidang;
use App\Models\ArtikelPengetahuan;
use App\Models\Subbidang;
use App\Models\Kegiatan;
use App\Models\Dokumen;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    public function index()
    {
        $bidangs = Bidang::all(); // Untuk dropdown
        return view('home', compact('bidangs'));
    }

    public function pengetahuan()
{
     $bidangs = Bidang::all(); // Untuk dropdown
    $artikels = ArtikelPengetahuan::with(['kategoriPengetahuan', 'pengguna'])->latest()->get();
    return view('pengetahuan', compact('artikels', 'bidangs'));
}



public function search(Request $request)
{
    $keyword = strtolower($request->query('q', ''));

    if (strlen($keyword) < 2) {
        return view('artikelsearch', [
            'artikels' => collect(),
            'keyword' => $keyword,
        ]);
    }

    // Ambil sejumlah artikel (misal batasi 200)
    $artikels = ArtikelPengetahuan::limit(200)->get();

    // Filter data yang sudah otomatis didekripsi oleh Laravel
    $filtered = $artikels->filter(function ($artikel) use ($keyword) {
        $judul = strtolower($artikel->judul);  // sudah plaintext
        $isi = strtolower($artikel->isi);      // sudah plaintext

        return Str::contains($judul, $keyword) || Str::contains($isi, $keyword);
    });

    return view('artikelsearch', [
        'artikels' => $filtered,
        'keyword' => $keyword,
    ]);
}




public function searchPartial(Request $request)
{
    $keyword = $request->query('q', '');

    if (strlen($keyword) < 2) {
        return view('artikel.partials.list', ['artikels' => []]);
    }

    $artikels = ArtikelPengetahuan::where('judul', 'like', "%{$keyword}%")
        ->orWhere('isi', 'like', "%{$keyword}%")
        ->limit(20)
        ->get();

    return view('artikelpartialslist', compact('artikels'));
}





    // Ambil subbidang berdasarkan bidang_id
    public function getSubbidang($bidang_id)
    {
        $subbidangs = Subbidang::where('bidang_id', $bidang_id)->get();
        return response()->json($subbidangs);
    }
public function getArtikelByBidang($bidang_id)
{
    $artikels = ArtikelPengetahuan::whereHas('kategoriPengetahuan', function ($query) use ($bidang_id) {
        $query->where('bidang_id', $bidang_id);
    })->with(['kategoriPengetahuan.bidang', 'kategoriPengetahuan.subbidang', 'pengguna'])->get();

    return response()->json($artikels);
}

    // Ambil artikel berdasarkan subbidang
    public function getArtikelBySubbidang($subbidang_id)
    {
        $artikels = ArtikelPengetahuan::whereHas('kategoriPengetahuan', function ($query) use ($subbidang_id) {
            $query->where('subbidang_id', $subbidang_id);
        })->with(['kategoriPengetahuan.bidang', 'kategoriPengetahuan.subbidang', 'pengguna'])->get();

        return response()->json($artikels);
    }

    public function showArtikelBySlug($slug)
    {
        $artikel = ArtikelPengetahuan::with(['kategoriPengetahuan.bidang', 'kategoriPengetahuan.subbidang', 'pengguna'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('artikelshow', compact('artikel'));
    }



public function dokumen()
{   $bidangs = Bidang::all(); 
    // Ambil semua dokumen yang *bukan* dari kategori 'rahasia'
    $dokumens = Dokumen::with(['kategoriDokumen', 'user'])
        ->whereHas('kategoriDokumen', function ($query) {
            $query->where('nama_kategoridokumen', '!=', 'rahasia');
        })
        ->latest()
        ->get();

    return view('dokumen', compact('dokumens','bidangs'));
}

public function getDokumenByBidang($bidang_id)
{
    $dokumens = Dokumen::whereHas('kategoriDokumen', function ($query) use ($bidang_id) {
        $query->where('bidang_id', $bidang_id);
    })->with('user')->get();

    return response()->json($dokumens);
}

public function getDokumenBySubbidang($subbidang_id)
{
    $dokumens = Dokumen::whereHas('kategoriDokumen', function ($query) use ($subbidang_id) {
        $query->where('subbidang_id', $subbidang_id);
    })->with('user')->get();

    return response()->json($dokumens);
}

public function showDokumenById($id)
{
    $dokumen = Dokumen::with(['kategoriDokumen.bidang', 'kategoriDokumen.subbidang', 'user'])
        ->whereHas('kategoriDokumen', function ($query) {
            $query->where('nama_kategoridokumen', '!=', 'rahasia');
        })
        ->findOrFail($id);

    return view('dokumenshow', compact('dokumen'));
}


public function searchDokumen(Request $request)
{
    $keyword = $request->query('q', '');

    if (strlen($keyword) < 2) {
        return view('dokumensearch', [
            'dokumens' => collect(),
            'keyword' => $keyword,
        ]);
    }

    $dokumens = Dokumen::with(['kategoriDokumen', 'user'])
        ->whereHas('kategoriDokumen', function ($query) {
            $query->where('nama_kategoridokumen', '!=', 'Rahasia');
        })
        ->where(function ($query) use ($keyword) {
            $query->where('nama_dokumen', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
        })
        ->get();

    return view('dokumensearch', [
        'dokumens' => $dokumens,
        'keyword' => $keyword,
    ]);
}

 public function kegiatan()
    {
        $bidangs = Bidang::all(); // Untuk sidebar filter bidang
        // Bisa load semua kegiatan atau kosongkan dulu, nanti di load ajax saat klik bidang/subbidang
        return view('kegiatan', compact('bidangs'));
    }

public function getByBidang($bidang_id)
{
    $kegiatans = Kegiatan::where('bidang_id', $bidang_id)
        ->with([
            'bidang', 
            'subbidang',
            'fotokegiatan' => function ($q) {
                $q->limit(1); // Ambil hanya 1 foto
            }
        ])
        ->get();

    return response()->json($kegiatans);
}

public function getBySubbidang($subbidang_id)
{
    $kegiatans = Kegiatan::where('subbidang_id', $subbidang_id)
        ->with([
            'bidang',
            'subbidang',
            'fotokegiatan' => function ($q) {
                $q->limit(1);
            }
        ])
        ->get();

    return response()->json($kegiatans);
}

}
