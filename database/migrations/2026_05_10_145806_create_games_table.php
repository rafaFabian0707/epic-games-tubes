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
