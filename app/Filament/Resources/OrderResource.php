<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Заказы';

    protected static ?string $modelLabel = 'заказ';

    protected static ?string $pluralModelLabel = 'заказы';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Пользователь')
                    ->relationship('user', 'name')
                    ->required(),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (OrderStatus $state): string => match ($state) {
                        OrderStatus::NEW => 'warning',
                        OrderStatus::IN_PROGRESS => 'info',
                        OrderStatus::READY => 'success',
                        OrderStatus::COMPLETED => 'gray',
                        OrderStatus::CANCELLED => 'danger',
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
