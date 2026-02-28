<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariation>
 */
class ProductVariationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id ?? Product::factory(),
            'name' => fake()->word(),
            'price' => fake()->randomFloat(2, 500, 5000),
            'stock' => fake()->numberBetween(0, 100),
            'sales_count' => fake()->numberBetween(0, 50),
            'description' => fake()->paragraphs(3, true),
            'images' => [fake()->imageUrl()],
            'is_active' => true,
        ];
    }
}
