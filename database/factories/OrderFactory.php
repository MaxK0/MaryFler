<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalPrice = fake()->randomFloat(2, 500, 5000);

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'status' => fake()->randomElement(OrderStatus::cases())->value,
            'total_price' => $totalPrice,
            'delivery_type' => fake()->randomElement(['pickup', 'delivery']),
            'delivery_address' => fake()->address(),
            'estimated_completion' => fake()->dateTimeBetween('now', '+1 week'),
        ];
    }
}
