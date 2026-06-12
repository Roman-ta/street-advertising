<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Пользователи';
    protected static ?string $modelLabel = 'Пользователь';
    protected static ?string $pluralModelLabel = 'Пользователи';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')->label('Имя')->required(),
            Forms\Components\TextInput::make('email')->label('Email')->email()->required(),
            Forms\Components\Select::make('role')
                ->label('Роль')
                ->options([
                    'client'  => 'Клиент',
                    'partner' => 'Партнёр',
                    'admin'   => 'Админ',
                ])
                ->required(),
            Forms\Components\Toggle::make('is_active')->label('Активен'),
            Forms\Components\Toggle::make('legal_signed')->label('Оферта принята'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Имя')->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Роль')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'admin'   => 'primary',
                        'partner' => 'success',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'admin'   => 'Админ',
                        'partner' => 'Партнёр',
                        'client'  => 'Клиент',
                        default   => $state,
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активен')->boolean(),
                Tables\Columns\IconColumn::make('legal_signed')
                    ->label('Оферта')->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Регистрация')
                    ->dateTime('d.m.Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Роль')
                    ->options([
                        'client'  => 'Клиент',
                        'partner' => 'Партнёр',
                        'admin'   => 'Админ',
                    ]),
            ])
            ->recordActions([
                Action::make('block')
                    ->label('Заблокировать')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->visible(fn(User $record) => $record->is_active)
                    ->action(fn(User $record) => $record->update(['is_active' => false]))
                    ->requiresConfirmation(),
                Action::make('unblock')
                    ->label('Разблокировать')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(User $record) => !$record->is_active)
                    ->action(fn(User $record) => $record->update(['is_active' => true])),
                EditAction::make()->label('Изменить'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit'  => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
