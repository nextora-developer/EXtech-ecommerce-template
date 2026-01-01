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
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // Normal customer（不用 factory 也行）
        User::updateOrCreate(
            ['email' => 'user@user.com'],
            [
                'name'     => 'User',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            PaymentMethodSeeder::class,
            ShippingRateSeeder::class,
        ]);
    }
}
