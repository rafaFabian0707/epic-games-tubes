<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Library — merepresentasikan tabel `library` (ERD Final v4.0)
 *
 * PENTING — Perubahan dari versi sebelumnya:
 *  - Kolom `playtime_mins` → DIHAPUS (tidak bisa ditrack dari web).
 *
 * ARSITEKTUR PENTING:
 *  Library TIDAK BOLEH diinsert manual dari kode Laravel.
 *  Record library dibuat OTOMATIS oleh MySQL trigger:
 *      trg_after_transaction_insert (AFTER INSERT ON transactions)
 *  Kode Laravel hanya membaca library, tidak pernah menulis langsung.
 */
class Library extends Model
{
    // =========================================================
    // TABLE CONFIG
    // =========================================================

    protected $table      = 'library';
    protected $primaryKey = 'library_id';

    /**
     * Tabel library tidak punya updated_at.
     * acquired_at adalah timestamp saat game masuk library (diset oleh trigger).
     */
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'game_id',
        'acquired_at',
        // 'playtime_mins' → DIHAPUS di ERD v4.0
    ];

    protected function casts(): array
    {
        return [
            'acquired_at' => 'datetime',
        ];
    }

    // =========================================================
    // RELATIONS
    // =========================================================

    /**
     * Library entry ini milik satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Library entry ini merujuk ke satu game.
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }
}
