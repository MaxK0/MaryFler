<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $title = 'Отзывы';

    protected static ?string $modelLabel = 'отзыв';

    protected static ?string $pluralModelLabel = 'отзывы';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Пользователь')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('rating')
                    ->label('Оценка')
                    ->options([
                        '1' => '1 звезда',
                        '2' => '2 звезды',
                        '3' => '3 звезды',
                        '4' => '4 звезды',
                        '5' => '5 звезд',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('comment')
                    ->label('Комментарий')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('images')
                    ->label('Изображения')
                    ->multiple()
                    ->image()
                    ->directory('reviews')
                    ->reorderable(),
                Forms\Components\Toggle::make('is_approved')
                    ->label('Одобрен')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Оценка')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Комментарий')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Одобрен')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_approved')
                    ->label('Статус одобрения')
                    ->options([
                        '1' => 'Одобрен',
                        '0' => 'Не одобрен',
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
