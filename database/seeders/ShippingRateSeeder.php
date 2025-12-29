<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ShippingRate::updateOrCreate(
            ['code' => 'west_my'],
            ['name' => 'West Malaysia', 'rate' => 8.00]
        );

        \App\Models\ShippingRate::updateOrCreate(
            ['code' => 'east_my'],
            ['name' => 'East Malaysia', 'rate' => 15.00]
        );

        \App\Models\ShippingRate::updateOrCreate(
            ['code' => 'digital'],
            ['name' => 'Digital Product', 'rate' => 0.00]
        );
    }
}
