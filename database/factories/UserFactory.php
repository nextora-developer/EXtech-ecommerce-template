<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    // 指明这个 factory 对应的模型（可写可不写，但写了更清楚）
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'is_admin'          => false,              // 你是用 is_admin boolean
            'remember_token'    => Str::random(10),
        ];
    }

    // 可选：如果以后想用 factory 生成 admin，可以用这个状态
    public function admin(): static
    {
        return $this->state(fn() => [
            'name'     => 'Admin',
            'email'    => 'admin@admin.com',
            'is_admin' => true,
            'password' => Hash::make('password'),
        ]);
    }
}
