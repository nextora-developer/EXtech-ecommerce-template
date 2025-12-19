<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);

        // 随机找一个 category，没有就先留 null（Seeder 里会补）
        $categoryId = Category::inRandomOrder()->value('id');

        return [
            'category_id'  => $categoryId,
            'name'         => ucfirst($name),
            'slug'         => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(100, 999),
            'description'  => $this->faker->paragraph(),
            'price'        => $this->faker->randomFloat(2, 10, 300), // RM 10 - 300
            'stock'        => $this->faker->numberBetween(0, 200),
            'has_variants' => false, // Seeder 里会再改
            'is_active'    => true,
            'image'        => '/storage/products/placeholder.jpg', // 你可以自己换路径
        ];
    }
}
