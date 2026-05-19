<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `games` (
 *   game_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   title VARCHAR(200) NOT NULL,
 *   info ENUM('First_Run', 'Now_On_Epic', 'Trial_Available') NULL,
 *   media_url VARCHAR(255) NULL,
 *   main_desc TEXT NULL,
 *   announce TEXT NULL,
 *   desc TEXT NULL,
 *   icon_url VARCHAR(255) NULL,
 *   release_date DATE NULL,
 *   publisher_id BIGINT UNSIGNED NULL, FOREIGN KEY (publisher_id) REFERENCES publishers(publisher_id) ON DELETE SET NULL,
 *   developer_id BIGINT UNSIGNED NULL, FOREIGN KEY (developer_id) REFERENCES developers(developer_id) ON DELETE SET NULL,
 *   cover_image_url VARCHAR(255) NULL,
 *   game_type ENUM('base_game', 'edition', 'addon', 'aplikasi', 'editor', 'langganan', 'pengalaman', 'bundle', 'demo') NOT NULL DEFAULT 'base_game',
 *   parent_game_id BIGINT UNSIGNED NULL, FOREIGN KEY (parent_game_id) REFERENCES games(game_id) ON DELETE SET NULL,
 *   refund_type ENUM('refundable', 'self_refundable', 'non_refundable') NULL,
 *   is_active TINYINT(1) NOT NULL DEFAULT 1,
 *   created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
 * );
 */
public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {

            $table->id('game_id');

            $table->string('title', 200);

            $table->enum('info', [
                'First_Run',
                'Now_On_Epic',
                'Trial_Available'
            ])->nullable();

            $table->string('media_url')->nullable();

            $table->text('main_desc')->nullable();

            $table->text('announce')->nullable();

            $table->text('desc')->nullable();

            $table->string('icon_url', 255)->nullable();

            $table->decimal('base_price', 9, 2)->nullable();

            $table->date('release_date')->nullable();

            $table->foreignId('publisher_id')
                ->nullable()
                ->constrained('publishers', 'publisher_id')
                ->nullOnDelete();

            $table->foreignId('developer_id')
                ->nullable()
                ->constrained('developers', 'developer_id')
                ->nullOnDelete();

            $table->string('cover_image_url', 255)->nullable();

            $table->enum('game_type', [
                'base_game',
                'edition',
                'addon',
                'aplikasi',
                'editor',
                'langganan',
                'pengalaman',
                'bundle',
                'demo'
            ])->default('base_game');

            $table->foreignId('parent_game_id')
                ->nullable()
                ->constrained('games', 'game_id')
                ->nullOnDelete();

            $table->decimal('avg_rating', 2, 1)->nullable();

            $table->enum('refund_type', [
                'refundable',
                'self_refundable',
                'non_refundable'
            ])->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
