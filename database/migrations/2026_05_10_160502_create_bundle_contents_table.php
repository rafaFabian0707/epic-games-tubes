<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `bundle_contents` (
 *   bundle_id BIGINT UNSIGNED NOT NULL, FOREIGN KEY (bundle_id) REFERENCES games(game_id) ON DELETE CASCADE,
 *   included_game_id BIGINT UNSIGNED NOT NULL, FOREIGN KEY (included_game_id) REFERENCES games(game_id) ON DELETE CASCADE,
 *   PRIMARY KEY (bundle_id, included_game_id)
 * );
 */
public function up(): void
    {
        Schema::create('bundle_contents', function (Blueprint $table) {

            $table->foreignId('bundle_id')
                ->constrained('games', 'game_id')
                ->cascadeOnDelete();

            $table->foreignId('included_game_id')
                ->constrained('games', 'game_id')
                ->cascadeOnDelete();

            $table->primary(['bundle_id', 'included_game_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bundle_contents');
    }
};
