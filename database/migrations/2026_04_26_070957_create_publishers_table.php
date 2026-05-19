<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `publishers` (
 *   publisher_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   name VARCHAR(100) NOT NULL
 * );
 */
public function up(): void
    {
        Schema::create('publishers', function (Blueprint $table) {
    $table->id('publisher_id');

    $table->string('name', 100)->unique();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publishers');
    }
};
