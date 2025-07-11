<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GrupChatUser extends Pivot
{
    protected $table = 'grupchat_user';

    protected $fillable = [
        'pengguna_id',
        'grupchat_id',
    ];

   
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    // (Opsional) relasi ke model GrupChat
    public function grupchat()
    {
        return $this->belongsTo(GrupChat::class, 'grupchat_id');
    }

    
}

