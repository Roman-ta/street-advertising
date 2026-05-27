<?php

namespace App\Livewire\Partner;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Spot;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $spotIds = Spot::where('partner_id', Auth::id())->pluck('id');

        $stats = [
            'total_spots'   => $spotIds->count(),
            'active_spots'  => Spot::where('partner_id', Auth::id())->where('status', 'active')->count(),
            'new_orders'    => OrderItem::whereIn('spot_id', $spotIds)
                ->whereHas('order', fn($q) => $q->where('status', 'paid_pending'))
                ->count(),
            'total_earned'  => OrderItem::whereIn('spot_id', $spotIds)
                ->whereHas('order', fn($q) => $q->whereIn('status', ['active', 'completed']))
                ->sum('price'),
        ];

        $recent_orders = OrderItem::whereIn('spot_id', $spotIds)
            ->with(['order.client', 'spot.mainPhoto'])
            ->latest()
            ->take(5)
            ->get();

        return view('partner.dashboard', compact('stats', 'recent_orders'));
    }
}
