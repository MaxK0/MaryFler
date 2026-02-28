<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Сохраняем slug без ID
        $categoryId = Category::latest()->first()->id + 1;

        $data['slug'] = $data['slug'] . '-' . $categoryId;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
