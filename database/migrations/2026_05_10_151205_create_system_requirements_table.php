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
