<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'), // ✅ 一定要 hash
            'is_admin' => true,
        ]);

        // Normal customer
        User::factory()->create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => Hash::make('password'), // ✅ 一定要 hash
            'is_admin' => false,
        ]);

        $this->call([
            CategorySeeder::class,
        ]);

        $this->call([
            OrderSeeder::class,
        ]);
    }
}
