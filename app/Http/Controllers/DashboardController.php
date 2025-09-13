<?php

namespace App\Http\Controllers;
use App\Models\GrupChat;
use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;
use App\Models\Dokumen;
use App\Models\Bidang; 
use App\Models\Subbidang;
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
        $userId = Auth::id(); 
        // Hitung total seluruh data di sistem (tidak terbatas pengguna tertentu)
        $jumlahKegiatan = Kegiatan::count();
        $jumlahDokumen  = Dokumen::count();
        $jumlahForum    = GrupChatUser::count();
        $jumlahArtikel  = ArtikelPengetahuan::count();

        // Data bulanan (12 bulan terakhir)
        $bulan = collect(range(1, 12))->map(fn ($m) => Carbon::create()->month($m)->translatedFormat('F'));

        $dokumenPerBulan = DB::table('dokumen')
            ->where('pengguna_id', $userId)
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) AS bulan, COUNT(*) AS total')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->pluck('total', 'bulan');

        // PERBAIKAN: Menggunakan Query Builder (DB::table) untuk menghindari error ONLY_FULL_GROUP_BY
        $artikelPerBulan = DB::table('artikelpengetahuan')
            ->where('pengguna_id', $userId)
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) AS bulan, COUNT(*) AS total')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
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
        $user = auth()->user();
        $bidangId = $user->role->bidang_id ?? null;

        if (!$bidangId) {
            return view('kepalabagian.dashboard', [
                'jumlahDokumen'   => 0,
                'jumlahArtikel'   => 0,
                'jumlahKegiatan'  => 0,
                'jumlahForum'     => 0,
                'dokumenTeratas'  => collect(),
                'bulan'           => [],
                'dataDokumen'     => [],
                'dataArtikel'     => [],
                'subbidangNames'  => collect(),
                'barArtikel'      => collect(),
                'barDokumen'      => collect(),
                'rankArtikel'     => collect(),
                'rankDokumen'     => collect(),
            ]);
        }

        $subbidangIds = Subbidang::where('bidang_id', $bidangId)->pluck('id');

        if ($subbidangIds->isEmpty()) {
            return view('kepalabagian.dashboard', [
                'jumlahDokumen'   => 0,
                'jumlahArtikel'   => 0,
                'jumlahKegiatan'  => 0,
                'jumlahForum'     => 0,
                'dokumenTeratas'  => collect(),
                'bulan'           => [],
                'dataDokumen'     => [],
                'dataArtikel'     => [],
                'subbidangNames'  => collect(),
                'barArtikel'      => collect(),
                'barDokumen'      => collect(),
                'rankArtikel'     => collect(),
                'rankDokumen'     => collect(),
            ]);
        }

        // ======== JUMLAH DATA ======== //
        $jumlahDokumen = Dokumen::whereHas('kategoriDokumen', fn ($q) => $q->whereIn('subbidang_id', $subbidangIds))->count();
        $jumlahArtikel = ArtikelPengetahuan::whereHas('kategoriPengetahuan', fn ($q) => $q->whereIn('subbidang_id', $subbidangIds))->count();
        $jumlahKegiatan = Kegiatan::whereIn('subbidang_id', $subbidangIds)->count();
        $jumlahForum = GrupChat::where('bidang_id', $bidangId)->count();

        // ======== DOKUMEN TERATAS ======== //
        $dokumenTeratas = Dokumen::with('kategoriDokumen')
            ->whereHas('kategoriDokumen', fn ($q) => $q->whereIn('subbidang_id', $subbidangIds))
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // ======== DATA PER BULAN ======== //
        $tahunSekarang = now()->year;
        $dataDokumenMentah = Dokumen::withoutGlobalScopes()
            ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', $tahunSekarang)
            ->whereHas('kategoriDokumen', fn ($q) => $q->whereIn('subbidang_id', $subbidangIds))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'bulan');

        $dataArtikelMentah = ArtikelPengetahuan::withoutGlobalScopes()
            ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', $tahunSekarang)
            ->whereHas('kategoriPengetahuan', fn ($q) => $q->whereIn('subbidang_id', $subbidangIds))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'bulan');

        $bulan = range(1, 12);
        $dataDokumen = array_values(array_map(fn ($b) => $dataDokumenMentah->get($b) ?? 0, $bulan));
        $dataArtikel = array_values(array_map(fn ($b) => $dataArtikelMentah->get($b) ?? 0, $bulan));

        // ======== DATA BAR PER SUBBIDANG ======== //
        $subbidangNames = Subbidang::whereIn('id', $subbidangIds)->pluck('nama', 'id');
        $barArtikel = ArtikelPengetahuan::withoutGlobalScopes()
            ->select('kategori_pengetahuan.subbidang_id', DB::raw('COUNT(*) as total'))
            ->join('kategori_pengetahuan', 'artikelpengetahuan.kategori_pengetahuan_id', '=', 'kategori_pengetahuan.id')
            ->whereIn('kategori_pengetahuan.subbidang_id', $subbidangIds)
            ->groupBy('kategori_pengetahuan.subbidang_id')
            ->pluck('total', 'kategori_pengetahuan.subbidang_id');

        $barDokumen = Dokumen::withoutGlobalScopes()
            ->select('kategori_dokumen.subbidang_id', DB::raw('COUNT(*) as total'))
            ->join('kategori_dokumen', 'dokumen.kategori_dokumen_id', '=', 'kategori_dokumen.id')
            ->whereIn('kategori_dokumen.subbidang_id', $subbidangIds)
            ->groupBy('kategori_dokumen.subbidang_id')
            ->pluck('total', 'kategori_dokumen.subbidang_id');

        // ======== PENGGUNA TERATIF (RANKING) ======== //
        // Peringkat 5 teratas pengunggah dokumen per subbidang
        $rankDokumen = Dokumen::withoutGlobalScopes()
            ->select('pengguna_id', DB::raw('COUNT(*) as total_dokumen'))
            ->whereHas('kategoriDokumen', fn ($q) => $q->whereIn('subbidang_id', $subbidangIds))
            ->groupBy('pengguna_id')
            ->orderByDesc('total_dokumen')
            ->limit(5)
            ->with('user')
            ->get();

        // Peringkat 5 teratas penulis artikel pengetahuan per subbidang
        $rankArtikel = ArtikelPengetahuan::withoutGlobalScopes()
            ->select('pengguna_id', DB::raw('COUNT(*) as total_artikel'))
            ->whereHas('kategoriPengetahuan', fn ($q) => $q->whereIn('subbidang_id', $subbidangIds))
            ->groupBy('pengguna_id')
            ->orderByDesc('total_artikel')
            ->limit(5)
            ->with('pengguna')
            ->get();


        return view('kepalabagian.dashboard', compact(
            'jumlahDokumen',
            'jumlahArtikel',
            'jumlahKegiatan',
            'jumlahForum',
            'dokumenTeratas',
            'bulan',
            'dataDokumen',
            'dataArtikel',
            'subbidangNames',
            'barArtikel',
            'barDokumen',
            'rankArtikel',
            'rankDokumen'
        ));
    }
    public function kasubbidang()
    {
        $user = Auth::user();
        $userId = $user->id; 
        $subbidangId = $user->role->subbidang_id;
        $penggunaIds = \App\Models\User::whereHas('role', function ($query) use ($subbidangId) {
        $query->where('subbidang_id', $subbidangId);
        })->pluck('id');
        // Hitungan total entitas
        $jumlahKegiatan = Kegiatan::whereIn('pengguna_id', $penggunaIds)->count();
        $jumlahDokumen  = Dokumen::whereIn('pengguna_id', $penggunaIds)->count();
        $jumlahForum    = GrupChatUser::whereIn('pengguna_id', $penggunaIds)->count();
        $jumlahArtikel  = ArtikelPengetahuan::whereIn('pengguna_id', $penggunaIds)->count();

        // Data bulanan (12 bulan terakhir)
        $bulan = collect(range(1, 12))->map(fn ($m) => Carbon::create()->month($m)->translatedFormat('F'));

        $dokumenPerBulan = DB::table('dokumen')
            ->where('pengguna_id', $userId)
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) AS bulan, COUNT(*) AS total')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->pluck('total', 'bulan');

        // PERBAIKAN: Menggunakan Query Builder (DB::table) untuk menghindari error ONLY_FULL_GROUP_BY
        $artikelPerBulan = DB::table('artikelpengetahuan')
            ->where('pengguna_id', $userId)
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) AS bulan, COUNT(*) AS total')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
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

        // === Dokumen Teratas berdasar views (hanya dokumen tim subbidang + non-rahasia) ===
        $viewsAgg = DB::table('document_views')
            ->select('dokumen_id', DB::raw('COUNT(*) as views'))
            ->groupBy('dokumen_id');

        $dokumenTeratas = Dokumen::query()
            ->whereIn('pengguna_id', $penggunaIds)
            ->whereHas('kategoriDokumen', function ($q) {
                $q->whereRaw('LOWER(nama_kategoridokumen) <> ?', ['rahasia']);
            })
            ->leftJoinSub($viewsAgg, 'dv', function ($join) {
                $join->on('dokumen.id', '=', 'dv.dokumen_id');
            })
            ->orderByDesc(DB::raw('COALESCE(dv.views,0)'))
            ->limit(5)
            ->get([
                'dokumen.id',
                'dokumen.nama_dokumen',
                DB::raw('COALESCE(dv.views,0) as total_views'),
            ]);

        // fallback jika belum ada view sama sekali
        if ($dokumenTeratas->isEmpty()) {
            $dokumenTeratas = Dokumen::whereIn('pengguna_id', $penggunaIds)
                ->whereHas('kategoriDokumen', function ($q) {
                    $q->whereRaw('LOWER(nama_kategoridokumen) <> ?', ['rahasia']);
                })
                ->latest()
                ->limit(5)
                ->get(['id','nama_dokumen'])
                ->map(function ($d) { $d->total_views = 0; return $d; });
        }

        return view('kasubbidang.dashboard', compact(
            'jumlahKegiatan',
            'jumlahDokumen',
            'jumlahForum',
            'jumlahArtikel',
            'bulan',
            'dataDokumen',
            'dataArtikel',
            'dokumenTerbaru',
            'dokumenTeratas'
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
        $bulan = collect(range(1, 12))->map(fn ($m) => Carbon::create()->month($m)->translatedFormat('F'));

        $dokumenPerBulan = DB::table('dokumen')
            ->where('pengguna_id', $userId)
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) AS bulan, COUNT(*) AS total')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->pluck('total', 'bulan');

        // PERBAIKAN: Menggunakan Query Builder (DB::table) untuk menghindari error ONLY_FULL_GROUP_BY
        $artikelPerBulan = DB::table('artikelpengetahuan')
            ->where('pengguna_id', $userId)
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) AS bulan, COUNT(*) AS total')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
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

        $dokumenPerBulan = DB::table('dokumen')
            ->where('pengguna_id', $userId)
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) AS bulan, COUNT(*) AS total')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->pluck('total', 'bulan');

        // PERBAIKAN: Menggunakan Query Builder (DB::table) untuk menghindari error ONLY_FULL_GROUP_BY
        $artikelPerBulan = DB::table('artikelpengetahuan')
            ->where('pengguna_id', $userId)
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) AS bulan, COUNT(*) AS total')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
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

        $dokumenPerBulan = DB::table('dokumen')
            ->where('pengguna_id', $userId)
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) AS bulan, COUNT(*) AS total')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->pluck('total', 'bulan');

        // PERBAIKAN: Menggunakan Query Builder (DB::table) untuk menghindari error ONLY_FULL_GROUP_BY
        $artikelPerBulan = DB::table('artikelpengetahuan')
            ->where('pengguna_id', $userId)
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) AS bulan, COUNT(*) AS total')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
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
        // Langkah 1: Ambil data agregat
        $topArtikelData = DB::table('artikelpengetahuan')
            ->select('pengguna_id', DB::raw('COUNT(*) as total_artikel'))
            ->groupBy('pengguna_id')
            ->orderByDesc('total_artikel')
            ->take(5)
            ->get();

        // Langkah 2: Ambil model User berdasarkan ID yang didapat
        $penggunaIdsArtikel = $topArtikelData->pluck('pengguna_id');
        $penggunaModelsArtikel = User::whereIn('id', $penggunaIdsArtikel)->get()->keyBy('id');

        // Langkah 3: Gabungkan data
        $penggunaTeraktifArtikel = $topArtikelData->map(function ($item) use ($penggunaModelsArtikel) {
            $item->pengguna = $penggunaModelsArtikel->get($item->pengguna_id);
            return $item;
        });

        // PERBAIKAN: Melakukan hal yang sama untuk Dokumen
        $topDokumenData = DB::table('dokumen')
            ->select('pengguna_id', DB::raw('COUNT(*) as total_dokumen'))
            ->groupBy('pengguna_id')
            ->orderByDesc('total_dokumen')
            ->take(5)
            ->get();
        
        $penggunaIdsDokumen = $topDokumenData->pluck('pengguna_id');
        $penggunaModelsDokumen = User::whereIn('id', $penggunaIdsDokumen)->get()->keyBy('id');

        $penggunaTeraktifDokumen = $topDokumenData->map(function ($item) use ($penggunaModelsDokumen) {
            $item->pengguna = $penggunaModelsDokumen->get($item->pengguna_id);
            return $item;
        });

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
