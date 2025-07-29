<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;
use App\Models\Dokumen;
use App\Models\GrupChatUser;
use App\Models\ArtikelPengetahuan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

    // Total
    $jumlahKegiatan = Kegiatan::where('pengguna_id', $userId)->count();
    $jumlahDokumen = Dokumen::where('pengguna_id', $userId)->count();
    $jumlahForum = GrupChatUser::where('pengguna_id', $userId)->count();
    $jumlahArtikel = ArtikelPengetahuan::where('pengguna_id', $userId)->count();

    // Data bulanan (12 bulan terakhir)
    $bulan = collect(range(1, 12))->map(function ($m) {
        return Carbon::create()->month($m)->translatedFormat('F');
    });

    $dokumenPerBulan = Dokumen::select(
        DB::raw('MONTH(created_at) as bulan'),
        DB::raw('COUNT(*) as total')
    )->where('pengguna_id', $userId)
     ->whereYear('created_at', date('Y'))
     ->groupBy('bulan')
     ->pluck('total', 'bulan');

    $artikelPerBulan = ArtikelPengetahuan::select(
        DB::raw('MONTH(created_at) as bulan'),
        DB::raw('COUNT(*) as total')
    )->where('pengguna_id', $userId)
     ->whereYear('created_at', date('Y'))
     ->groupBy('bulan')
     ->pluck('total', 'bulan');

    // Buat array jumlah berdasarkan bulan (0 jika tidak ada)
    $dataDokumen = [];
    $dataArtikel = [];
    for ($i = 1; $i <= 12; $i++) {
        $dataDokumen[] = $dokumenPerBulan[$i] ?? 0;
        $dataArtikel[] = $artikelPerBulan[$i] ?? 0;
    }
    $dokumenTerbaru = Dokumen::where('pengguna_id', $userId)
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();


    return view('magang.dashboard', compact(
        'jumlahKegiatan', 'jumlahDokumen', 'jumlahForum', 'jumlahArtikel',
        'bulan', 'dataDokumen', 'dataArtikel','dokumenTerbaru'
    ));
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
