<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 25; $i++) {

            $name = $faker->words(rand(2, 4), true);

            $product = Product::create([
                'category_id'        => rand(1, 5),
                'name'               => ucfirst($name),
                'slug'               => Str::slug($name) . '-' . $i,
                'short_description'  => $faker->sentence(8),
                'description'        => $faker->paragraph(5),
                'price'              => $faker->randomFloat(2, 10, 300),
                'stock'              => $faker->numberBetween(0, 50),

                'has_variants'       => (bool) rand(0, 1),
                'is_active'          => (bool) rand(0, 1),
                'is_digital'         => (bool) rand(0, 1),   // 👈 新增

                'image'              => null,
            ]);

            // === Variants ===
            if ($product->has_variants) {

                // Create 1 option: Color
                $option = ProductOption::create([
                    'product_id' => $product->id,
                    'name'       => 'color',
                    'label'      => 'Color',
                    'sort_order' => 1,
                ]);

                // Values (red, blue, green)
                $colors = ['Red', 'Blue', 'Green'];

                foreach ($colors as $idx => $color) {
                    ProductOptionValue::create([
                        'product_option_id' => $option->id,
                        'value'             => $color,
                        'sort_order'        => $idx,
                    ]);

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku'        => strtoupper(Str::random(6)),
                        'options'    => [
                            'label' => 'Color',
                            'value' => $color,
                        ],
                        'price'      => $product->price + rand(1, 10),
                        'stock'      => rand(1, 10),
                        'image'      => null,
                        'is_active'  => true,
                    ]);
                }

                // Stock = sum
                $product->update([
                    'stock' => $product->variants()->sum('stock')
                ]);
            }
        }
    }
}
