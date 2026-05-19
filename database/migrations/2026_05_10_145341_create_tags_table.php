<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `tags` (
 *   tag_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   emoji VARCHAR(10) NOT NULL,
 *   label VARCHAR(100) NOT NULL
 * );
 */
public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {

            $table->id('tag_id');

            $table->string('emoji', 10);

            $table->string('label', 100);

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
