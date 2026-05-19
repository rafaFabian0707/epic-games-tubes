<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;

    protected $table      = 'games';
    protected $primaryKey = 'game_id';

    protected $fillable = [
        'title',
        'info',
        'media_url',
        'main_desc',
        'announce',
        'desc',
        'icon_url',
        'base_price',
        'release_date',
        'publisher_id',
        'developer_id',
        'cover_image_url',
        'game_type',
        'parent_game_id',
        'avg_rating',
        'refund_type',
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
    // SCOPES
    // =========================================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBaseGame($query)
    {
        return $query->where('game_type', 'base_game');
    }

    public function scopeFree($query)
    {
        return $query->where(function ($q) {

            $q->whereNull('base_price')
              ->orWhere('base_price', 0);
        });
    }

    // =========================================================
    // RELATIONS
    // =========================================================

    public function publisher()
    {
        return $this->belongsTo(
            Publisher::class,
            'publisher_id',
            'publisher_id'
        );
    }

    public function developer()
    {
        return $this->belongsTo(
            Developer::class,
            'developer_id',
            'developer_id'
        );
    }

    public function genres()
    {
        return $this->belongsToMany(
            Genre::class,
            'game_genre',
            'game_id',
            'genre_id'
        );
    }

    public function features()
    {
        return $this->belongsToMany(
            Feature::class,
            'game_features',
            'game_id',
            'feature_id'
        );
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'game_tags',
            'game_id',
            'tag_id'
        );
    }

    public function platforms()
    {
        return $this->belongsToMany(
            Platform::class,
            'game_platform',
            'game_id',
            'platform_id'
        );
    }

    public function ageRating()
    {
        return $this->hasOne(
            Age::class,
            'game_id',
            'game_id'
        );
    }

    public function systemRequirements()
    {
        return $this->hasOne(
            SystemRequirement::class,
            'game_id',
            'game_id'
        );
    }

    public function achievements()
    {
        return $this->hasMany(
            Achievement::class,
            'game_id',
            'game_id'
        );
    }

    public function discounts()
    {
        return $this->hasMany(
            Discount::class,
            'game_id',
            'game_id'
        );
    }

    public function criticReviews()
    {
        return $this->hasMany(
            CriticReview::class,
            'game_id',
            'game_id'
        );
    }

    public function socialLinks()
    {
        return $this->hasMany(
            GameSocialLink::class,
            'game_id',
            'game_id'
        );
    }

    public function parentGame()
    {
        return $this->belongsTo(
            Game::class,
            'parent_game_id',
            'game_id'
        );
    }

    public function children()
    {
        return $this->hasMany(
            Game::class,
            'parent_game_id',
            'game_id'
        )->where('is_active', true);
    }

    public function transactionDetails()
    {
        return $this->hasMany(
            TransactionDetail::class,
            'game_id',
            'game_id'
        );
    }

    public function libraryEntries()
    {
        return $this->hasMany(
            Library::class,
            'game_id',
            'game_id'
        );
    }

    // =========================================================
    // ACCESSORS
    // =========================================================

    public function getFinalPriceAttribute(): float
    {
        $disc = $this->discounts()
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderByDesc('discount_pct')
            ->first();

        if (!$disc) {

            return (float) ($this->base_price ?? 0);
        }

        return round(
            $this->base_price * (1 - $disc->discount_pct / 100),
            2
        );
    }

    public function getDiscountPctAttribute(): int
    {
        if (
            $this->final_price >= $this->base_price ||
            $this->base_price == 0
        ) {
            return 0;
        }

        return (int) round(
            (1 - $this->final_price / $this->base_price) * 100
        );
    }

    public function getInfoLabelAttribute(): ?string
    {
        return $this->info
            ? str_replace('_', ' ', $this->info)
            : null;
    }
}