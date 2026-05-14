<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // =========================================================
    // TABLE CONFIG
    // =========================================================

    protected $table    = 'users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
        ];
    }

    // =========================================================
    // RELATIONS
    // =========================================================

    /**
     * Satu user bisa punya banyak transaksi.
     * BR-16: Setiap transaksi milik tepat satu user.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id', 'user_id');
    }

    /**
     * Library = koleksi game yang dimiliki user.
     * Diisi secara OTOMATIS oleh trigger trg_after_transaction_insert.
     * Jangan pernah insert ke library secara manual dari kode Laravel.
     */
    public function library()
    {
        return $this->hasMany(Library::class, 'user_id', 'user_id');
    }

    /**
     * Shortcut: game yang dimiliki user (via library).
     */
    public function ownedGames()
    {
        return $this->belongsToMany(
            Game::class,
            'library',        // pivot table
            'user_id',        // FK di pivot yang mengarah ke User
            'game_id',        // FK di pivot yang mengarah ke Game
            'user_id',        // local key di User
            'game_id'         // local key di Game
        )->withPivot('acquired_at');
    }

    /**
     * Wishlist user — game yang ingin dibeli nanti.
     * Trigger trg_before_wishlist_insert akan menolak insert
     * jika game sudah ada di library.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlists::class, 'user_id', 'user_id');
    }

    // =========================================================
    // HELPER METHODS
    // =========================================================

    /**
     * BR-01: Cek apakah user sudah memiliki game tertentu di library.
     * Digunakan oleh CheckoutController sebelum memproses transaksi.
     *
     * @param  int|array  $gameIds  Satu game_id atau array of game_id
     * @return bool  true jika ADA SATU SAJA game yang sudah dimiliki
     */
    public function alreadyOwns(int|array $gameIds): bool
    {
        $ids = is_array($gameIds) ? $gameIds : [$gameIds];

        return $this->library()
                    ->whereIn('game_id', $ids)
                    ->exists();
    }

    /**
     * Kembalikan game_id yang sudah dimiliki user dari daftar tertentu.
     * Dipakai untuk menampilkan pesan error yang spesifik ("Kamu sudah punya: X, Y").
     *
     * @param  array  $gameIds
     * @return \Illuminate\Support\Collection
     */
    public function ownedFromList(array $gameIds)
    {
        return $this->library()
                    ->whereIn('game_id', $gameIds)
                    ->pluck('game_id');
    }
}
