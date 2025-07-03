<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subbidang extends Model
{
    use HasFactory;

    /**
     * Tabel yang digunakan
     *
     * @var string
     */
    protected $table = 'subbidang';

    /**
     * Primary key dari tabel
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Kolom yang bisa diisi mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'bidang_id',
    ];

    /**
     * Relasi ke Bidang
     */
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }
}
