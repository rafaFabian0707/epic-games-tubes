<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model News — merepresentasikan tabel `news`
 *
 * Kolom utama:
 *  - news_id      : PK
 *  - title        : judul berita
 *  - cover_url    : URL gambar cover
 *  - main_content : ringkasan / intro
 *  - date         : tanggal artikel (string, misal "May 15, 2026")
 *  - publisher    : nama sumber/penulis
 *  - content      : isi lengkap artikel (longText)
 *  - media_url    : URL video/gambar tambahan (nullable)
 *  - is_active    : apakah artikel dipublikasikan
 *
 * CATATAN: Tabel `news` tidak memakai timestamps (created_at/updated_at)
 * sesuai migration yang sudah ada, jadi $timestamps = false.
 */
class News extends Model
{
    protected $table      = 'news';
    protected $primaryKey = 'news_id';

    // Tabel news tidak memiliki kolom created_at / updated_at di migration
    public $timestamps = false;

    protected $fillable = [
        'title',
        'cover_url',
        'main_content',
        'date',
        'publisher',
        'content',
        'media_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // =========================================================
    // SCOPES
    // =========================================================

    /** Hanya artikel yang dipublikasikan */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // =========================================================
    // ACCESSORS
    // =========================================================

    /**
     * Cek apakah artikel ini memiliki media (video/gambar tambahan)
     */
    public function getHasMediaAttribute(): bool
    {
        return ! empty($this->media_url);
    }

    /**
     * Ringkasan teks dari main_content (untuk preview card)
     * Dibatasi 200 karakter
     */
    public function getExcerptAttribute(): string
    {
        if (! $this->main_content) return '';
        return mb_substr(strip_tags($this->main_content), 0, 200)
            . (mb_strlen(strip_tags($this->main_content)) > 200 ? '...' : '');
    }
}
