<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `game_tags` (
 *   game_id BIGINT UNSIGNED NOT NULL, FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
 *   tag_id BIGINT UNSIGNED NOT NULL, FOREIGN KEY (tag_id) REFERENCES tags(tag_id) ON DELETE CASCADE,
 *   PRIMARY KEY (game_id, tag_id)
 * );
 */
public function up(): void
    {
        Schema::create('game_tags', function (Blueprint $table) {

            $table->foreignId('game_id')
                ->constrained('games', 'game_id')
                ->cascadeOnDelete();

            $table->foreignId('tag_id')
                ->constrained('tags', 'tag_id')
                ->cascadeOnDelete();

            $table->primary(['game_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_tags');
    }
};
