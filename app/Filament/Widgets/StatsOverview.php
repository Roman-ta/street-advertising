<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Spot;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = OrderItem::whereHas('order', fn($q) => $q->whereIn('status', ['active', 'completed']))
            ->sum('commission');

        $pendingPayouts = OrderItem::whereHas('order', fn($q) => $q->whereIn('status', ['active', 'completed']))
            ->whereDoesntHave('payout')
            ->sum('price');

        return [
            Stat::make('Площадок на модерации', Spot::where('status', 'moderation')->count())
                ->description('Ждут проверки')
                ->color('warning'),

            Stat::make('Активных площадок', Spot::where('status', 'active')->count())
                ->description('Доступны для бронирования')
                ->color('success'),

            Stat::make('Пользователей', User::count())
                ->description('Партнёров: ' . User::where('role', 'partner')->count() . ' · Клиентов: ' . User::where('role', 'client')->count())
                ->color('primary'),

            Stat::make('Заработок платформы', '$' . number_format($totalRevenue, 2))
                ->description('Комиссия 10% с активных заказов')
                ->color('success'),

            Stat::make('Должны партнёрам', '$' . number_format($pendingPayouts, 2))
                ->description('Ожидают выплаты')
                ->color('danger'),
        ];
    }
}
