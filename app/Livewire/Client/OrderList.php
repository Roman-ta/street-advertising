<?php

namespace App\Livewire\Client;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    public string $status = '';

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $orders = Order::where('client_id', Auth::id())
            ->with(['items.spot.partner', 'items.spot.mainPhoto'])
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->latest()
            ->paginate(10);

        return view('client.order-list', compact('orders'));
    }
}
