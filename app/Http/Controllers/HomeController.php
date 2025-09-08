<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bidang;
use App\Models\ArtikelPengetahuan;
use App\Models\Subbidang;
use App\Models\Kegiatan;
use App\Models\KegiatanView;
use App\Models\Dokumen;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    public function index()
    {
        $bidangs = Bidang::all();
        $totalDokumen = Dokumen::count();
        $totalArtikel = ArtikelPengetahuan::count();

        // ⬇ Hanya tampilkan dokumen non-rahasia di beranda
        $dokumens = Dokumen::with(['kategoriDokumen', 'user'])
            ->whereHas('kategoriDokumen', function ($q) {
                $q->whereRaw('LOWER(nama_kategoridokumen) <> ?', ['rahasia']);
            })
            ->latest()
            ->take(4)
            ->get();

        $artikels = ArtikelPengetahuan::with(['kategoriPengetahuan', 'pengguna'])
                    ->latest()
                    ->take(4)
                    ->get();

        $kegiatans = Kegiatan::with('fotokegiatan')->latest()->take(6)->get();
        return view('home', compact('bidangs', 'totalDokumen', 'totalArtikel', 'dokumens', 'artikels', 'kegiatans'));
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
        
        // Ambil 5 artikel pengetahuan lainnya (selain yang sedang dibuka)
        $pengetahuan_lainnya = ArtikelPengetahuan::where('id', '!=', $artikel->id)
            ->latest()
            ->take(5)
            ->get();

        return view('artikelshow', compact('artikel', 'pengetahuan_lainnya'));
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
        $dokumens = Dokumen::whereHas('kategoriDokumen', function ($q) use ($bidang_id) {
            $q->where('bidang_id', $bidang_id)
              ->whereRaw('LOWER(nama_kategoridokumen) <> ?', ['rahasia']); // ⬅ filter rahasia
        })
        ->with(['user:id,name', 'kategoriDokumen:id,nama_kategoridokumen'])
        ->latest()
        ->get()
        ->map(function ($d) {
            return [
                'id'            => $d->id,
                'nama_dokumen'  => $d->nama_dokumen,
                'deskripsi'     => (string) $d->deskripsi,
                'created_at'    => optional($d->created_at)->toISOString(),
                'user'          => ['name' => optional($d->user)->name],
                // ⬇️ inilah yang dibaca oleh JS-mu
                'kategori_nama' => optional($d->kategoriDokumen)->nama_kategoridokumen,
                // opsional jika punya relasi views()
                'views_count'   => method_exists($d, 'views') ? $d->views()->count() : 0,
            ];
        });

        return response()->json($dokumens);
    }

    public function getDokumenBySubbidang($subbidang_id)
    {
         $dokumens = Dokumen::whereHas('kategoriDokumen', function ($q) use ($subbidang_id) {
            $q->where('subbidang_id', $subbidang_id)
              ->whereRaw('LOWER(nama_kategoridokumen) <> ?', ['rahasia']); // ⬅ filter rahasia
        })
        ->with(['user:id,name', 'kategoriDokumen:id,nama_kategoridokumen'])
        ->latest()
        ->get()
        ->map(function ($d) {
            return [
                'id'            => $d->id,
                'nama_dokumen'  => $d->nama_dokumen,
                'deskripsi'     => (string) $d->deskripsi,
                'created_at'    => optional($d->created_at)->toISOString(),
                'user'          => ['name' => optional($d->user)->name],
                'kategori_nama' => optional($d->kategoriDokumen)->nama_kategoridokumen,
                'views_count'   => method_exists($d, 'views') ? $d->views()->count() : 0,
            ];
        });

        return response()->json($dokumens);
    }

    public function showDokumenById($id)
    {
        $dokumen = Dokumen::with(['kategoriDokumen.bidang', 'kategoriDokumen.subbidang', 'user'])
            ->whereHas('kategoriDokumen', function ($query) {
                $query->where('nama_kategoridokumen', '!=', 'rahasia');
            })
            ->findOrFail($id);

        // Ambil dokumen lainnya, exclude dokumen yang sedang dibuka
        $dokumen_lainnya = Dokumen::where('id', '!=', $dokumen->id)
            ->whereHas('kategoriDokumen', function ($query) {
                $query->where('nama_kategoridokumen', '!=', 'rahasia');
            })
            ->latest()
            ->take(5)
            ->get();

        return view('dokumenshow', compact('dokumen', 'dokumen_lainnya'));
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
            ->whereHas('kategoriDokumen', function ($q) {
                $q->whereRaw('LOWER(nama_kategoridokumen) <> ?', ['rahasia']);
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

    public function kegiatan(Request $request)
    {
        $bidangs = Bidang::all();
        $query = $request->input('q'); 

        // Ambil kegiatan yang hanya kategori publik dan sesuai pencarian jika ada
        $kegiatan = Kegiatan::with('fotokegiatan')
            ->withCount('views')
            ->where('kategori_kegiatan', 'publik') 
            ->when($query, function ($qBuilder) use ($query) {
                $qBuilder->where(function ($subQuery) use ($query) {
                    $subQuery->where('nama_kegiatan', 'like', '%' . $query . '%')
                            ->orWhere('deskripsi_kegiatan', 'like', '%' . $query . '%');
                });
            })
            ->latest()
            ->get();

        return view('kegiatan', compact('bidangs', 'kegiatan', 'query'));
    }

    public function showKegiatanById($id, Request $request)
    {
        $kegiatan = Kegiatan::with(['bidang', 'subbidang', 'fotokegiatan'])->findOrFail($id);

        // Catat 1x per sesi browser agar tidak membengkak
        $sessionKey = 'viewed_kegiatan_'.$kegiatan->id;
        if (!$request->session()->has($sessionKey)) {
            KegiatanView::create([
                'kegiatan_id' => $kegiatan->id,
                'user_id'     => auth()->id(),                                    
                'ip'          => $request->ip(),
                'user_agent'  => substr((string) $request->userAgent(), 0, 512),  
            ]);
            $request->session()->put($sessionKey, now());
        }

        // Hitung total views
        $viewsCount = $kegiatan->views()->count();

        // Ambil 5 kegiatan lain (selain yang sedang dibuka)
        $kegiatan_lainnya = Kegiatan::where('id', '!=', $kegiatan->id)
            ->where('kategori_kegiatan', $kegiatan->kategori_kegiatan)
            ->latest()
            ->take(5)
            ->get();

        return view('kegiatanshow', [
            'kegiatan'         => $kegiatan,
            'kegiatan_lainnya' => $kegiatan_lainnya,
            'viewsCount'       => $kegiatan->views_count, 
        ]);
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
            ->withCount('views')
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
            ->withCount('views')
            ->get();

        return response()->json($kegiatans);
    }
}
