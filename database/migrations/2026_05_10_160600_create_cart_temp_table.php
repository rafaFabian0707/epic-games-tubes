<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel cart_temp — digunakan sementara oleh sp_checkout
 *
 * Bukan keranjang permanen. Laravel mengisi tabel ini sesaat sebelum
 * memanggil CALL sp_checkout(...), dan SP akan menghapus isinya sendiri
 * setelah transaksi selesai.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_temp', function (Blueprint $table) {
            $table->id('cart_temp_id');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('game_id');
            $table->decimal('price_at_purchase', 10, 2);
            $table->decimal('discount_applied', 5, 2)->default(0);

            // Tidak perlu FK constraint — tabel ini bersifat sementara
            // dan di-delete setelah SP selesai
            $table->index('user_id'); // SP filter by user_id
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_temp');
    }
};
