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
        $adjectives = ['Нежный', 'Яркий', 'Пышный', 'Романтичный', 'Осенний', 'Весенний', 'Классический', 'Экзотический', 'Страстный', 'Изящный'];
        $nouns = ['букет', 'набор', 'композиция', 'подарок', 'сюрприз', 'цветок', 'ансамбль'];
        $flowers = ['из роз', 'с пионами', 'из тюльпанов', 'с орхидеями', 'из хризантем', 'с лилиями', 'из ромашек', 'с гортензиями'];

        $name = fake()->randomElement($adjectives) . ' ' . fake()->randomElement($nouns) . ' ' . fake()->randomElement($flowers);

        return [
            'name' => $name,
            'slug' => str()->slug($name),
            'description' => fake()->realText(300),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'is_active' => true,
        ];
    }
}
