<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        // 随机生成 label + value
        $label = $this->faker->randomElement(['Color', 'Size', 'Material']);
        $value = match ($label) {
            'Color' => $this->faker->randomElement(['Red', 'Blue', 'Green', 'Pink', 'Black']),
            'Size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
            default => $this->faker->word(),
        };

        return [
            'product_id' => Product::inRandomOrder()->value('id'),
            'sku'        => strtoupper($label[0] . $value) . '-' . $this->faker->numberBetween(100, 999),
            'options'    => [
                'label' => $label,
                'value' => $value,
            ],
            'price'      => $this->faker->randomFloat(2, 10, 300),
            'stock'      => $this->faker->numberBetween(5, 50),
            'image'      => null,
            'is_active'  => 1,
        ];
    }
}
