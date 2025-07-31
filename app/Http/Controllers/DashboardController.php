<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;
use App\Models\Dokumen;
use App\Models\User;
use App\Models\GrupChatUser;
use App\Models\ArtikelPengetahuan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
  public function admin()
{
    // Hitung total seluruh data di sistem (tidak terbatas pengguna tertentu)
    $jumlahKegiatan = Kegiatan::count();
    $jumlahDokumen  = Dokumen::count();
    $jumlahForum    = GrupChatUser::count();
    $jumlahArtikel  = ArtikelPengetahuan::count();

    // Nama bulan (Januari - Desember)
    $bulan = collect(range(1, 12))->map(fn($m) => Carbon::create()->month($m)->translatedFormat('F'));

    // Data dokumen per bulan
    $dokumenPerBulan = Dokumen::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
        ->whereYear('created_at', date('Y'))
        ->groupBy('bulan')
        ->pluck('total', 'bulan');

    // Data artikel per bulan
    $artikelPerBulan = ArtikelPengetahuan::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
        ->whereYear('created_at', date('Y'))
        ->groupBy('bulan')
        ->pluck('total', 'bulan');

    // Data kegiatan per bulan
    $kegiatanPerBulan = Kegiatan::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
        ->whereYear('created_at', date('Y'))
        ->groupBy('bulan')
        ->pluck('total', 'bulan');

    // Data forum per bulan
    $forumPerBulan = GrupChatUser::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
        ->whereYear('created_at', date('Y'))
        ->groupBy('bulan')
        ->pluck('total', 'bulan');

    // Inisialisasi array agar semua bulan terisi (meskipun 0)
    $dataDokumen = [];
    $dataArtikel = [];
    $dataKegiatan = [];
    $dataForum = [];

    for ($i = 1; $i <= 12; $i++) {
        $dataDokumen[] = $dokumenPerBulan[$i] ?? 0;
        $dataArtikel[] = $artikelPerBulan[$i] ?? 0;
        $dataKegiatan[] = $kegiatanPerBulan[$i] ?? 0;
        $dataForum[] = $forumPerBulan[$i] ?? 0;
    }

    // Dokumen terbaru secara keseluruhan
    $dokumenTerbaru = Dokumen::latest()->take(5)->get();

    return view('admin.dashboard', compact(
        'jumlahKegiatan',
        'jumlahDokumen',
        'jumlahForum',
        'jumlahArtikel',
        'bulan',
        'dataDokumen',
        'dataArtikel',
        'dataKegiatan',
        'dataForum',
        'dokumenTerbaru'
    ));
}



  public function kepalabagian()
{
    $user = Auth::user();
    $bidangId = $user->role->bidang_id; // Ambil bidang_id dari role

    // Ambil semua ID pengguna dalam bidang yang sama
    $penggunaIds = \App\Models\User::whereHas('role', function ($query) use ($bidangId) {
        $query->where('bidang_id', $bidangId);
    })->pluck('id');

    // Total ringkasan
    $jumlahKegiatan = Kegiatan::whereIn('pengguna_id', $penggunaIds)->count();
    $jumlahDokumen  = Dokumen::whereIn('pengguna_id', $penggunaIds)->count();
    $jumlahForum    = GrupChatUser::whereIn('pengguna_id', $penggunaIds)->count();
    $jumlahArtikel  = ArtikelPengetahuan::whereIn('pengguna_id', $penggunaIds)->count();

    // Bulan
    $bulan = collect(range(1, 12))->map(function ($m) {
        return Carbon::create()->month($m)->translatedFormat('F');
    });

    // Data dokumen per bulan
    $dokumenPerBulan = Dokumen::select(
        DB::raw('MONTH(created_at) as bulan'),
        DB::raw('COUNT(*) as total')
    )->whereIn('pengguna_id', $penggunaIds)
     ->whereYear('created_at', date('Y'))
     ->groupBy('bulan')
     ->pluck('total', 'bulan');

    // Data artikel per bulan
    $artikelPerBulan = ArtikelPengetahuan::select(
        DB::raw('MONTH(created_at) as bulan'),
        DB::raw('COUNT(*) as total')
    )->whereIn('pengguna_id', $penggunaIds)
     ->whereYear('created_at', date('Y'))
     ->groupBy('bulan')
     ->pluck('total', 'bulan');

    // Format data untuk grafik (lengkap 12 bulan)
    $dataDokumen = [];
    $dataArtikel = [];
    for ($i = 1; $i <= 12; $i++) {
        $dataDokumen[] = $dokumenPerBulan[$i] ?? 0;
        $dataArtikel[] = $artikelPerBulan[$i] ?? 0;
    }

    // Dokumen terbaru
    $dokumenTerbaru = Dokumen::whereIn('pengguna_id', $penggunaIds)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    // Dokumen teratas (optional)


    return view('kepalabagian.dashboard', compact(
        'jumlahKegiatan',
        'jumlahDokumen',
        'jumlahForum',
        'jumlahArtikel',
        'bulan',
        'dataDokumen',
        'dataArtikel',
        'dokumenTerbaru',
      
    ));
}


