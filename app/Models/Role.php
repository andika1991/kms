<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * Nama tabel.
     *
     * @var string
     */
    protected $table = 'role';



    protected $primaryKey = 'id';

    /**
     * Kolom yang bisa diisi mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_role',
        'role_group',
        'parent_id',
        'bidang_id',
        'subbidang_id',
    ];

public function users()
{
    return $this->hasMany(User::class);
}
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

  
    public function subbidang()
    {
        return $this->belongsTo(Subbidang::class, 'subbidang_id');
    }

   
    public function parent()
    {
        return $this->belongsTo(Role::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Role::class, 'parent_id');
    }
}
