<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $primaryKey = 'discount_id';

    protected $fillable = [
        'game_id',
        'discount_pct',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_pct' => 'decimal:2',
            'start_date'   => 'datetime',
            'end_date'     => 'datetime',
            'is_active'    => 'boolean',
        ];
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }

    /** Scope: hanya diskon yang sedang aktif saat ini */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('start_date', '<=', now())
                     ->where('end_date', '>=', now());
    }
}