public function kasubbidang()
{
    $user = Auth::user();
    $subbidangId = $user->role->subbidang_id;

  
$penggunaIds = \App\Models\User::whereHas('role', function ($query) use ($subbidangId) {
    $query->where('subbidang_id', $subbidangId);
})->pluck('id');
    // Hitungan total entitas
    $jumlahKegiatan = Kegiatan::whereIn('pengguna_id', $penggunaIds)->count();
    $jumlahDokumen  = Dokumen::whereIn('pengguna_id', $penggunaIds)->count();
    $jumlahForum    = GrupChatUser::whereIn('pengguna_id', $penggunaIds)->count();
    $jumlahArtikel  = ArtikelPengetahuan::whereIn('pengguna_id', $penggunaIds)->count();

    // Label bulan (Januari - Desember)
    $bulan = collect(range(1, 12))->map(fn($m) => Carbon::create()->month($m)->translatedFormat('F'));

    // Jumlah dokumen per bulan (tahun berjalan)
    $dokumenPerBulan = Dokumen::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
        ->whereIn('pengguna_id', $penggunaIds)
        ->whereYear('created_at', date('Y'))
        ->groupBy('bulan')
        ->pluck('total', 'bulan');

    // Jumlah artikel per bulan (tahun berjalan)
    $artikelPerBulan = ArtikelPengetahuan::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
        ->whereIn('pengguna_id', $penggunaIds)
        ->whereYear('created_at', date('Y'))
        ->groupBy('bulan')
        ->pluck('total', 'bulan');

    // Inisialisasi array data bulanan (isi 0 jika kosong)
    $dataDokumen = [];
    $dataArtikel = [];
    for ($i = 1; $i <= 12; $i++) {
        $dataDokumen[] = $dokumenPerBulan[$i] ?? 0;
        $dataArtikel[] = $artikelPerBulan[$i] ?? 0;
    }

    // Ambil 5 dokumen terbaru
    $dokumenTerbaru = Dokumen::whereIn('pengguna_id', $penggunaIds)
        ->latest()
        ->take(5)
        ->get();

    return view('kasubbidang.dashboard', compact(
        'jumlahKegiatan',
        'jumlahDokumen',
        'jumlahForum',
        'jumlahArtikel',
        'bulan',
        'dataDokumen',
        'dataArtikel',
        'dokumenTerbaru'
    ));
}

    public function pegawai()
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
        return view('pegawai.dashboard', compact(
            'jumlahKegiatan', 'jumlahDokumen', 'jumlahForum', 'jumlahArtikel',
            'bulan', 'dataDokumen', 'dataArtikel','dokumenTerbaru'
        ));
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
        return view('sekretaris.dashboard', compact(
            'jumlahKegiatan', 'jumlahDokumen', 'jumlahForum', 'jumlahArtikel',
            'bulan', 'dataDokumen', 'dataArtikel','dokumenTerbaru'
        ));
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

$totalPegawai = User::whereHas('role', function ($query) {
    $query->where('role_group', 'pegawai');
})->count();

$totalMagang = User::whereHas('role', function ($query) {
    $query->where('role_group', 'magang');
})->count();


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
