<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); // primary key custom

            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();

            // WAJIB untuk Laravel Auth
            $table->string('password');

            $table->string('full_name', 100)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};