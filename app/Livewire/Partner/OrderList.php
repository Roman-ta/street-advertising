<?php

namespace App\Livewire\Partner;

use App\Models\OrderItem;
use App\Models\Spot;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    public string $status = '';

    public function updatingStatus(): void { $this->resetPage(); }

    public function render()
    {
        $spotIds = Spot::where('partner_id', Auth::id())->pluck('id');

        $orders = OrderItem::whereIn('spot_id', $spotIds)
            ->with(['order.client', 'spot.mainPhoto'])
            ->when($this->status, fn($q) => $q->whereHas('order', fn($q) => $q->where('status', $this->status)))
            ->latest()
            ->paginate(10);

        return view('partner.order-list', compact('orders'));
    }
}
