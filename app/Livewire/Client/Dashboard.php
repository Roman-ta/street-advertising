<?php

namespace App\Livewire\Client;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_orders'   => Order::where('client_id', Auth::id())->count(),
            'active_orders'  => Order::where('client_id', Auth::id())->where('status', 'active')->count(),
            'pending_orders' => Order::where('client_id', Auth::id())->where('status', 'pending')->count(),
            'total_spent'    => Order::where('client_id', Auth::id())->whereIn('status', ['active','completed'])->sum('total'),
        ];

        $recent_orders = Order::where('client_id', Auth::id())
            ->with(['items.spot.mainPhoto'])
            ->latest()
            ->take(3)
            ->get();

        return view('client.dashboard', compact('stats', 'recent_orders'));
    }
}
