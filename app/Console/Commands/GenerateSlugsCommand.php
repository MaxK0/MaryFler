<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slugs:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Генерация slug для товаров и категорий';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Генерация slug для категорий
        $categories = Category::whereNull('slug')->orWhere('slug', '')->get();
        foreach ($categories as $category) {
            $category->slug = Str::slug($category->name);
            $category->save();
        }

        $this->info("Обновлено {$categories->count()} категорий.");

        // Генерация slug для товаров
        $products = Product::whereNull('slug')->orWhere('slug', '')->get();
        foreach ($products as $product) {
            $product->slug = Str::slug($product->name);
            $product->save();
        }

        $this->info("Обновлено {$products->count()} товаров.");

        return Command::SUCCESS;
    }
}
