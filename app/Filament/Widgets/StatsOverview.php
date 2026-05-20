<?php

namespace App\Filament\Widgets;

use App\Models\Spot;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Площадок на модерации',
                Spot::where('status', 'moderation')->count()
            )
                ->description('Ждут проверки')
                ->color('warning'),

            Stat::make(
                'Активных площадок',
                Spot::where('status', 'active')->count()
            )
                ->description('Доступны для бронирования')
                ->color('success'),

            Stat::make(
                'Пользователей',
                User::count()
            )
                ->description(
                    'Партнёров: ' . User::where('role', 'partner')->count() .
                    ' · Клиентов: ' . User::where('role', 'client')->count()
                )
                ->color('primary'),
        ];
    }
}
