<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriDokumen extends Model
{
    use HasFactory;

    protected $table = 'kategori_dokumen';

    protected $fillable = [
        'nama_kategoridokumen',
        'subbidang_id',
        'bidang_id'
    ];

 
    public function subbidang()
    {
        return $this->belongsTo(Subbidang::class, 'subbidang_id');
    }

    /**
     * Relasi ke Dokumen.
     */
    public function dokumens()
    {
        return $this->hasMany(Dokumen::class, 'kategori_dokumen_id');
    }
}
