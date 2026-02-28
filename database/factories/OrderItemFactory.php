<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ProductVariation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomFloat(2, 500, 5000);
        $quantity = fake()->numberBetween(1, 5);

        return [
            'order_id' => Order::inRandomOrder()->first()->id ?? Order::factory(),
            'product_variation_id' => ProductVariation::inRandomOrder()->first()->id ?? ProductVariation::factory(),
            'quantity' => $quantity,
            'price' => $price,
        ];
    }
}
