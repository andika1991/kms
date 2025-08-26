<?php

namespace App\Models;

use App\Scopes\ViewsCountScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dokumen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dokumen';

    /**
     * Kolom yang boleh diisi mass-assignment.
     */
    protected $fillable = [
        'id',
        'nama_dokumen',
        'path_dokumen',
        'thumbnail',
        'encrypted_key',
        'deskripsi',
        'kategori_dokumen_id',
        'pengguna_id',
 
    ];

    /**
     * Cast kolom terenkripsi.
     *
     * Laravel akan otomatis mengenkripsi saat menyimpan,
     * dan mendekripsi saat membaca.
     */
    protected $casts = [
        'nama_dokumen'     => 'encrypted',
        'deskripsi'        => 'encrypted',
        'encrypted_key'    => 'encrypted',
    ];

    /**
     * Relasi ke KategoriDokumen.
     */
    public function kategoriDokumen()
    {
        return $this->belongsTo(KategoriDokumen::class, 'kategori_dokumen_id');
    }

    /**
     * Relasi ke Pengguna.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function aksesDokumenPengguna()
    {
        return $this->hasMany(AksesDokumenPengguna::class, 'dokumen_id', 'id');
    }

    public function views()
    {
        return $this->hasMany(\App\Models\DocumentView::class, 'dokumen_id');
    }

    public function viewers() // opsional kalau mau ambil daftar user yang melihat
    {
        return $this->belongsToMany(
            \App\Models\User::class,
            'document_views',
            'dokumen_id',
            'user_id'
        )->withPivot('viewed_at')->withTimestamps();
    }

    // ===== Pasang Global Scope sekali untuk semua query Dokumen =====
    protected static function booted()
    {
        static::addGlobalScope(new ViewsCountScope); // -> otomatis ada attribute views_count
    }

    // Opsional: accessor yang selalu mengembalikan angka aman (dengan fallback)
    protected $appends = ['views_total'];

    public function getViewsTotalAttribute(): int
    {
        // Jika views_count sudah ada (dari scope), pakai itu, jika tidak hitung langsung
        return (int) ($this->attributes['views_count'] ?? $this->getAttribute('views_count') ?? $this->views()->count());
    }

}
