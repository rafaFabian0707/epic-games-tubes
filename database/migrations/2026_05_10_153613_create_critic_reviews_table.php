<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `critic_reviews` (
 *   critic_review_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   game_id BIGINT UNSIGNED NOT NULL, FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
 *   critic_rating VARCHAR(10) NULL,
 *   score VARCHAR(10) NULL,
 *   author VARCHAR(100) NULL,
 *   pub VARCHAR(100) NOT NULL,
 *   text TEXT NULL,
 *   created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
 * );
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
