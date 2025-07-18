<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ArtikelPengetahuan extends Model
{
    use HasFactory;

    protected $table = 'artikelpengetahuan';

    protected $fillable = [
        'judul',
        'slug',
        'thumbnail',
        'filedok',
        'isi',
        'kategori_pengetahuan_id',
        'pengguna_id',
    ];

    protected $casts = [
        'judul' => 'encrypted',
        'thumbnail' => 'encrypted',
        'filedok' => 'encrypted',
        'isi' => 'encrypted',
    ];

    /**
     * Relasi ke KategoriPengetahuan.
     */
    public function kategoriPengetahuan()
    {
        return $this->belongsTo(KategoriPengetahuan::class, 'kategori_pengetahuan_id');
    }

    /**
     * Relasi ke Pengguna.
     */
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }
}
