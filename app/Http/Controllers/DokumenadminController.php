<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Bidang;
use App\Models\Subbidang;
use App\Models\KategoriDokumen;
use App\Models\DocumentView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenAdminController extends Controller
{
    public function index(Request $request)
    {
        $dokumenQuery = Dokumen::with(['kategoriDokumen', 'user']);

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $dokumenQuery->whereRaw('LOWER(nama_dokumen) LIKE ?', ["%{$search}%"]);
        }

        $dokumen = $dokumenQuery->latest()->get();
      
        $bidangList = Bidang::all();
        $subbidangList = SubBidang::all();


        $kategori = KategoriDokumen::all(); // Tidak dibatasi bidang/subbidang

        return view('admin.dokumen.index', compact('dokumen', 'kategori', 'bidangList', 'subbidangList'));
    }

    public function create()
    {
        $kategori = KategoriDokumen::all(); // Semua kategori dokumen
        return view('admin.dokumen.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dokumen'         => ['required', 'string', 'max:255'],
            'deskripsi'            => ['required', 'string'],
            'kategori_dokumen_id'  => ['required', 'exists:kategori_dokumen,id'],
            'path_dokumen'         => ['required', 'file', 'max:10240'],
        ]);

        if ($request->hasFile('path_dokumen')) {
            $validated['path_dokumen'] = $request->file('path_dokumen')->store('dokumen', 'public');
        } else {
            return back()->withErrors(['path_dokumen' => 'File dokumen wajib diunggah.']);
        }

        $validated['pengguna_id'] = auth()->id();

        Dokumen::create($validated);

        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function show($id)
    {
        $dokumen = Dokumen::with(['kategoriDokumen', 'user'])->findOrFail($id);

        DocumentView::updateOrCreate(
            [
                'dokumen_id' => $dokumen->id,
                'user_id' => auth()->id(),
            ],
            [
                'viewed_at' => now(),
            ]
        );

        $viewers = DocumentView::where('dokumen_id', $dokumen->id)
            ->with('pengguna')
            ->latest('viewed_at')
            ->get();

        return view('admin.dokumen.show', compact('dokumen', 'viewers'));
    }

    public function edit($id)
    {
        $manajemendokuman = Dokumen::findOrFail($id);
        $kategori = KategoriDokumen::all();
        return view('admin.dokumen.edit', compact('manajemendokuman', 'kategori'));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_dokumen'         => ['required', 'string', 'max:255'],
            'deskripsi'            => ['nullable', 'string'],
            'kategori_dokumen_id'  => ['required', 'exists:kategori_dokumen,id'],
            'path_dokumen'         => ['nullable', 'file', 'max:10240'],
        ]);

        $dokumen = Dokumen::findOrFail($id);

        if ($request->hasFile('path_dokumen')) {
            if ($dokumen->path_dokumen && Storage::disk('public')->exists($dokumen->path_dokumen)) {
                Storage::disk('public')->delete($dokumen->path_dokumen);
            }

            $validated['path_dokumen'] = $request->file('path_dokumen')->store('dokumen', 'public');
        } else {
            unset($validated['path_dokumen']);
        }

        $dokumen->update($validated);

        return redirect()->route('admin.manajemendokumen.index')
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $dokumen = Dokumen::findOrFail($id);

            if ($dokumen->path_dokumen && Storage::disk('public')->exists($dokumen->path_dokumen)) {
                Storage::disk('public')->delete($dokumen->path_dokumen);
            }

            $dokumen->delete();

            return redirect()->route('admin.manajemendokumen.index')
                ->with('deleted', 'Dokumen berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.manajemendokumen.index')
                ->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }
}
