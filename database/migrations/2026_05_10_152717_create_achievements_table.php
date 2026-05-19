<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `achievements` (
 *   achievement_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   game_id BIGINT UNSIGNED NOT NULL, FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
 *   name VARCHAR(200) NOT NULL,
 *   desc TEXT NULL,
 *   acv_xp VARCHAR(20) NULL,
 *   percent VARCHAR(50) NULL,
 *   icon_url VARCHAR(255) NULL,
 *   created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
 * );
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
