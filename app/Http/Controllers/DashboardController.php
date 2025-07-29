<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;
use App\Models\Dokumen;
use App\Models\GrupChatUser;
use App\Models\ArtikelPengetahuan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('admin.dashboard');
    }

    public function kepalabagian()
    {
        return view('kepalabagian.dashboard');
    }

    public function kasubbidang()
    {
        return view('kasubbidang.dashboard');
    }

    public function pegawai()
    {
        return view('pegawai.dashboard');
    }

    public function magang()
    {
    $userId = Auth::id();

        // Hitung data yang terkait user yang login
        $jumlahKegiatan = Kegiatan::where('pengguna_id', $userId)->count();
        $jumlahDokumen = Dokumen::where('pengguna_id', $userId)->count();
        $jumlahForum = GrupChatUser::where('pengguna_id', $userId)->count();
        $jumlahArtikel = ArtikelPengetahuan::where('pengguna_id', $userId)->count();

        // Kirim ke view
        return view('magang.dashboard', compact('jumlahKegiatan', 'jumlahDokumen', 'jumlahForum', 'jumlahArtikel'));
    }

    public function sekretaris()
    {
        return view('sekretaris.dashboard');
    }

    public function kadis()
    {
        return view('kadis.dashboard');
    }

    public function index()
    {
        return view('dashboard.index');
    }
}
