<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AksesDokumenPengguna extends Model
{
    use HasFactory;

    protected $table = 'aksesdokumenpengguna';
    protected $primaryKey = 'id';

    protected $fillable = [
        'pengguna_id',
        'dokumen_id',
    ];

    // Relasi ke model User (Pengguna)
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id', 'id');
    }

    // Relasi ke model Dokumen
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id', 'id');
    }
}
