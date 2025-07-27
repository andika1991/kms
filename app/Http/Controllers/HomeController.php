<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bidang;
use App\Models\ArtikelPengetahuan;
use App\Models\Subbidang;
use App\Models\Dokumen;
use Illuminate\Support\Str;
class HomeController extends Controller
{
    public function index()
    {
        $bidangs = Bidang::all(); // Untuk dropdown
        $totalDokumen = Dokumen::count();
        $totalArtikel = ArtikelPengetahuan::count();

        $dokumens = Dokumen::with(['kategoriDokumen', 'user'])
                    ->latest()
                    ->take(4)
                    ->get();

        $artikels = ArtikelPengetahuan::with(['kategoriPengetahuan', 'pengguna'])
                    ->latest()
                    ->take(4)
                    ->get();
        return view('home', compact('bidangs', 'totalDokumen', 'totalArtikel', 'dokumens', 'artikels'));
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

}
