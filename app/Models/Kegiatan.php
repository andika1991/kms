<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;
    protected $table = 'kegiatan';

    protected $fillable = [
        'nama_kegiatan',
        'deskripsi_kegiatan',
        'kategori_kegiatan',
        'subbidang_id',
        'pengguna_id',
        'bidang_id'
    ];

    

    /**
     * Relasi ke model Subbidang.
     */
    public function subbidang()
    {
        return $this->belongsTo(Subbidang::class);
    }

    /**
     * Relasi ke model Pengguna.
     */
    public function pengguna()
    {
        return $this->belongsTo(User::class);
    }

  public function bidang()
{
    return $this->belongsTo(Bidang::class);
}

  
public function fotokegiatan()
{
    return $this->hasMany(FotoKegiatan::class, 'kegiatan_id');
}

}
