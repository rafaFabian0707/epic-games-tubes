<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `game_features` (
 *   game_id BIGINT UNSIGNED NOT NULL, FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
 *   feature_id BIGINT UNSIGNED NOT NULL, FOREIGN KEY (feature_id) REFERENCES features(feature_id) ON DELETE CASCADE,
 *   PRIMARY KEY (game_id, feature_id)
 * );
 */
public function up(): void
    {
         Schema::create('game_features', function (Blueprint $table) {

            $table->foreignId('game_id')
                ->constrained('games', 'game_id')
                ->cascadeOnDelete();

            $table->foreignId('feature_id')
                ->constrained('features', 'feature_id')
                ->cascadeOnDelete();

            $table->primary(['game_id', 'feature_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_features');
    }
};
