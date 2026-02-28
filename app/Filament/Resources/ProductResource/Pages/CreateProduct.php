<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($product = Product::latest()->first()) {
            $productId = $product->id + 1;
        } else {
            $productId = 1;
        }

        $data['slug'] = $data['slug'] . '-' . $productId;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
