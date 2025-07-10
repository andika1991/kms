<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $table = 'message';

    protected $fillable = [
        'message',
        'file',
        'grupchat_id',
        'pengguna_id',
    ];

    /**
     * Relasi ke grup chat.
     */
    public function grupchat()
    {
        return $this->belongsTo(GrupChat::class, 'grupchat_id');
    }

    /**
     * Relasi ke pengguna.
     */
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }
}
