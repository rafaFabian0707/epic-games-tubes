<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `system_requirements` (
 *   req_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   game_id BIGINT UNSIGNED NOT NULL, FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
 *   min_os VARCHAR(255) NULL,
 *   min_cpu TEXT NULL,
 *   min_gpu TEXT NULL,
 *   rec_os VARCHAR(255) NULL,
 *   rec_cpu TEXT NULL,
 *   rec_gpu TEXT NULL,
 *   created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
 * );
 */
public function up(): void
    {
        Schema::create('system_requirements', function (Blueprint $table) {

            $table->id('req_id');

            $table->foreignId('game_id')
                ->unique()
                ->constrained('games', 'game_id')
                ->cascadeOnDelete();
            $table->string('min_os', 255)->nullable();

            $table->text('min_cpu')->nullable();

            $table->Integer('min_ram_gb')->nullable();

            $table->text('min_gpu')->nullable();

            $table->Integer('min_storage_gb')->nullable();

            $table->string('rec_os', 255)->nullable();

            $table->text('rec_cpu')->nullable();

            $table->Integer('rec_ram_gb')->nullable();

            $table->text('rec_gpu')->nullable();

            $table->Integer('rec_storage_gb')->nullable();

            $table->longText('languange')->nullable();

            $table->longText('policy')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_requirements');
    }
};
