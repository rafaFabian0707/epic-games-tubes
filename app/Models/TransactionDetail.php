<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model TransactionDetail — merepresentasikan tabel `transaction_details`
 *
 * Tabel ini adalah tabel pivot antara transactions dan games,
 * dengan kolom tambahan: price_at_purchase dan discount_applied.
 *
 * CATATAN: Tabel ini tidak perlu timestamps sesuai ERD.
 */
class TransactionDetail extends Model
{
    protected $table      = 'transaction_details';
    protected $primaryKey = null; // composite key, tidak ada single PK

    public $incrementing = false;
    public $timestamps   = false;

    protected $fillable = [
        'transaction_id',
        'game_id',
        'price_at_purchase',
        'discount_applied',
    ];

    protected function casts(): array
    {
        return [
            'price_at_purchase' => 'decimal:2',
            'discount_applied'  => 'decimal:2',
        ];
    }

    // =========================================================
    // RELATIONS
    // =========================================================

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }
}
