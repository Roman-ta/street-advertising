<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Models\OrderItem;
use App\Models\Payout;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PartnerFinanceResource\Pages;

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
                Tables\Columns\TextColumn::make('spots_count')
                    ->label('Площадок')
                    ->getStateUsing(fn (User $record) => $record->spots()->count()),
                Tables\Columns\TextColumn::make('pending_amount')
                    ->label('К выплате')
                    ->getStateUsing(function (User $record) {
                        return money(OrderItem::whereIn('spot_id', $record->spots()->pluck('id'))
                            ->whereHas('order', fn($q) => $q->whereIn('status', ['active', 'completed']))
                            ->whereDoesntHave('payout')
                            ->sum('price'), 2);
                    })
                    ->color('warning'),
                Tables\Columns\TextColumn::make('total_paid')
                    ->label('Всего выплачено')
                    ->getStateUsing(function (User $record) {
                        return money(Payout::where('partner_id', $record->id)
                            ->where('status', 'paid')
                            ->sum('amount'), 2);
                    })
                    ->color('success'),
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
