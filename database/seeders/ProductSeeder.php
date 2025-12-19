<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 如果没有 categories → create default
        if (Category::count() === 0) {
            $default = ['Towels', 'Hooks', 'Bathroom', 'Home & Living'];
            foreach ($default as $name) {
                Category::create([
                    'name'      => $name,
                    'slug'      => Str::slug($name),
                    'is_active' => 1,
                ]);
            }
        }

        // Create 20 products
        $products = Product::factory()
            ->count(20)
            ->create();

        foreach ($products as $product) {
            $hasVariants = (bool) random_int(0, 1);

            if ($hasVariants) {
                $product->update([
                    'has_variants' => 1,
                    'stock' => 0,
                ]);

                // 每个产品 2-4 个 variant
                $variantCount = random_int(2, 4);

                ProductVariant::factory()
                    ->count($variantCount)
                    ->create([
                        'product_id' => $product->id
                    ]);
            } else {
                $product->update([
                    'has_variants' => 0,
                    'stock' => random_int(10, 100),
                ]);
            }
        }
    }
}
