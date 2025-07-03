<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    /**
     * Tabel yang digunakan
     *
     * @var string
     */
    protected $table = 'bidang';

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
    ];
}
