<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'product_id' => Product::inRandomOrder()->first()->id ?? Product::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->paragraphs(2, true),
            'images' => [fake()->imageUrl()],
            'is_approved' => fake()->boolean(80), // 80% шанс одобрения
        ];
    }
}
