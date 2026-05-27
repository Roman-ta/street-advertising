<div class="order-list">

    <div class="order-list__header">
        <h2>Мои заказы</h2>
        <div class="order-list__filters">
            @foreach([
                ''               => 'Все',
                'pending'        => 'Ожидают оплаты',
                'active'         => 'Активные',
                'completed'      => 'Завершённые',
                'cancelled'      => 'Отменённые',
            ] as $value => $label)
                <button
                    wire:click="$set('status', '{{ $value }}')"
                    class="order-list__filter {{ $status === $value ? 'order-list__filter--active' : '' }}"
                >{{ $label }}</button>
            @endforeach
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="order-list__empty">
            <p>Заказов не найдено</p>
            <a href="{{ route('home') }}" class="btn btn--primary">Найти площадку</a>
        </div>
    @else
        <div class="order-cards">
            @foreach($orders as $order)
                <a href="{{ route('client.orders.show', $order->id) }}" class="order-card">
                    <div class="order-card__header">
                        <span class="order-card__number">Заказ #{{ $order->id }}</span>
                        <span class="order-status order-status--{{ $order->status }}">
                            {{ match($order->status) {
                                'pending'         => 'Ожидает оплаты',
                                'paid_pending'    => 'Оплачен',
                                'materials_ready' => 'Материалы загружены',
                                'active'          => 'Активен',
                                'completed'       => 'Завершён',
                                'cancelled'       => 'Отменён',
                                default           => $order->status,
                            } }}
                        </span>
                    </div>
                    <div class="order-card__items">
                        @foreach($order->items->take(2) as $item)
                            <div class="order-card__item">
                                <div class="order-card__item-photo">
                                    @if($item->spot->mainPhoto)
                                        <img src="{{ Storage::url($item->spot->mainPhoto->path) }}" alt="">
                                    @endif
                                </div>
                                <div class="order-card__item-info">
                                    <div class="order-card__item-title">{{ $item->spot->title }}</div>
                                    <div class="order-card__item-dates">
                                        {{ \Carbon\Carbon::parse($item->date_from)->format('d.m.Y') }}
                                        — {{ \Carbon\Carbon::parse($item->date_to)->format('d.m.Y') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if($order->items->count() > 2)
                            <p class="order-card__more">+ ещё {{ $order->items->count() - 2 }}</p>
                        @endif
                    </div>
                    <div class="order-card__footer">
                        <span class="order-card__date">{{ $order->created_at->format('d.m.Y') }}</span>
                        <span class="order-card__total">${{ number_format($order->total, 2) }}</span>
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top:24px">
            {{ $orders->links() }}
        </div>
    @endif
</div>
