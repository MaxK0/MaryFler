<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Review;
use App\Models\User;
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

        // Создаем администратора, если его еще нет
        if (!User::where('email', 'admin@admin.com')->exists()) {
            User::factory()->create([
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'phone' => '79999999999',
            ]);
        }

        // Создаем категории
        $categories = [
            'Букеты роз',
            'Свадебные букеты',
            'Комнатные растения',
            'Подарочные наборы',
            'Авторские букеты'
        ];

        foreach ($categories as $categoryName) {
            Category::factory()->create([
                'name' => $categoryName,
                'slug' => str()->slug($categoryName),
            ]);
        }

        // Создаем товары с единственной вариацией, копирующей данные товара
        Product::factory(20)
            ->has(
                ProductVariation::factory()
                    ->count(1)
                    ->state(function (array $attributes, Product $product) {
                        return [
                            'name' => $product->name,
                            'description' => $product->description,
                        ];
                    }),
                'variations'
            )
            ->create();

        // Создаем отзывы
        Review::factory(50)->create();

        // Создаем заказы с элементами
        Order::factory(15)
            ->hasItems(fake()->numberBetween(1, 5))
            ->create();
    }
}
