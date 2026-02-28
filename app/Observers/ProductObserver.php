<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductVariation;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        // Создаем первую вариацию товара при его создании
        ProductVariation::create([
            'product_id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => 0,
            'stock' => 0,
            'sales_count' => 0,
            'is_active' => true,
        ]);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
