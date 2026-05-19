<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `age` (
 *   age_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   game_id BIGINT UNSIGNED NOT NULL, FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
 *   age VARCHAR(10) NOT NULL,
 *   desc VARCHAR(50) NULL,
 *   created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
 * );
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
