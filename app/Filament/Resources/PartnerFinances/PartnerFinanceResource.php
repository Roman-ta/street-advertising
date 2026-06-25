<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PartnerFinanceResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Выплаты партнёрам';
    protected static ?string $modelLabel = 'Партнёр';
    protected static ?string $pluralModelLabel = 'Выплаты партнёрам';
    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'partner');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Партнёр')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Телефон'),
                Tables\Columns\TextColumn::make('spots_count')
                    ->label('Площадок')
                    ->getStateUsing(fn (User $record) => $record->spots()->count()),
                Tables\Columns\TextColumn::make('pending_amount')
                    ->label('К выплате')
                    ->getStateUsing(function (User $record) {
                        return \App\Models\OrderItem::whereIn('spot_id', $record->spots()->pluck('id'))
                            ->whereHas('order', fn($q) => $q->whereIn('status', ['active', 'completed']))
                            ->whereDoesntHave('payout')
                            ->sum('price');
                    })
                    ->money('USD')
                    ->color('warning')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('total_paid')
                    ->label('Всего выплачено')
                    ->getStateUsing(function (User $record) {
                        return \App\Models\Payout::where('partner_id', $record->id)
                            ->where('status', 'paid')
                            ->sum('amount');
                    })
                    ->money('USD')
                    ->color('success'),
            ])
            ->actions([
                Tables\Actions\Action::make('view_orders')
                    ->label('Заказы')
                    ->icon('heroicon-o-eye')
                    ->url(fn (User $record) => route('filament.admin.resources.spots.index', ['tableFilters[partner_id][value]' => $record->id])),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerFinances::route('/'),
        ];
    }
}
