<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;
use App\Models\Dokumen;
use App\Models\Bidang; 
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

        // ===== Agregat per Bidang untuk grafik (khusus data milik user aktif) =====
        $bidangIds   = \App\Models\Bidang::orderBy('nama')->pluck('id')->all();
        $bidangNames = \App\Models\Bidang::orderBy('nama')->pluck('nama')->all();

        // Bar chart: jumlah artikel pengetahuan user per Bidang (via relasi kategoriPengetahuan)
        $dataPengetahuanBidang = [];
        foreach ($bidangIds as $bidangId) {
            $dataPengetahuanBidang[] = \App\Models\ArtikelPengetahuan::where('pengguna_id', $userId)
                ->whereHas('kategoriPengetahuan', function ($q) use ($bidangId) {
                    $q->where('bidang_id', $bidangId);
                })
                ->count();
        }

        // Line chart: jumlah kegiatan user per Bidang (kolom bidang_id di tabel kegiatan)
        $dataKegiatanBidang = [];
        foreach ($bidangIds as $bidangId) {
            $dataKegiatanBidang[] = \App\Models\Kegiatan::where('pengguna_id', $userId)
                ->where('bidang_id', $bidangId)
                ->count();
        }

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
                'bulan', 'dataDokumen', 'dataArtikel','dokumenTerbaru', 'bidangNames',
                'dataPengetahuanBidang', 'dataKegiatanBidang'
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

        // ===== Bar charts per Bidang (sesuai struktur bidang/subbidang) =====
        // Ambil semua Bidang untuk label
        $bidangIds   = Bidang::orderBy('nama')->pluck('id')->all();
        $bidangNames = Bidang::orderBy('nama')->pluck('nama')->all();

        // Artikel Pengetahuan per Bidang (kategori_pengetahuan -> bidang_id)
        $dataArtikelBidang = [];
        foreach ($bidangIds as $bidangId) {
            $dataArtikelBidang[] = ArtikelPengetahuan::where('pengguna_id', $userId)
                ->whereHas('kategoriPengetahuan', function ($q) use ($bidangId) {
                    $q->where('bidang_id', $bidangId);
                    // Jika ingin spesifik subbidang: ->whereNotNull('subbidang_id') / ->where('subbidang_id', X);
                })
                ->count();
        }

        // Dokumen per Bidang (kategori_dokumen -> bidang_id)
        $dataDokumenBidang = [];
        foreach ($bidangIds as $bidangId) {
            $dataDokumenBidang[] = Dokumen::where('pengguna_id', $userId)
                ->whereHas('kategoriDokumen', function ($q) use ($bidangId) {
                    $q->where('bidang_id', $bidangId);
                })
                ->count();
        }

        return view('magang.dashboard', compact(
            'jumlahKegiatan', 'jumlahDokumen', 'jumlahForum', 'jumlahArtikel',
            'bulan', 'dataDokumen', 'dataArtikel','dokumenTerbaru', 'bidangNames',
            'dataDokumenBidang', 'dataArtikelBidang'
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

        // ===== agregat per Bidang (untuk 2 bar chart) =====
        $bidangIds   = Bidang::orderBy('nama')->pluck('id')->all();
        $bidangNames = Bidang::orderBy('nama')->pluck('nama')->all();

        $dataDokumenBidang = [];
        foreach ($bidangIds as $bidangId) {
            $dataDokumenBidang[] = Dokumen::whereHas('kategoriDokumen', function ($q) use ($bidangId) {
                $q->where('bidang_id', $bidangId);
            })->count();
        }

            $dataArtikelBidang = [];
        foreach ($bidangIds as $bidangId) {
            $dataArtikelBidang[] = ArtikelPengetahuan::whereHas('kategoriPengetahuan', function ($q) use ($bidangId) {
                $q->where('bidang_id', $bidangId);
            })->count();
        }

        return view('sekretaris.dashboard', compact(
            'jumlahKegiatan', 'jumlahDokumen', 'jumlahForum', 'jumlahArtikel',
            'bulan', 'dataDokumen', 'dataArtikel','dokumenTerbaru',
            'bidangNames', 'dataDokumenBidang', 'dataArtikelBidang'
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

        // ======== Tambahan: Dokumen Teratas by views (semua bidang/subbidang) ========
        // Aggregrasi view per dokumen
        $viewsAgg = DB::table('document_views')
            ->select('dokumen_id', DB::raw('COUNT(*) as views'))
            ->groupBy('dokumen_id');

        // Join ke dokumen supaya nama_dokumen lewat model (akses/decrypt) dan tetap Eloquent
        $topDokumen = Dokumen::query()
            ->joinSub($viewsAgg, 'dv', function ($join) {
                $join->on('dv.dokumen_id', '=', 'dokumen.id');
            })
            // ->whereHas('kategoriDokumen', fn($q) => $q->where('nama_kategoridokumen', '!=', 'rahasia')) // opsional
            ->orderByDesc('dv.views')
            ->limit(5)
            ->get([
                'dokumen.id',
                'dokumen.nama_dokumen',
                DB::raw('dv.views as total_views'),
            ]);
        
        // ===== Data untuk Bar Chart (Perkembangan Pengetahuan & Artikel) =====
        // Dikelompokkan per Bidang; seluruh Subbidang ikut terakumulasi.
        $bidangIds   = Bidang::orderBy('nama')->pluck('id')->all();
        $bidangNames = Bidang::orderBy('nama')->pluck('nama')->all();

        // Total dokumen per Bidang (relasi lewat kategoriDokumen)
        $dataDokumenBidang = [];
        foreach ($bidangIds as $bidangId) {
            $dataDokumenBidang[] = Dokumen::whereHas('kategoriDokumen', function ($q) use ($bidangId) {
                $q->where('bidang_id', $bidangId);
            })->count();
        }

        // Total artikel pengetahuan per Bidang (relasi lewat kategoriPengetahuan)
        $dataArtikelBidang = [];
        foreach ($bidangIds as $bidangId) {
            $dataArtikelBidang[] = ArtikelPengetahuan::whereHas('kategoriPengetahuan', function ($q) use ($bidangId) {
                $q->where('bidang_id', $bidangId);
            })->count();
        }

        return view('kadis.dashboard', compact(
            'penggunaTeraktifArtikel',
            'penggunaTeraktifDokumen',
            'totalArtikel',
            'totalDokumen',
            'totalPegawai',
            'totalMagang',
            'topDokumen',
            'bidangNames',
            'dataDokumenBidang',
            'dataArtikelBidang',
        ));
    }


    public function index()
    {
        return view('dashboard.index');
    }
}
