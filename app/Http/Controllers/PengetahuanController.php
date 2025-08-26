<?php

namespace App\Http\Controllers;

use App\Models\ArtikelPengetahuan;
use App\Models\ArticleView;
use App\Models\KategoriPengetahuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // [MAGANG] untuk Rule::in

class PengetahuanController extends Controller
{
    public function index(Request $request)
    {
        // [MAGANG] daftar ID kategori khusus magang (ganti sesuai DB)
        $magangIds = [21, 22, 23, 24, 26, 27]; // <-- semula [26]

        $query = ArtikelPengetahuan::query();
        $query->where('pengguna_id', auth()->id());

        // [MAGANG] tampilkan artikel milik user yang hanya berada pada kategori magang
        $query->whereIn('kategori_pengetahuan_id', $magangIds);

        // Filter pencarian jika ada input "search"
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $artikels = $query->latest()->get();

        // Ambil kategori sesuai bidang/subbidang user
        $user = auth()->user();
        $role = $user->role;
        $bidangId = $role->bidang_id ?? null;
        $subbidangId = $role->subbidang_id ?? null;

        $kategoriQuery = KategoriPengetahuan::query();

        if ($bidangId) {
            $kategoriQuery->where('bidang_id', $bidangId);
        }
        if ($subbidangId) {
            $kategoriQuery->where('subbidang_id', $subbidangId);
        }

        // [MAGANG] batasi list kategori yang tampil di sidebar hanya kategori magang
        $kategoriQuery->whereIn('id', $magangIds);

        // [MAGANG] override agar TIDAK terfilter bidang/subbidang (biar tidak kosong),
        // tetap hanya ambil ID yang diperbolehkan.
        $kategoriQuery = KategoriPengetahuan::query()
            ->whereIn('id', $magangIds)
            ->orderBy('id');

        $kategori = $kategoriQuery->get();

        return view('magang.berbagipengetahuan', compact('artikels', 'kategori'));
    }

    public function create()
    {
        // [MAGANG] daftar ID kategori khusus magang
        $magangIds = [21, 22, 23, 24, 26, 27]; // <-- semula [26]

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

        // [MAGANG] batasi pilihan kategori pada form create
        $query->whereIn('id', $magangIds);

        // [MAGANG] override agar tanpa filter bidang/subbidang (biar tidak kosong),
        // tetap hanya ID yang diperbolehkan.
        $query = KategoriPengetahuan::query()
            ->whereIn('id', $magangIds)
            ->orderBy('id');

        $kategori = $query->get();

        return view('magang.artikelpengetahuan-create', compact('kategori'));
    }

    public function store(Request $request)
    {
        // [MAGANG] daftar ID kategori khusus magang
        $magangIds = [21, 22, 23, 24, 26, 27]; // <-- semula [26]

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:artikelpengetahuan,slug'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'filedok' => ['nullable', 'file', 'max:5120'],
            'isi' => ['required', 'string'],
            'kategori_pengetahuan_id' => [
                'required',
                'exists:kategori_pengetahuan,id',
                Rule::in($magangIds), // [MAGANG] wajib kategori khusus magang
            ],
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
            ->route('magang.berbagipengetahuan.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail artikel (hanya jika milik user login).
     */
    public function show(ArtikelPengetahuan $berbagipengetahuan)
    {
        // [MAGANG] daftar ID kategori khusus magang
        $magangIds = [21, 22, 23, 24, 26, 27];

        // Validasi untuk memastikan user hanya bisa melihat artikel miliknya
        if (!in_array($berbagipengetahuan->kategori_pengetahuan_id, $magangIds)) {
            abort(403, 'Akses Ditolak: Kategori tidak sesuai.');
        }

        $berbagipengetahuan->load(['kategoriPengetahuan', 'pengguna']);

        if (auth()->check()) {
            ArticleView::updateOrCreate(
                ['artikel_id' => $artikel->id, 'user_id' => auth()->id()],
                ['viewed_at' => now()]
            );
        }

        // Kirim ke view dengan nama variabel yang sama seperti sebelumnya agar tidak merusak view
        return view('magang.berbagipengetahuan-show', [
            'artikelpengetahuan' => $berbagipengetahuan
        ]);
    }

    /**
     * Tampilkan form edit artikel.
     */
    public function edit($id)
    {
        // [MAGANG] daftar ID kategori khusus magang
        $magangIds = [21, 22, 23, 24, 26, 27]; // <-- semula [26]

        $userId = auth()->id();

        $artikelpengetahuan = ArtikelPengetahuan::where('id', $id)
            ->where('pengguna_id', $userId)
            ->whereIn('kategori_pengetahuan_id', $magangIds) // [MAGANG] batasi
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

        // [MAGANG] batasi pilihan kategori pada form edit
        $query->whereIn('id', $magangIds);

        // [MAGANG] override agar tanpa filter bidang/subbidang (biar tidak kosong)
        $query = KategoriPengetahuan::query()
            ->whereIn('id', $magangIds)
            ->orderBy('id');

        $kategori = $query->get();

        return view('magang.berbagipengetahuan-edit', compact('artikelpengetahuan', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        // [MAGANG] daftar ID kategori khusus magang
        $magangIds = [21, 22, 23, 24, 26, 27]; // <-- semula [26]

        $userId = auth()->id();

        $artikelpengetahuan = ArtikelPengetahuan::where('id', $id)
            ->where('pengguna_id', $userId)
            ->whereIn('kategori_pengetahuan_id', $magangIds) // [MAGANG] batasi
            ->firstOrFail();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:artikelpengetahuan,slug,' . $artikelpengetahuan->id,
            'kategori_pengetahuan_id' => [
                'required',
                'exists:kategori_pengetahuan,id',
                Rule::in($magangIds), // [MAGANG] wajib kategori khusus magang
            ],
            'thumbnail' => 'nullable|image|max:2048',
            'filedok' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'isi' => 'required|string',
        ]);

        // Thumbnail
        if ($request->hasFile('thumbnail')) {
            if ($artikelpengetahuan->thumbnail && Storage::disk('public')->exists($artikelpengetahuan->thumbnail)) {
                Storage::disk('public')->delete($artikelpengetahuan->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        } else {
            $validated['thumbnail'] = $artikelpengetahuan->thumbnail;
        }

        // Filedok
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
            ->route('magang.berbagipengetahuan.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Hapus artikel.
     */
    public function destroy($id)
    {
        // [MAGANG] daftar ID kategori khusus magang
        $magangIds = [21, 22, 23, 24, 26, 27]; // <-- semula [26]

        $userId = auth()->id();

        $artikelpengetahuan = ArtikelPengetahuan::where('id', $id)
            ->where('pengguna_id', $userId)
            ->whereIn('kategori_pengetahuan_id', $magangIds) // [MAGANG] batasi
            ->firstOrFail();

        if ($artikelpengetahuan->thumbnail && Storage::disk('public')->exists($artikelpengetahuan->thumbnail)) {
            Storage::disk('public')->delete($artikelpengetahuan->thumbnail);
        }

        if ($artikelpengetahuan->filedok && Storage::disk('public')->exists($artikelpengetahuan->filedok)) {
            Storage::disk('public')->delete($artikelpengetahuan->filedok);
        }

        $artikelpengetahuan->delete();

        return redirect()
            ->route('magang.berbagipengetahuan.index')
            ->with('deleted', 'Artikel berhasil dihapus.');
    }
}
