<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GrupChatMessageController extends Controller
{
    /**
     * Simpan pesan baru ke grup chat.
     */
    public function store(Request $request, $grupchat_id)
    {
        $request->validate([
            'message' => 'required|string',
            'file' => 'nullable|file|max:20480' // Maksimal 20MB
        ]);

        
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('chat_files', 'public');
        }

        // Simpan pesan ke database
        Message::create([
            'message' => $request->message,
            'file' => $filePath,
            'grupchat_id' => $grupchat_id,
            'pengguna_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Pesan berhasil dikirim!');
    }
}
