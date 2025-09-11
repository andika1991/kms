<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'pengguna';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'photo_profil',
        'role_id',
        'verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'verified' => 'boolean',
        'name' => 'encrypted',
        'email' => 'encrypted',
       
    ];

    protected $dates = ['deleted_at'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
    public function agenda()
    {
        // Relasi one-to-many, karena 1 user bisa punya banyak agenda
        return $this->hasMany(AgendaPimpinan::class, 'id_pengguna', 'id');
    }

}
