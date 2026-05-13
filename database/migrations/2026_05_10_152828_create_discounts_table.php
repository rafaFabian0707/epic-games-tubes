<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('discounts', function (Blueprint $table) {

            $table->id('discount_id');

            $table->foreignId('game_id')
                ->constrained('games', 'game_id')
                ->cascadeOnDelete();

            $table->decimal('discount_pct', 5, 2);

            $table->dateTime('start_date');

            $table->dateTime('end_date');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
