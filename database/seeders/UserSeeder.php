<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Loop untuk generate 200 user
        for ($i = 0; $i < 200; $i++) {
            User::create([
                // Username unik + realistis
                'username' => $faker->unique()->userName(),

                // Email unik
                'email' => $faker->unique()->safeEmail(),

                // Password random (8 karakter) + di-hash
                'password' => Hash::make(Str::random(8)),

                // Nama lengkap realistis
                'full_name' => $faker->name(),

                // 90% aktif, 10% nonaktif (lebih realistis)
                'is_active' => $faker->boolean(90),
            ]);
        }
    }
}