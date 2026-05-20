<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpotResource\Pages;
use App\Models\Spot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SpotResource extends Resource
{
    protected static ?string $model = Spot::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Площадки';
    protected static ?string $modelLabel = 'Площадка';
    protected static ?string $pluralModelLabel = 'Площадки';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Основная информация')->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Название')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Тип')
                    ->options([
                        'billboard'  => 'Билборд',
                        'lightbox'   => 'Лайтбокс',
                        'led_screen' => 'LED экран',
                        'banner'     => 'Баннер',
                        'transport'  => 'Транспорт',
                        'indoor'     => 'Внутри помещений',
                        'digital'    => 'Digital',
                        'event'      => 'Event',
                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Статус')
                    ->options([
                        'draft'      => 'Черновик',
                        'moderation' => 'На модерации',
                        'active'     => 'Активна',
                        'blocked'    => 'Заблокирована',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('address')
                    ->label('Адрес')
                    ->required(),
                Forms\Components\TextInput::make('price_month')
                    ->label('Цена/месяц ($)')
                    ->numeric()
                    ->required(),
            ])->columns(2),

            Forms\Components\Section::make('Модерация')->schema([
                Forms\Components\Textarea::make('moderation_comment')
                    ->label('Комментарий модератора')
                    ->placeholder('Причина отклонения или замечания...')
                    ->rows(3),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('mainPhoto.path')
                    ->label('Фото')
                    ->disk('public')
                    ->width(80)
                    ->height(60),
                Tables\Columns\TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('partner.name')
                    ->label('Партнёр')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Тип')
                    ->badge(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Адрес')
                    ->limit(25),
                Tables\Columns\TextColumn::make('price_month')
                    ->label('Цена')
                    ->money('USD'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Статус')
                    ->colors([
                        'warning' => 'moderation',
                        'success' => 'active',
                        'danger'  => 'blocked',
                        'gray'    => 'draft',
                    ])
                    ->formatStateUsing(fn($state) => match($state) {
                        'moderation' => 'На модерации',
                        'active'     => 'Активна',
                        'blocked'    => 'Заблокирована',
                        'draft'      => 'Черновик',
                        default      => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'moderation' => 'На модерации',
                        'active'     => 'Активна',
                        'blocked'    => 'Заблокирована',
                        'draft'      => 'Черновик',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->label('Тип')
                    ->options([
                        'billboard'  => 'Билборд',
                        'lightbox'   => 'Лайтбокс',
                        'led_screen' => 'LED экран',
                    ]),
            ])
            ->actions([
                // Быстрое одобрение
                Tables\Actions\Action::make('approve')
                    ->label('Одобрить')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(Spot $record) => $record->status === 'moderation')
                    ->action(fn(Spot $record) => $record->update(['status' => 'active']))
                    ->requiresConfirmation(),

                // Быстрое отклонение
                Tables\Actions\Action::make('block')
                    ->label('Отклонить')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(Spot $record) => $record->status === 'moderation')
                    ->action(fn(Spot $record) => $record->update(['status' => 'blocked']))
                    ->requiresConfirmation(),

                Tables\Actions\EditAction::make()->label('Изменить'),
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(25);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpots::route('/'),
            'edit'  => Pages\EditSpot::route('/{record}/edit'),
        ];
    }

    // Показываем только площадки на модерации по умолчанию
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['partner', 'mainPhoto']);
    }
}
