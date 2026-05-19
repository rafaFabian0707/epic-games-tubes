<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `game_platform` (
 *   game_id BIGINT UNSIGNED NOT NULL, FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
 *   platform_id BIGINT UNSIGNED NOT NULL, FOREIGN KEY (platform_id) REFERENCES platform(platform_id) ON DELETE CASCADE,
 *   PRIMARY KEY (game_id, platform_id)
 * );
 */
public function up(): void
    {
        Schema::create('game_platform', function (Blueprint $table) {

            $table->foreignId('game_id')
                ->constrained('games', 'game_id')
                ->cascadeOnDelete();

            $table->foreignId('platform_id')
                ->constrained('platform', 'platform_id')
                ->cascadeOnDelete();

            $table->primary(['game_id', 'platform_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_platform');
    }
};
