<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PERBAIKAN dari versi sebelumnya:
 *  - Tambah kolom `password`      (Laravel auth wajib, ganti password_hash)
 *  - Tambah kolom `is_admin`      (dibutuhkan AdminMiddleware)
 *  - Tambah kolom `remember_token` (dibutuhkan Laravel session auth)
 *  - Rename `full_name` → tetap ada sebagai info tambahan
 *  - Pertahankan `username` sebagai display name
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');

            $table->string('username', 50)->unique();     // display name / login field
            $table->string('email', 100)->unique();       // login utama via Laravel auth
            $table->string('password');                   // WAJIB: Laravel Auth pakai kolom ini
            $table->string('full_name', 100)->nullable(); // nama lengkap (opsional)
            $table->boolean('is_admin')->default(false);  // WAJIB: untuk AdminMiddleware
            $table->boolean('is_active')->default(true);

            $table->rememberToken();                      // WAJIB: untuk "remember me" session
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
