<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing fake orders (optional)
        // Order::truncate();

        // Mixed statuses
        Order::factory()->count(10)->create();

        // Specific distributions (so dashboard looks nice)
        Order::factory()->count(5)->pending()->create();
        Order::factory()->count(3)->paid()->create();
        Order::factory()->count(2)->completed()->create();
    }
}
