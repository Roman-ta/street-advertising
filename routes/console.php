<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('orders:complete-expired')->everyMinute();
Schedule::call(function () {
    \App\Models\Order::where('status', 'active')
        ->with(['items.spot', 'client'])
        ->get()
        ->each(function ($order) {
            $item = $order->items->first();
            if (!$item) return;

            $daysLeft = \Carbon\Carbon::today()->diffInDays(
                \Carbon\Carbon::parse($item->date_to), false
            );

            // Отправляем за 3 дня и в последний день
            if ($daysLeft === 3 || $daysLeft === 1) {
                \App\Jobs\SendExtensionReminderEmail::dispatch($order);
            }
        });
})->dailyAt('10:00'); // каждый день в 10 утра
