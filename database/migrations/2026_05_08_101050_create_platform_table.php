<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `platform` (
 *   platform_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   platform VARCHAR(20) NOT NULL
 * );
 */
public function up(): void
    {
        Schema::create('platform', function (Blueprint $table) {
    $table->id('platform_id');

    $table->string('platform', 20);

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform');
    }
};
