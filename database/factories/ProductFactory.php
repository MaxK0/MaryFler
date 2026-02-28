<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'name' => $name,
            'slug' => str()->slug($name),
            'description' => fake()->paragraphs(3, true),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'is_active' => true,
        ];
    }
}
