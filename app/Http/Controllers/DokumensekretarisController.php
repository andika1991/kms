<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumensekretarisController extends Controller
{
    public function index(Request $request)
    {
        $dokumenQuery = Dokumen::with(['kategoriDokumen', 'user'])
            ->where('pengguna_id', auth()->id());

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $dokumenQuery->whereRaw('LOWER(nama_dokumen) LIKE ?', ["%{$search}%"]);
        }

        $dokumen = $dokumenQuery->latest()->get();

        // Hanya ambil kategori yang tidak memiliki bidang maupun subbidang (untuk sekretaris)
        $kategori = KategoriDokumen::whereNull('bidang_id')
            ->whereNull('subbidang_id')
            ->get();

        return view('sekretaris.dokumen.index', compact('dokumen', 'kategori'));
    }

    public function create()
    {
        $kategori = KategoriDokumen::whereNull('bidang_id')
        ->whereNull('subbidang_id')
        ->get();

        return view('sekretaris.dokumen.create', compact('kategori'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dokumen'         => ['required', 'string', 'max:255'],
            'deskripsi'            => ['required', 'string'],
            'kategori_dokumen_id'  => ['required', 'exists:kategori_dokumen,id'],
            'path_dokumen'         => ['required', 'file', 'max:10240'],
            'encrypted_key'        => ['nullable', 'string', 'max:255'],
        ]);

        $kategori = KategoriDokumen::findOrFail($validated['kategori_dokumen_id']);

        if (strtolower($kategori->nama_kategoridokumen) === 'rahasia') {
            $request->validate([
                'encrypted_key' => ['required', 'string', 'max:255'],
            ]);
            $validated['encrypted_key'] = $request->encrypted_key;
        } else {
            $validated['encrypted_key'] = null;
        }

        if ($request->hasFile('path_dokumen')) {
            $validated['path_dokumen'] = $request->file('path_dokumen')->store('dokumen', 'public');
        } else {
            return back()->withErrors(['path_dokumen' => 'File dokumen wajib diunggah.']);
        }

        $validated['pengguna_id'] = auth()->id();

        Dokumen::create($validated);

        return redirect()->route('sekretaris.manajemendokumen.index')
            ->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function show(Request $request, $id)
    {
        $dokumen = Dokumen::with(['kategoriDokumen', 'user'])->findOrFail($id);

        // Log view
        if (auth()->check()) {
            $currentUser = \App\Models\User::find(auth()->id());
            \App\Models\DocumentView::updateOrCreate(
                [
                    'dokumen_id' => $dokumen->id,
                    'user_id' => auth()->id(),
                ],
                [
                    'viewed_at' => now(),
                ]
            );
        }

        $viewers = \App\Models\DocumentView::where('dokumen_id', $dokumen->id)->with('pengguna')->latest('viewed_at')->get();

        $isRahasia = $dokumen->kategoriDokumen 
        && $dokumen->kategoriDokumen->nama_kategoridokumen == 'Rahasia';

    if ($isRahasia) {
        $inputKey = $request->encrypted_key;

        if (!$inputKey) {
            return redirect()->route('pegawai.manajemendokumen.index')
                ->with('error', 'Kunci dokumen diperlukan untuk mengakses dokumen rahasia.');
        }

        // Dekripsi key dari DB
        try {
            $decryptedKey = decrypt($dokumen->encrypted_key);
        } catch (\Exception $e) {
            // Jika gagal dekripsi, berarti ada masalah data
            return redirect()->route('sekretaris.manajemendokumen.index')
                ->with('error', 'Data kunci dokumen tidak valid.');
        }

        if ($inputKey !== $decryptedKey) {
            return redirect()->route('sekretaris.manajemendokumen.index')
                ->with('error', 'Kunci dokumen salah.');
        }
    }


        return view('sekretaris.dokumen.show', compact('dokumen', 'viewers'));
    }

    public function edit(Dokumen $manajemendokuman)
    {
        $kategori = KategoriDokumen::whereNull('bidang_id')
        ->whereNull('subbidang_id')
        ->get();
    
        return view('sekretaris.dokumen.edit', compact('manajemendokuman', 'kategori'));
    
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
            if (
                $dokumen->path_dokumen &&
                Storage::disk('public')->exists($dokumen->path_dokumen)
            ) {
                Storage::disk('public')->delete($dokumen->path_dokumen);
            }

            $validated['path_dokumen'] = $request->file('path_dokumen')
                ->store('dokumen', 'public');
        } else {
            unset($validated['path_dokumen']);
        }

        $dokumen->update($validated);

        return redirect()->route('sekretaris.manajemendokumen.index')
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

            return redirect()->route('sekretaris.manajemendokumen.index')
                ->with('deleted', 'Dokumen berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('sekretaris.manajemendokumen.index')
                ->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }
}
