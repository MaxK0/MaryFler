<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class VariationsRelationManager extends RelationManager
{
    protected static string $relationship = 'variations';

    protected static ?string $title = 'Вариации';

    protected static ?string $modelLabel = 'вариация';

    protected static ?string $pluralModelLabel = 'вариации';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Название')
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->label('Цена')
                    ->required()
                    ->numeric()
                    ->prefix('₽'),
                Forms\Components\TextInput::make('stock')
                    ->label('Количество на складе')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('sales_count')
                    ->label('Количество продаж')
                    ->numeric()
                    ->default(0),
                Forms\Components\FileUpload::make('images')
                    ->label('Изображения')
                    ->multiple()
                    ->image()
                    ->directory('product_variations')
                    ->reorderable(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Активна')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Цена')
                    ->money('rub'),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Количество на складе')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sales_count')
                    ->label('Количество продаж')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активна')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
