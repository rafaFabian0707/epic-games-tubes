<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `news` (
 *   news_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   title VARCHAR(200) NOT NULL,
 *   cover_url VARCHAR(255) NOT NULL,
 *   main_content TEXT NULL,
 *   date VARCHAR(32) NULL,
 *   publisher TEXT NOT NULL,
 *   media_url VARCHAR(255) NULL,
 *   is_active TINYINT(1) NOT NULL DEFAULT 1
 * );
 */
public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id('news_id');
            $table->string('title', 200);
            $table->string('cover_url', 255);
            $table->text('main_content')->nullable();
            $table->string('date', 32)->nullable();
            $table->text('publisher');
            $table->longText('content');

            $table->string('media_url', 255)->nullable();

            $table->boolean('is_active')
                  ->default(true);

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
