<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupChat extends Model
{
    use HasFactory;

    protected $table = 'grupchat'; // nama tabel

    /**
     * Kolom yang boleh diisi mass-assignment
     */
    protected $fillable = [
        'nama_grup',
        'deskripsi',
        'grup_role',
        'is_private',
        'is_active',
        'bidang_id',
        'pengguna_id',
    ];

    /**
     * Relasi ke model Bidang (jika ada)
     */
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }
    // GrupChat.php
public function messages()
{
    return $this->hasMany(\App\Models\Message::class, 'grupchat_id');
}

public function pengguna()
{
    return $this->belongsTo(User::class, 'pengguna_id'); // pastikan nama kolom sesuai
}

public function users()
{
    return $this->belongsToMany(
        \App\Models\User::class,
        'grupchat_user', // nama tabel pivot
        'grupchat_id',    // foreign key di tabel pivot mengarah ke grup chat
        'pengguna_id'     // foreign key di tabel pivot mengarah ke user
    );
}

}
