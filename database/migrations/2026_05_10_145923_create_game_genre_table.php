<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `game_genre` (
 *   game_id BIGINT UNSIGNED NOT NULL, FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
 *   genre_id BIGINT UNSIGNED NOT NULL, FOREIGN KEY (genre_id) REFERENCES genres(genre_id) ON DELETE CASCADE,
 *   PRIMARY KEY (game_id, genre_id)
 * );
 */
public function up(): void
    {
         Schema::create('game_genre', function (Blueprint $table) {

            $table->foreignId('game_id')
                ->constrained('games', 'game_id')
                ->cascadeOnDelete();

            $table->foreignId('genre_id')
                ->constrained('genres', 'genre_id')
                ->cascadeOnDelete();

            $table->primary(['game_id', 'genre_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_genre');
    }
};
