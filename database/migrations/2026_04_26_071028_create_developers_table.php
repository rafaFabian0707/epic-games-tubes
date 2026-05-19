<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
 * SQL equivalent for this migration:
 * CREATE TABLE `developers` (
 *   developer_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   name VARCHAR(100) NOT NULL
 * );
 */
public function up(): void
    {
        Schema::create('developers', function (Blueprint $table) {
    $table->id('developer_id');


    $table->string('name', 100);

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developers');
    }
};
