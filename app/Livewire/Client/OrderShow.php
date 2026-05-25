<?php

namespace App\Livewire\Client;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderShow extends Component
{
    public Order $order;

    public function mount(int $id): void
    {
        $this->order = Order::where('id', $id)
            ->where('client_id', Auth::id())
            ->with(['items.spot.mainPhoto'])
            ->firstOrFail();
    }

    public function render()
    {
        return view('client.order-show');
    }
}
