<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * AdminUserSeeder
 *
 * Jalankan dengan:
 *   php artisan db:seed --class=AdminUserSeeder
 *
 * Atau tambahkan ke DatabaseSeeder:
 *   $this->call(AdminUserSeeder::class);
 *
 * Kredensial default:
 *   Email    : admin@epicgames.com
 *   Password : Admin@12345
 *
 * PENTING: Ganti password setelah pertama kali login!
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah sudah ada admin
        $existing = User::where('is_admin', true)->first();

        if ($existing) {
            $this->command->info('Admin sudah ada: ' . $existing->email);
            return;
        }

        $admin = User::create([
            'username'  => 'admin',
            'email'     => 'admin@epicgames.com',
            'password'  => Hash::make('Admin@12345'),
            'full_name' => 'System Administrator',
            'is_admin'  => true,
            'is_active' => true,
        ]);

        $this->command->info('✅ Admin berhasil dibuat!');
        $this->command->table(
            ['Field', 'Value'],
            [
                ['Email',    $admin->email],
                ['Password', 'Admin@12345'],
                ['Username', $admin->username],
                ['URL',      url('/admin/login')],
            ]
        );
        $this->command->warn('⚠️  Segera ganti password setelah login pertama!');
    }
}
