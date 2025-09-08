<?php

namespace App\Models;

use App\Scopes\ViewsCountScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ArtikelPengetahuan extends Model
{
    use HasFactory;

    protected $table = 'artikelpengetahuan';

    protected $fillable = [
        'judul',
        'slug',
        'thumbnail',
        'filedok',
        'isi',
        'kategori_pengetahuan_id',
        'pengguna_id',
    ];

    protected $casts = [
        'judul' => 'encrypted',
        'thumbnail' => 'encrypted',
        'filedok' => 'encrypted',
        'isi' => 'encrypted',
    ];

    /**
     * Relasi ke KategoriPengetahuan.
     */
    public function kategoriPengetahuan()
    {
        return $this->belongsTo(KategoriPengetahuan::class, 'kategori_pengetahuan_id');
    }

    /**
     * Relasi ke Pengguna.
     */
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    // ===== Relasi baru untuk view =====
    public function views()
    {
        return $this->hasMany(\App\Models\ArticleView::class, 'artikel_id');
    }

    public function viewers() // opsional, kalau mau ambil daftar user yang melihat
    {
        return $this->belongsToMany(
            \App\Models\User::class,
            'article_views',
            'artikel_id',
            'user_id'
        )->withPivot('viewed_at')->withTimestamps();
    }

    // ===== Pasang Global Scope SEKALI untuk semua query artikel =====
    protected static function booted(): void
    {
        static::addGlobalScope(new ViewsCountScope); // -> menambah kolom virtual `views_count`
    }

    // Opsional: accessor dengan fallback aman
    protected $appends = ['views_total'];

    public function getViewsTotalAttribute(): int
    {
        return (int) ($this->attributes['views_count']
            ?? $this->getAttribute('views_count')
            ?? $this->views()->count());
    }
}
