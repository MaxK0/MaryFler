<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Товары';

    protected static ?string $modelLabel = 'товар';

    protected static ?string $pluralModelLabel = 'товары';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_variation_id')
                    ->label('Вариация товара')
                    ->relationship('productVariation', 'name')
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Количество')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('price')
                    ->label('Цена')
                    ->required()
                    ->numeric()
                    ->prefix('₽'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('productVariation.product.name')
                    ->label('Товар')
                    ->searchable(),
                Tables\Columns\TextColumn::make('productVariation.name')
                    ->label('Вариация')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Количество')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Цена')
                    ->money('rub')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Сумма')
                    ->money('rub')
                    ->sortable()
                    ->getStateUsing(function (Tables\Columns\TextColumn $column, $record): float {
                        return $record->quantity * $record->price;
                    }),
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
