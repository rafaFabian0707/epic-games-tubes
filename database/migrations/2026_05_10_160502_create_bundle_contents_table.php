<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
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
