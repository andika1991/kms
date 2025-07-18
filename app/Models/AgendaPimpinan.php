<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaPimpinan extends Model
{
    use HasFactory;

    protected $table = 'agenda_pimpinan';

    protected $fillable = [
        'nama_agenda',
        'date_agenda',
        'waktu_agenda',
          'waktu_selesai',
        'id_pengguna',
    ];

    /**
     * Relasi ke model Pengguna (User)
     */
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
}
