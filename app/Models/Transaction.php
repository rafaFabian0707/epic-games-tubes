<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model Transaction — merepresentasikan tabel `transactions` (ERD Final v4.0)
 *
 * PENTING — Perubahan dari versi sebelumnya:
 *  - Kolom `status`  → DIHAPUS sepenuhnya.
 *  - Transaksi bersifat LANGSUNG SELESAI saat diinsert.
 *  - Filter "transaksi selesai" menggunakan: completed_at IS NOT NULL
 *    (BUKAN: status = 'completed')
 *  - Library diisi OTOMATIS oleh trigger trg_after_transaction_insert,
 *    bukan oleh kode Laravel.
 *
 * BUG FIX v1:
 *  - details() sebelumnya memakai `transaction_details::class` (lowercase, SALAH)
 *    → diperbaiki menjadi `TransactionDetail::class` (PascalCase, BENAR)
 */
class Transaction extends Model
{
    use HasFactory;

    // =========================================================
    // TABLE CONFIG
    // =========================================================

    protected $table      = 'transactions';
    protected $primaryKey = 'transaction_id';

    /**
     * Laravel mengelola created_at secara otomatis via timestamps().
     * completed_at diisi manual = NOW() saat insert (transaksi langsung selesai).
     */
    public $timestamps = true; // created_at diurus Laravel

    protected $fillable = [
        'user_id',
        'total_amount',
        'payment_method', // ENUM: credit_card | debit_card | paypal | gift_card
        'completed_at',   // Diisi NOW() saat insert — tidak ada status lagi
    ];

    protected function casts(): array
    {
        return [
            'total_amount'  => 'decimal:2',
            'created_at'    => 'datetime',
            'completed_at'  => 'datetime',
        ];
    }

    // =========================================================
    // SCOPES
    // =========================================================

    /**
     * Filter transaksi yang sudah selesai.
     * WAJIB pakai ini — jangan filter by status karena kolom status sudah DIHAPUS.
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    // =========================================================
    // RELATIONS
    // =========================================================

    /**
     * Transaksi dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Satu transaksi bisa berisi banyak item (detail).
     *
     * FIX: `transaction_details::class` (lowercase) → `TransactionDetail::class`
     */
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'transaction_id');
    }

    /**
     * Shortcut: game-game yang dibeli dalam transaksi ini (via detail).
     */
    public function games()
    {
        return $this->belongsToMany(
            Game::class,
            'transaction_details',
            'transaction_id',
            'game_id'
        )->withPivot('price_at_purchase', 'discount_applied');
    }

    // =========================================================
    // HELPER METHODS
    // =========================================================

    /**
     * Apakah transaksi ini sudah selesai?
     * Pengganti dari: $transaction->status === 'completed'
     */
    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    /**
     * Format metode pembayaran untuk tampilan
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'credit_card' => 'Credit Card',
            'debit_card'  => 'Debit Card',
            'paypal'      => 'PayPal',
            'gift_card'   => 'Gift Card',
            default       => ucfirst($this->payment_method),
        };
    }
}
