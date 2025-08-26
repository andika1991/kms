<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleView extends Model
{
    use HasFactory;

    protected $table = 'article_views';
    protected $fillable = ['artikel_id', 'user_id', 'viewed_at'];

    public function artikel()
    {
        return $this->belongsTo(ArtikelPengetahuan::class, 'artikel_id');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
