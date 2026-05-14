<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model Game — merepresentasikan tabel `games` (ERD Final v4.0)
 *
 * PENTING — Perubahan dari versi sebelumnya:
 *  - Kolom `description`       → DIHAPUS  (ganti: main_desc + desc)
 *  - Kolom `addon_type`        → DIHAPUS
 *  - Kolom `critic_score`      → DIHAPUS
 *  - Kolom `critic_count`      → DIHAPUS
 *  - Kolom `refund_policy`     → DIHAPUS
 *  - Kolom `age_rating`        → DIHAPUS  (pindah ke tabel `age`)
 *  - Kolom baru: info, media_url, main_desc, announce, desc, icon_url
 *  - Relasi baru: platforms (N:M), ageRating (1:1)
 *  - Tabel game_media          → DIHAPUS  (ganti: kolom media_url di sini)
 */
class Game extends Model
{
    use HasFactory;

    // =========================================================
    // TABLE CONFIG
    // =========================================================

    protected $table      = 'games';
    protected $primaryKey = 'game_id';

    protected $fillable = [
        'title',
        // Kolom baru v4.0
        'info',           // ENUM: First_Run | Now_On_Epic | Trial_Available
        'media_url',      // URL trailer / media utama (ganti game_media table)
        'main_desc',      // Deskripsi singkat (tampil di atas halaman detail)
        'announce',       // Teks pengumuman / banner (opsional)
        'desc',           // Deskripsi lengkap
        'icon_url',       // Icon game kecil (sidebar detail)
        // Kolom lama yang masih ada
        'base_price',
        'release_date',
        'publisher_id',
        'developer_id',
        'cover_image_url',
        'game_type',      // ENUM: base_game | edition | addon | dll.
        'parent_game_id',
        'avg_rating',
        'refund_type',    // ENUM: refundable | self_refundable | non_refundable
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_price'   => 'decimal:2',
            'avg_rating'   => 'decimal:1',
            'is_active'    => 'boolean',
            'release_date' => 'date',
        ];
    }

    // =========================================================
    // SCOPES — filter umum
    // =========================================================

    /** Hanya tampilkan game yang aktif (is_active = 1) */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Hanya base_game (bukan addon, bundle, dll.) */
    public function scopeBaseGame($query)
    {
        return $query->where('game_type', 'base_game');
    }

    /** Game gratis */
    public function scopeFree($query)
    {
        return $query->where('base_price', 0);
    }

    // =========================================================
    // RELATIONS — Master / Lookup
    // =========================================================

    public function publisher()
    {
        return $this->belongsTo(publishers::class, 'publisher_id', 'publisher_id');
    }

    public function developer()
    {
        return $this->belongsTo(developers::class, 'developer_id', 'developer_id');
    }

    // =========================================================
    // RELATIONS — Many-to-Many (Junction Tables)
    // =========================================================

    public function genres()
    {
        return $this->belongsToMany(
            genres::class,
            'game_genre', 'game_id', 'genre_id'
        );
    }

    public function features()
    {
        return $this->belongsToMany(
            features::class,
            'game_features', 'game_id', 'feature_id'
        );
    }

    public function tags()
    {
        return $this->belongsToMany(
            tags::class,
            'game_tags', 'game_id', 'tag_id'
        );
    }

    /**
     * BARU (ERD v4.0): Platform yang didukung game ini.
     * Junction table: game_platform
     */
    public function platforms()
    {
        return $this->belongsToMany(
            Platform::class,
            'game_platform', 'game_id', 'platform_id'
        );
    }

    // =========================================================
    // RELATIONS — One-to-One
    // =========================================================

    /**
     * BARU (ERD v4.0): Age rating game (E, T, M, AO, dll.)
     * Menggantikan kolom age_rating yang dihapus dari tabel games.
     */
    public function ageRating()
    {
        return $this->hasOne(Age::class, 'game_id', 'game_id');
    }

    public function systemRequirements()
    {
        return $this->hasOne(system_requirements::class, 'game_id', 'game_id');
    }

    // =========================================================
    // RELATIONS — One-to-Many
    // =========================================================

    public function achievements()
    {
        return $this->hasMany(achievements::class, 'game_id', 'game_id');
    }

    public function discounts()
    {
        return $this->hasMany(discounts::class, 'game_id', 'game_id');
    }

    public function criticReviews()
    {
        return $this->hasMany(critic_reviews::class, 'game_id', 'game_id');
    }

    public function socialLinks()
    {
        return $this->hasMany(game_social_links::class, 'game_id', 'game_id');
    }

    /**
     * Self-referential: game induk (mis. base game dari sebuah Edition)
     */
    public function parentGame()
    {
        return $this->belongsTo(Game::class, 'parent_game_id', 'game_id');
    }

    /**
     * Self-referential: semua turunan (Edition, Addon, Bundle, dll.)
     */
    public function children()
    {
        return $this->hasMany(Game::class, 'parent_game_id', 'game_id')
                    ->where('is_active', true);
    }

    // =========================================================
    // RELATIONS — Pivot/Transaksional
    // =========================================================

    public function transactionDetails()
    {
        return $this->hasMany(transaction_details::class, 'game_id', 'game_id');
    }

    public function libraryEntries()
    {
        return $this->hasMany(Library::class, 'game_id', 'game_id');
    }

    // =========================================================
    // ACCESSORS — Computed Properties
    // =========================================================

    /**
     * Hitung harga final setelah diskon aktif (jika ada).
     * Sama dengan logika MySQL function fn_get_final_price().
     * Gunakan: $game->final_price
     */
    public function getFinalPriceAttribute(): float
    {
        $activeDiscount = $this->discounts()
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderByDesc('discount_pct')
            ->first();

        if (! $activeDiscount) {
            return (float) $this->base_price;
        }

        return round(
            $this->base_price * (1 - $activeDiscount->discount_pct / 100),
            2
        );
    }

    /**
     * Persentase diskon aktif (0 jika tidak ada diskon).
     * Gunakan: $game->discount_pct
     */
    public function getDiscountPctAttribute(): int
    {
        if ($this->final_price >= $this->base_price || $this->base_price == 0) {
            return 0;
        }

        return (int) round((1 - $this->final_price / $this->base_price) * 100);
    }

    /**
     * Label info badge yang siap tampil (spasi, bukan underscore).
     * Gunakan: $game->info_label
     */
    public function getInfoLabelAttribute(): ?string
    {
        return $this->info ? str_replace('_', ' ', $this->info) : null;
    }
}
