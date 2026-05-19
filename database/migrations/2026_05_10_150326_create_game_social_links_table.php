<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `game_social_links` (
 *   link_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   game_id BIGINT UNSIGNED NOT NULL, FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
 *   platform VARCHAR(50) NOT NULL,
 *   url VARCHAR(255) NOT NULL,
 *   created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
 * );
 */
public function up(): void
    {
        Schema::create('game_social_links', function (Blueprint $table) {

            $table->id('link_id');

            $table->foreignId('game_id')
                ->constrained('games', 'game_id')
                ->cascadeOnDelete();

            $table->string('platform', 50);

            $table->string('url', 255);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_social_links');
    }
};
