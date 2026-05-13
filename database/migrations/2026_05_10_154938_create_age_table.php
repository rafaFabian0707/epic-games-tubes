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
        Schema::create('age', function (Blueprint $table) {

            $table->id('age_id');

            $table->foreignId('game_id')
                ->constrained('games', 'game_id')
                ->cascadeOnDelete();

            $table->string('age', 10);

            $table->string('desc', 50)->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('age');
    }
};
