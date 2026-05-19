<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `genres` (
 *   genre_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   name VARCHAR(50) NOT NULL
 * );
 */
public function up(): void
    {
       Schema::create('genres', function (Blueprint $table) {
    $table->id('genre_id');

    $table->string('name', 50)->unique();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genres');
    }
};
