<div>
    <div class="partner-page">

        <div class="partner-header">
            <h2>{{ __('messages.partner.orders_title') }}</h2>
            <a href="{{ route('partner.dashboard') }}" class="btn btn--outline">← {{ __('messages.partner.back') }}</a>
        </div>

        <div class="order-list__filters" style="margin-bottom:24px">
            @foreach([
                ''                => __('messages.partner.filter_all'),
                'paid_pending'    => __('messages.partner.filter_new'),
                'materials_ready' => __('messages.order_status.materials_ready'),
                'active'          => __('messages.partner.filter_active'),
                'completed'       => __('messages.partner.filter_completed'),
            ] as $value => $label)
                <button
                    wire:click="$set('status', '{{ $value }}')"
                    class="order-list__filter {{ $status === $value ? 'order-list__filter--active' : '' }}"
                >{{ $label }}</button>
            @endforeach
        </div>

        @if($orders->isEmpty())
            <div class="spot-list__empty">
                <p>{{ __('messages.partner.orders_not_found') }}</p>
            </div>
        @else
            <div class="spot-list">
                @foreach($orders as $item)
                    <a href="{{ route('partner.orders.show', $item->id) }}" class="spot-row" style="text-decoration:none; color:inherit; display:flex; align-items:center">
                        <div class="spot-row__photo">
                            @if($item->spot->mainPhoto)
                                <img src="{{ Storage::url($item->spot->mainPhoto->path) }}" alt="">
                            @else
                                <div class="spot-row__photo-empty">{{ __('messages.partner.no_photo') }}</div>
                            @endif
                        </div>
                        <div class="spot-row__info">
                            <div class="spot-row__title">{{ $item->spot->title }}</div>
                            <div class="spot-row__meta">
                                {{ __('messages.partner.client_label') }} {{ $item->order->client->name }} ·
                                {{ \Carbon\Carbon::parse($item->date_from)->format('d.m.Y') }}
                                — {{ \Carbon\Carbon::parse($item->date_to)->format('d.m.Y') }}
                            </div>
                            <span class="order-status order-status--{{ $item->order->status }}">
                                {{ match($item->order->status) {
                                    'pending'         => __('messages.order_status.pending'),
                                    'paid_pending'    => __('messages.order_status.paid_pending'),
                                    'materials_ready' => __('messages.order_status.materials_ready'),
                                    'active'          => __('messages.order_status.active'),
                                    'completed'       => __('messages.order_status.completed'),
                                    'cancelled'       => __('messages.order_status.cancelled'),
                                    default           => $item->order->status,
                                } }}
                            </span>
                        </div>
                        <div style="text-align:right; flex-shrink:0">
                            <div style="font-size:18px; font-weight:700; color:#5B21B6">
                                ${{ number_format($item->price * 0.9, 2) }}
                            </div>
                            <div style="font-size:12px; color:#9ca3af">{{ __('messages.partner.your_share') }}</div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div style="margin-top:24px">
                {{ $orders->links() }}
            </div>
        @endif

    </div>
</div>
