<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Wishlist — merepresentasikan tabel `wishlist`
 *
 * Tabel wishlist punya timestamps (created_at/updated_at) dari migration,
 * plus kolom `added_at` (useCurrent) sebagai waktu resmi ditambahkan.
 */
class Wishlist extends Model
{
    protected $table      = 'wishlist';
    protected $primaryKey = 'wishlist_id';

    // Migration punya timestamps() jadi biarkan default true
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'game_id',
        'added_at',
    ];

    protected function casts(): array
    {
        return [
            'added_at' => 'datetime',
        ];
    }

    // =========================================================
    // RELATIONS
    // =========================================================

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }
}
