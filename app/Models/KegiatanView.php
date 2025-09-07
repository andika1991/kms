<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanView extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_views';

    protected $fillable = [
        'kegiatan_id',
        'user_id',
        'ip',
        'user_agent',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    // Jika pakai tabel pengguna kustom, mapping user_id opsional
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
