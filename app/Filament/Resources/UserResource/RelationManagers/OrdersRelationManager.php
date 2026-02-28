<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\OrderStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $title = 'Заказы';

    protected static ?string $modelLabel = 'заказ';

    protected static ?string $pluralModelLabel = 'заказы';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label('Статус')
                    ->options([
                        OrderStatus::NEW->value => OrderStatus::NEW->value,
                        OrderStatus::IN_PROGRESS->value => OrderStatus::IN_PROGRESS->value,
                        OrderStatus::READY->value => OrderStatus::READY->value,
                        OrderStatus::COMPLETED->value => OrderStatus::COMPLETED->value,
                        OrderStatus::CANCELLED->value => OrderStatus::CANCELLED->value,
                    ])
                    ->required(),
                Forms\Components\TextInput::make('total_price')
                    ->label('Общая стоимость')
                    ->required()
                    ->numeric()
                    ->prefix('₽'),
                Forms\Components\Select::make('delivery_type')
                    ->label('Способ получения')
                    ->options([
                        'pickup' => 'Самовывоз',
                        'delivery' => 'Доставка',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('delivery_address')
                    ->label('Адрес доставки')
                    ->maxLength(65535),
                Forms\Components\DateTimePicker::make('estimated_completion')
                    ->label('Ожидаемое время готовности')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        OrderStatus::NEW->value => 'warning',
                        OrderStatus::IN_PROGRESS->value => 'info',
                        OrderStatus::READY->value => 'success',
                        OrderStatus::COMPLETED->value => 'gray',
                        OrderStatus::CANCELLED->value => 'danger',
                    }),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Общая стоимость')
                    ->money('rub'),
                Tables\Columns\TextColumn::make('delivery_type')
                    ->label('Способ получения')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pickup' => 'Самовывоз',
                        'delivery' => 'Доставка',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('estimated_completion')
                    ->label('Ожидаемое время готовности')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        OrderStatus::NEW->value => OrderStatus::NEW->value,
                        OrderStatus::IN_PROGRESS->value => OrderStatus::IN_PROGRESS->value,
                        OrderStatus::READY->value => OrderStatus::READY->value,
                        OrderStatus::COMPLETED->value => OrderStatus::COMPLETED->value,
                        OrderStatus::CANCELLED->value => OrderStatus::CANCELLED->value,
                    ]),
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
