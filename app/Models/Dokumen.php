<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dokumen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dokumen';

    /**
     * Kolom yang boleh diisi mass-assignment.
     */
    protected $fillable = [
        'id',
        'nama_dokumen',
        'path_dokumen',
        'thumbnail',
        'encrypted_key',
        'deskripsi',
        'kategori_dokumen_id',
        'pengguna_id',
 
    ];

    /**
     * Cast kolom terenkripsi.
     *
     * Laravel akan otomatis mengenkripsi saat menyimpan,
     * dan mendekripsi saat membaca.
     */
    protected $casts = [
        'nama_dokumen'     => 'encrypted',
        'deskripsi'        => 'encrypted',
        'encrypted_key'    => 'encrypted',
    ];

    /**
     * Relasi ke KategoriDokumen.
     */
    public function kategoriDokumen()
    {
        return $this->belongsTo(KategoriDokumen::class, 'kategori_dokumen_id');
    }

    /**
     * Relasi ke Pengguna.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function aksesDokumenPengguna()
{
    return $this->hasMany(AksesDokumenPengguna::class, 'dokumen_id', 'id');
}

}
