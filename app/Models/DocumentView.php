<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentView extends Model
{
    use HasFactory;

    protected $fillable = ['dokumen_id', 'user_id', 'viewed_at'];

    public function pengguna()
    {
        // Pastikan model User mengarah ke tabel pengguna!
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id');
    }
}

