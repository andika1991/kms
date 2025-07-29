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
    // Top 5 pengguna teraktif menulis artikel
    $penggunaTeraktifArtikel = \App\Models\ArtikelPengetahuan::select('pengguna_id')
        ->with('pengguna')
        ->groupBy('pengguna_id')
        ->selectRaw('pengguna_id, COUNT(*) as total_artikel')
        ->orderByDesc('total_artikel')
        ->take(5)
        ->get();

    // Top 5 pengguna teraktif berbagi dokumen
    $penggunaTeraktifDokumen = \App\Models\AksesDokumenPengguna::select('pengguna_id')
        ->with('pengguna')
        ->groupBy('pengguna_id')
        ->selectRaw('pengguna_id, COUNT(*) as total_dokumen')
        ->orderByDesc('total_dokumen')
        ->take(5)
        ->get();

    // Total artikel pengetahuan keseluruhan
    $totalArtikel = \App\Models\ArtikelPengetahuan::count();

    // Total dokumen keseluruhan (gunakan tabel dokumen langsung, bukan akses)
    $totalDokumen = \App\Models\Dokumen::count();

    // Total pengguna dengan role pegawai (asumsikan role_id = 2 untuk pegawai)
    $totalPegawai = \App\Models\User::where('role_id', 2)->count();

    // Total pengguna dengan role magang (asumsikan role_id = 4 untuk magang)
    $totalMagang = \App\Models\User::where('role_id', 4)->count();

    return view('kadis.dashboard', compact(
        'penggunaTeraktifArtikel',
        'penggunaTeraktifDokumen',
        'totalArtikel',
        'totalDokumen',
        'totalPegawai',
        'totalMagang'
    ));
}


    public function index()
    {
        return view('dashboard.index');
    }
}
