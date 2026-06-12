<?php

namespace App\Livewire\Public;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SpotAvailability;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Cart extends Component
{
    public array $items = [];
    public float $total = 0;

    public function mount(): void
    {
        $this->loadCart();
    }

    private function loadCart(): void
    {
        $this->items = session()->get('cart', []);
        $this->total = array_sum(array_column($this->items, 'total'));
    }

    public function remove(string $key): void
    {
        $cart = session()->get('cart', []);
        unset($cart[$key]);
        session()->put('cart', $cart);
        $this->loadCart();
    }

    public function checkout(): void
    {
        if (empty($this->items)) return;

        if (!Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        try {
            DB::transaction(function () {

                // Создаём заказ
                $order = Order::create([
                    'client_id'      => Auth::id(),
                    'status'         => 'pending',
                    'total'          => $this->total,
                    'commission'     => array_sum(array_column($this->items, 'commission')),
                    'commission_pct' => 10,
                ]);

                foreach ($this->items as $item) {
                    // Проверяем занятость (защита от двойного бронирования)
                    $conflict = SpotAvailability::where('spot_id', $item['spot_id'])
                        ->where(function($q) use ($item) {
                            $q->whereBetween('date_from', [$item['date_from'], $item['date_to']])
                                ->orWhereBetween('date_to', [$item['date_from'], $item['date_to']])
                                ->orWhere(function($q) use ($item) {
                                    $q->where('date_from', '<=', $item['date_from'])
                                        ->where('date_to', '>=', $item['date_to']);
                                });
                        })
                        ->lockForUpdate()
                        ->exists();

                    if ($conflict) {
                        throw new \Exception('Площадка "' . $item['spot_title'] . '" уже занята на эти даты');
                    }

                    // Создаём позицию заказа
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'spot_id'    => $item['spot_id'],
                        'date_from'  => $item['date_from'],
                        'date_to'    => $item['date_to'],
                        'price'      => $item['base_price'],
                        'commission' => $item['commission'],
                    ]);

                    // Блокируем даты
                    SpotAvailability::create([
                        'spot_id'   => $item['spot_id'],
                        'date_from' => $item['date_from'],
                        'date_to'   => $item['date_to'],
                        'status'    => 'reserved',
                    ]);
                }

                // Очищаем корзину
                session()->forget('cart');

                // Редирект на страницу заказа
                $this->redirect(route('client.orders.show', $order->id));
            });

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            $this->loadCart();
        }
    }

    public function render()
    {
        return view('public.cart');
    }
}
