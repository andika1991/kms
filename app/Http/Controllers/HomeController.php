<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bidang;
use App\Models\ArtikelPengetahuan;
use App\Models\Subbidang;

class HomeController extends Controller
{
    public function index()
    {
        $bidangs = Bidang::all(); // Untuk dropdown
        return view('home', compact('bidangs'));
    }

    // Ambil subbidang berdasarkan bidang_id
    public function getSubbidang($bidang_id)
    {
        $subbidangs = Subbidang::where('bidang_id', $bidang_id)->get();
        return response()->json($subbidangs);
    }

    // Ambil artikel berdasarkan subbidang
    public function getArtikelBySubbidang($subbidang_id)
    {
        $artikels = ArtikelPengetahuan::whereHas('kategoriPengetahuan', function ($query) use ($subbidang_id) {
            $query->where('subbidang_id', $subbidang_id);
        })->with(['kategoriPengetahuan.bidang', 'kategoriPengetahuan.subbidang', 'pengguna'])->get();

        return response()->json($artikels);
    }
}
