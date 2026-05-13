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
         Schema::create('critic_reviews', function (Blueprint $table) {

            $table->id('critic_review_id');

            $table->foreignId('game_id')
                ->constrained('games', 'game_id')
                ->cascadeOnDelete();

            $table->integer('percent')->nullable();

            $table->tinyInteger('avg_score');

            $table->string('critic_rating', 10)->nullable();

            $table->string('score', 10)->nullable();

            $table->string('author', 100)->nullable();

            $table->string('pub', 100);

            $table->text('text')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('critic_reviews');
    }
};
