<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Review;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем пользователей
        User::factory(10)->create();

        // Создаем администратора
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'phone' => '79999999999',
        ]);

        // Создаем категории
        Category::factory(5)->create();

        // Создаем товары с вариациями
        Product::factory(20)
            ->has(ProductVariation::factory()->count(fake()->numberBetween(1, 3)), 'variations')
            ->create();

        // Создаем отзывы
        Review::factory(50)->create();

        // Создаем заказы с элементами
        Order::factory(15)
            ->hasItems(fake()->numberBetween(1, 5))
            ->create();
    }
}
