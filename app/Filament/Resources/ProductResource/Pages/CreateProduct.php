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
        // Сохраняем slug без ID
        $productId = Product::latest()->first()->id + 1;

        $data['slug'] = $data['slug'] . '-' . $productId;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
