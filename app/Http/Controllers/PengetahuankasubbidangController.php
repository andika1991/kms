<?php

namespace App\Http\Controllers;

use App\Models\ArtikelPengetahuan;
use App\Models\ArticleView;
use App\Models\KategoriPengetahuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengetahuankasubbidangController extends Controller
{
    public function index(Request $request)
    {
        $query = ArtikelPengetahuan::query();
        $query->where('pengguna_id', auth()->id());

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $artikels = $query->latest()->get();

        // Ambil kategori pengetahuan berdasarkan subbidang_id user
        $user = auth()->user();
        $role = $user->role;
        $subbidangId = $role->subbidang_id ?? null;

        $kategoriPengetahuans = KategoriPengetahuan::query()
            ->when($subbidangId, function ($query) use ($subbidangId) {
                $query->where('subbidang_id', $subbidangId);
            })
            ->get();

        return view('kasubbidang.berbagipengetahuan.index', compact('artikels', 'kategoriPengetahuans'));
    }

    public function create()
    {
        $user = auth()->user();
        $role = $user->role;

        $bidangId = $role->bidang_id ?? null;
        $subbidangId = $role->subbidang_id ?? null;

        $query = KategoriPengetahuan::query();

        if ($bidangId) {
            $query->where('bidang_id', $bidangId);
        }

        if ($subbidangId) {
            $query->where('subbidang_id', $subbidangId);
        }

        $kategori = $query->get();

        return view('kasubbidang.berbagipengetahuan.create', compact('kategori'));
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

        $validated['pengguna_id'] = auth()->id();

        ArtikelPengetahuan::create($validated);

        return redirect()
            ->route('kasubbidang.berbagipengetahuan.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $userId = auth()->id();

        $artikelpengetahuan = ArtikelPengetahuan::where('id', $id)
            ->where('pengguna_id', $userId)
            ->firstOrFail();

        $user = auth()->user();
        $role = $user->role;

        $bidangId = $role->bidang_id ?? null;
        $subbidangId = $role->subbidang_id ?? null;

        $query = KategoriPengetahuan::query();

        if ($bidangId) {
            $query->where('bidang_id', $bidangId);
        }

        if ($subbidangId) {
            $query->where('subbidang_id', $subbidangId);
        }

        $kategori = $query->get();

        return view('kasubbidang.berbagipengetahuan.edit', compact('artikelpengetahuan', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $userId = auth()->id();

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
            ->route('kasubbidang.berbagipengetahuan.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $userId = auth()->id();

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
            ->route('kasubbidang.berbagipengetahuan.index')
            ->with('deleted', 'Artikel berhasil dihapus.');
    }

    public function show($id)
    {
        $userId = auth()->id();

        // Ambil artikel sesuai id dan user yang login (hanya artikel milik user)
        $artikel = ArtikelPengetahuan::where('id', $id)
            ->where('pengguna_id', $userId)
            ->firstOrFail();

        if (auth()->check()) {
            ArticleView::updateOrCreate(
                ['artikel_id' => $artikel->id, 'user_id' => auth()->id()],
                ['viewed_at' => now()]
            );
        }
        return view('kasubbidang.berbagipengetahuan.show', compact('artikel'));
    }

}
