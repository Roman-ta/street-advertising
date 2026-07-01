<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendExtensionReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function handle(): void
    {
        $client = $this->order->client;
        $item   = $this->order->items->first();
        if (!$item) return;

        $daysLeft = \Carbon\Carbon::today()->diffInDays(
            \Carbon\Carbon::parse($item->date_to), false
        );

        Mail::send([], [], function($message) use ($client, $item, $daysLeft) {
            $message
                ->to($client->email, $client->name)
                ->subject("⏰ Ваша реклама заканчивается через {$daysLeft} дн. — продлите сейчас")
                ->html("
                    <h2>Реклама скоро закончится!</h2>
                    <p>Ваше размещение <strong>{$item->spot->title}</strong> заканчивается {$item->date_to}.</p>
                    <p>Осталось <strong>{$daysLeft} дней</strong>.</p>
                    <p>Если хотите продлить — нажмите кнопку ниже:</p>
                    <a href='" . route('client.orders.show', $this->order->id) . "' style='
                        display:inline-block;
                        background:#5B21B6;
                        color:white;
                        padding:12px 24px;
                        border-radius:8px;
                        text-decoration:none;
                        font-weight:600;
                    '>Продлить размещение →</a>
                ");
        });
    }
}
