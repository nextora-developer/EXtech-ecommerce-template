<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $statuses = ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'];

        $shipping = $this->faker->randomFloat(2, 0, 15); // RM 0 - RM 15

        return [
            // 如果没有 user 会自动用 factory 创建
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory(),

            'order_no' => 'BRIF-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),

            'customer_name'  => $this->faker->name(),
            'customer_phone' => $this->faker->phoneNumber(),

            'address_line1' => $this->faker->streetAddress(),
            'address_line2' => $this->faker->optional()->secondaryAddress(),
            'city'          => $this->faker->city(),
            'state'         => $this->faker->state(),
            'postcode'      => $this->faker->postcode(),

            // 先给 0，等 afterCreating 里根据 order_items 算
            'subtotal'     => 0,
            'shipping_fee' => $shipping,
            'total'        => 0,

            'status'     => $this->faker->randomElement($statuses),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }

    // 状态 scope 保留
    public function pending()
    {
        return $this->state(fn() => ['status' => 'pending']);
    }

    public function paid()
    {
        return $this->state(fn() => ['status' => 'paid']);
    }

    public function completed()
    {
        return $this->state(fn() => ['status' => 'completed']);
    }

    /**
     * 这里自动帮每个订单生成 order_items
     */
    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            // 拿一些产品出来
            $products = Product::where('is_active', true)->inRandomOrder()->take(rand(1, 4))->get();

            if ($products->isEmpty()) {
                // 没有产品就不生成 items，直接跳过
                return;
            }

            $subtotal = 0;

            foreach ($products as $product) {
                $qty = rand(1, 3);

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'qty'          => $qty,
                    'unit_price'   => $product->price,
                ]);

                $subtotal += $qty * $product->price;
            }

            // 用 item 的金额更新订单
            $order->update([
                'subtotal' => $subtotal,
                'total'    => $subtotal + $order->shipping_fee,
            ]);
        });
    }
}
