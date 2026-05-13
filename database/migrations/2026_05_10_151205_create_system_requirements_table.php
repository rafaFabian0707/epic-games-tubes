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
        Schema::create('system_requirements', function (Blueprint $table) {

            $table->id('req_id');

            $table->foreignId('game_id')
                ->unique()
                ->constrained('games', 'game_id')
                ->cascadeOnDelete();

            $table->string('min_os', 50)->nullable();

            $table->string('min_cpu', 100)->nullable();

            $table->tinyInteger('min_ram_gb')->nullable();

            $table->string('min_gpu', 100)->nullable();

            $table->smallInteger('min_storage_gb')->nullable();

            $table->string('rec_os', 50)->nullable();

            $table->string('rec_cpu', 100)->nullable();

            $table->tinyInteger('rec_ram_gb')->nullable();

            $table->string('rec_gpu', 100)->nullable();

            $table->smallInteger('rec_storage_gb')->nullable();

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
