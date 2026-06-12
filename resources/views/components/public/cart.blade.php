<div class="cart">

    <h2 class="cart__title">Корзина</h2>

    @if(session('error'))
        <div class="alert alert--error">{{ session('error') }}</div>
    @endif

    @if(empty($items))
        <div class="cart__empty">
            <p>Корзина пуста</p>
            <a href="{{ route('home') }}">← Вернуться к каталогу</a>
        </div>
    @else
        <div class="cart__layout">

            <div class="cart__items">
                @foreach($items as $key => $item)
                    <div class="cart__item">
                        <div class="cart__item-photo">
                            @if($item['photo'])
                                <img src="{{ Storage::url($item['photo']) }}" alt="{{ $item['spot_title'] }}">
                            @endif
                        </div>
                        <div class="cart__item-info">
                            <div class="cart__item-title">{{ $item['spot_title'] }}</div>
                            <div class="cart__item-address">📍 {{ $item['address'] }}</div>
                            <div class="cart__item-dates">
                                📅 {{ \Carbon\Carbon::parse($item['date_from'])->format('d.m.Y') }}
                                — {{ \Carbon\Carbon::parse($item['date_to'])->format('d.m.Y') }}
                                ({{ $item['days'] }} дн.)
                            </div>
                        </div>
                        <div class="cart__item-right">
                            <div class="cart__item-price">${{ number_format($item['total'], 2) }}</div>
                            <button wire:click="remove('{{ $key }}')" class="cart__remove">
                                Удалить
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cart__summary">
                <h3 class="cart__summary-title">Итого</h3>
                <div class="cart__summary-row">
                    <span>Площадок</span>
                    <span>{{ count($items) }}</span>
                </div>
                <div class="cart__summary-total">
                    <span>К оплате</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>
                <button wire:click="checkout" class="btn btn--primary btn--full btn--lg">
                    <span wire:loading.remove>Оформить заказ</span>
                    <span wire:loading>Оформляем...</span>
                </button>
                <a href="{{ route('home') }}" class="cart__continue">← Продолжить выбор</a>
            </div>

        </div>
    @endif
</div>
