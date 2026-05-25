<div class="order-show">

    <div class="order-show__hero">
        <div class="order-show__hero-emoji">🎉</div>
        <h2>Заказ оформлен!</h2>
        <p>Заказ #{{ $order->id }} · {{ $order->created_at->format('d.m.Y') }}</p>
    </div>

    <div class="order-show__status">
        ⏳ Ожидает оплаты — следующий шаг: оплата заказа
    </div>

    <div class="order-show__items">
        @foreach($order->items as $item)
            <div class="order-show__item">
                <div class="order-show__item-photo">
                    @if($item->spot->mainPhoto)
                        <img
                            src="{{ Storage::url($item->spot->mainPhoto->path) }}"
                            alt="{{ $item->spot->title }}"
                        >
                    @endif
                </div>
                <div class="order-show__item-info">
                    <div class="order-show__item-title">{{ $item->spot->title }}</div>
                    <div class="order-show__item-dates">
                        📅 {{ \Carbon\Carbon::parse($item->date_from)->format('d.m.Y') }}
                        — {{ \Carbon\Carbon::parse($item->date_to)->format('d.m.Y') }}
                    </div>
                </div>
                <div class="order-show__item-price">
                    ${{ number_format($item->price + $item->commission, 2) }}
                </div>
            </div>
        @endforeach
    </div>

    <div class="order-show__total">
        <span>Итого к оплате</span>
        <span>${{ number_format($order->total, 2) }}</span>
    </div>

    <button class="btn btn--primary btn--full btn--lg">
        Перейти к оплате →
    </button>

    <p class="order-show__hint">
        Вы будете перенаправлены на страницу безопасной оплаты
    </p>

</div>
