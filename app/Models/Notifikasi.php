<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'judul',
        'isi',
        'sudahdibaca',
        'pengguna_id',
        'dokumen_id',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class);
    }
}
