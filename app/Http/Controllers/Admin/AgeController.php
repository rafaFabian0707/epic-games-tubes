<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Age — merepresentasikan tabel `age` (ERD Final v4.0)
 *
 * Relasi 1:1 dengan tabel games.
 * Satu game hanya boleh punya satu age rating.
 *
 * SKEMA tabel `age`:
 *   age_id   INT PK AUTO_INCREMENT
 *   game_id  INT FK UNIQUE → games.game_id (cascadeOnDelete)
 *   age      VARCHAR(10) NULLABLE   ← contoh: "E", "T", "M", "AO"
 *   desc     VARCHAR(50) NULLABLE   ← contoh: "Mild Violence"
 *   created_at, updated_at          ← migration punya timestamps()
 *
 * BUG FIX (dari versi sebelumnya):
 *  - primaryKey  : 'id'    → 'age_id'  (sesuai migration)
 *  - timestamps  : false   → true      (migration punya timestamps())
 *  - fillable    : []      → ['game_id', 'age', 'desc']
 *  - Tambah relasi game() yang sebelumnya tidak ada
 */
class Age extends Model
{
    protected $table      = 'age';
    protected $primaryKey = 'age_id';  // FIX: sebelumnya 'id' — SALAH

    // FIX: migration punya $table->timestamps(), jadi ini harus true
    public $timestamps = true;

    protected $fillable = [
        'game_id',
        'age',
        'desc',
    ];

    // =========================================================
    // RELATIONS
    // =========================================================

    /**
     * Age rating ini milik satu game (relasi balik dari hasOne).
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }
}
