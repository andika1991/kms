<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPengetahuan extends Model
{
    use HasFactory;

    protected $table = 'kategori_pengetahuan';

    protected $fillable = [
        'nama_kategoripengetahuan',
        'subbidang_id',   'bidang_id'
        ,
    ];

    /**
     * Relasi ke Subbidang.
     */
    public function subbidang()
    {
        return $this->belongsTo(Subbidang::class);
    }
}
