<?php

namespace App\Http\Controllers;

use App\Models\ArtikelPengetahuan;
use App\Models\ArticleView;
use App\Models\KategoriPengetahuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengetahuanpegawaiController extends Controller
{
public function index(Request $request)
{
    $user = Auth::user();
    $role = $user->role;
    $bidangId = $role->bidang_id ?? null;
    $subbidangId = $role->subbidang_id ?? null;

    // Ambil semua artikel milik user
    $artikelsQuery = ArtikelPengetahuan::with('kategoriPengetahuan')
        ->where('pengguna_id', $user->id)
        ->latest();

    // Filter search di database menggunakan slug (plain text)
    if ($request->filled('search')) {
        $search = strtolower($request->search);
        $artikelsQuery->whereRaw('LOWER(slug) LIKE ?', ["%{$search}%"]);
    }

    $artikels = $artikelsQuery->get();

    // Ambil kategori sesuai role (bidang/subbidang) atau kategori umum
    $kategori = KategoriPengetahuan::query()
        ->when($bidangId, fn($q) => $q->where(function($q2) use ($bidangId, $subbidangId) {
            $q2->where('bidang_id', $bidangId)
               ->when($subbidangId, fn($q3) => $q3->where('subbidang_id', $subbidangId));
        }))
        ->orWhere(fn($q) => $q->whereNull('bidang_id')->whereNull('subbidang_id'))
        ->orderBy('nama_kategoripengetahuan')
        ->get();

    return view('pegawai.berbagipengetahuan.index', compact('artikels', 'kategori'));
}


    public function create()
    {
        $user = Auth::user();
        $role = $user->role;
        $bidangId = $role->bidang_id ?? null;
        $subbidangId = $role->subbidang_id ?? null;

        $kategori = KategoriPengetahuan::query()
            ->orderByRaw('CASE WHEN bidang_id IS NULL AND subbidang_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('nama_kategoripengetahuan')
            ->get();

        return view('pegawai.berbagipengetahuan.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:artikelpengetahuan,slug'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'filedok' => ['nullable', 'file', 'max:5120'],
            'isi' => ['required', 'string'],
            'kategori_pengetahuan_id' => ['required', 'exists:kategori_pengetahuan,id'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($request->hasFile('filedok')) {
            $validated['filedok'] = $request->file('filedok')->store('filedok', 'public');
        }

        $validated['pengguna_id'] = Auth::id();

        ArtikelPengetahuan::create($validated);

        return redirect()
            ->route('pegawai.berbagipengetahuan.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $role = $user->role;
        $bidangId = $role->bidang_id ?? null;
        $subbidangId = $role->subbidang_id ?? null;

        $artikelpengetahuan = ArtikelPengetahuan::where('id', $id)
            ->where('pengguna_id', $user->id)
            ->firstOrFail();

        $kategori = KategoriPengetahuan::query()
            ->orderByRaw('CASE WHEN bidang_id IS NULL AND subbidang_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('nama_kategoripengetahuan')
            ->get();$kategori = KategoriPengetahuan::query()
            ->orderByRaw('CASE WHEN bidang_id IS NULL AND subbidang_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('nama_kategoripengetahuan')
            ->get();

        return view('pegawai.berbagipengetahuan.edit', compact('artikelpengetahuan', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();

        $artikelpengetahuan = ArtikelPengetahuan::where('id', $id)
            ->where('pengguna_id', $userId)
            ->firstOrFail();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:artikelpengetahuan,slug,' . $artikelpengetahuan->id,
            'kategori_pengetahuan_id' => 'required|exists:kategori_pengetahuan,id',
            'thumbnail' => 'nullable|image|max:2048',
            'filedok' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'isi' => 'required|string',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($artikelpengetahuan->thumbnail && Storage::disk('public')->exists($artikelpengetahuan->thumbnail)) {
                Storage::disk('public')->delete($artikelpengetahuan->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        } else {
            $validated['thumbnail'] = $artikelpengetahuan->thumbnail;
        }

        if ($request->hasFile('filedok')) {
            if ($artikelpengetahuan->filedok && Storage::disk('public')->exists($artikelpengetahuan->filedok)) {
                Storage::disk('public')->delete($artikelpengetahuan->filedok);
            }
            $validated['filedok'] = $request->file('filedok')->store('filedok', 'public');
        } else {
            $validated['filedok'] = $artikelpengetahuan->filedok;
        }

        $artikelpengetahuan->update($validated);

        return redirect()
            ->route('pegawai.berbagipengetahuan.index')
            ->with('success', 'Artikel pengetahuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $userId = Auth::id();

        $artikelpengetahuan = ArtikelPengetahuan::where('id', $id)
            ->where('pengguna_id', $userId)
            ->firstOrFail();

        if ($artikelpengetahuan->thumbnail && Storage::disk('public')->exists($artikelpengetahuan->thumbnail)) {
            Storage::disk('public')->delete($artikelpengetahuan->thumbnail);
        }

        if ($artikelpengetahuan->filedok && Storage::disk('public')->exists($artikelpengetahuan->filedok)) {
            Storage::disk('public')->delete($artikelpengetahuan->filedok);
        }

        $artikelpengetahuan->delete();

        return redirect()
            ->route('pegawai.berbagipengetahuan.index')
            ->with('deleted', 'Artikel pengetahuan berhasil dihapus.');
    }

    public function show($id)
    {
        $user = Auth::user();

        // Pastikan artikel milik user yang login
        $artikel = ArtikelPengetahuan::with('kategoriPengetahuan')
            ->where('id', $id)
            ->where('pengguna_id', $user->id)
            ->firstOrFail();

        if (auth()->check()) {
            ArticleView::updateOrCreate(
                ['artikel_id' => $artikel->id, 'user_id' => auth()->id()],
                ['viewed_at' => now()]
            );
        }
        return view('pegawai.berbagipengetahuan.show', compact('artikel'));
    }

}
