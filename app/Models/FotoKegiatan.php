<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoKegiatan extends Model
{
    use HasFactory;

    protected $table = 'fotokegiatan';

    protected $fillable = [
        'path_foto',
        'kegiatan_id',
    ];

    /**
     * Relasi ke kegiatan.
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
