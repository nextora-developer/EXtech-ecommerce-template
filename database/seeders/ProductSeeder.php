<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Classic Cotton T-Shirt',
                'category_id' => 1,
                'price' => 39.90,
                'stock' => 30,
            ],
            [
                'name' => 'Premium Hoodie Jacket',
                'category_id' => 1,
                'price' => 119.00,
                'stock' => 15,
            ],
            [
                'name' => 'Leather Wallet',
                'category_id' => 2,
                'price' => 89.00,
                'stock' => 20,
            ],
            [
                'name' => 'Stylish Sports Bottle',
                'category_id' => 2,
                'price' => 29.90,
                'stock' => 40,
            ],
            [
                'name' => 'Minimalist Backpack',
                'category_id' => 3,
                'price' => 139.00,
                'stock' => 12,
            ],
            [
                'name' => 'Wireless Earbuds',
                'category_id' => 3,
                'price' => 159.00,
                'stock' => 18,
            ],
            [
                'name' => 'USB-C Fast Charger',
                'category_id' => 4,
                'price' => 49.90,
                'stock' => 25,
            ],
            [
                'name' => 'Bluetooth Portable Speaker',
                'category_id' => 4,
                'price' => 189.00,
                'stock' => 10,
            ],
            [
                'name' => 'Office Table Lamp',
                'category_id' => 5,
                'price' => 79.00,
                'stock' => 16,
            ],
            [
                'name' => 'Smart Digital Clock',
                'category_id' => 5,
                'price' => 99.00,
                'stock' => 14,
            ],
        ];

        foreach ($products as $item) {

            $slug = Str::slug($item['name']);

            Product::updateOrCreate(
                ['slug' => $slug], // 唯一条件
                [
                    'category_id'       => $item['category_id'],
                    'name'              => $item['name'],
                    'slug'              => $slug,
                    'short_description' => 'High-quality product with elegant design.',
                    'description'       => 'This is a premium product designed for daily lifestyle use.',
                    'price'             => $item['price'],
                    'stock'             => $item['stock'],
                    'has_variants'      => false,
                    'is_active'         => true,
                    'is_digital'        => false,
                    'image'             => null,
                ]
            );
        }
    }
}
