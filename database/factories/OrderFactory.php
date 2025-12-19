<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $statuses = ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'];

        $subtotal = $this->faker->numberBetween(2000, 50000);
        $shipping = $this->faker->numberBetween(0, 2000);

        return [
            'user_id' => User::inRandomOrder()->value('id'),

            'order_no' => 'BRIF-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),

            'customer_name'  => $this->faker->name(),
            'customer_phone' => $this->faker->phoneNumber(),

            'address_line1' => $this->faker->streetAddress(),
            'address_line2' => $this->faker->optional()->secondaryAddress(),
            'city'          => $this->faker->city(),
            'state'         => $this->faker->state(),
            'postcode'      => $this->faker->postcode(),

            'subtotal' => $subtotal,
            'shipping_fee' => $shipping,
            'total'    => $subtotal + $shipping,

            'status' => $this->faker->randomElement($statuses),

            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }

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
}
