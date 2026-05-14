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
           Schema::create('achievements', function (Blueprint $table) {

            $table->id('achievement_id');

            $table->foreignId('game_id')
                ->constrained('games', 'game_id')
                ->cascadeOnDelete();

            $table->integer('total');

            $table->integer('avail_xp');

            $table->string('name', 200);

            $table->text('desc')->nullable();

            $table->string('acv_xp', 20)->nullable();

            $table->string('percent', 50)->nullable();

            $table->string('icon_url')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
