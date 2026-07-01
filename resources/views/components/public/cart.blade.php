<div class="cart">

    <h2 class="cart__title">{{ __('messages.cart.title') }}</h2>

    @if(session('error'))
        <div class="alert alert--error">{{ session('error') }}</div>
    @endif

    @if(empty($items))
        <div class="cart__empty">
            <p>{{ __('messages.cart.empty') }}</p>
            <a href="{{ route('home') }}">{{ __('messages.cart.back_to_catalog') }}</a>
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
                                ({{ $item['days'] }} {{ __('messages.cart.days_short') }})
                            </div>
                        </div>
                        <div class="cart__item-right">
                            <div class="cart__item-price">{{ money($item['total'], 2) }}</div>
                            <button wire:click="remove('{{ $key }}')" class="cart__remove">
                                {{ __('messages.cart.remove') }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cart__summary">
                <h3 class="cart__summary-title">{{ __('messages.cart.summary_title') }}</h3>
                <div class="cart__summary-row">
                    <span>{{ __('messages.cart.spots_count') }}</span>
                    <span>{{ count($items) }}</span>
                </div>
                <div class="cart__summary-total">
                    <span>{{ __('messages.cart.to_pay') }}</span>
                    <span>{{ money($total, 2) }}</span>
                </div>
                <button wire:click="checkout" class="btn btn--primary btn--full btn--lg">
                    <span wire:loading.remove>{{ __('messages.cart.checkout_btn') }}</span>
                    <span wire:loading>{{ __('messages.cart.processing') }}</span>
                </button>
                <a href="{{ $backUrl }}" class="cart__continue cart__continue--prominent">
                    {{ __('messages.cart.continue') }}
                </a>
            </div>

        </div>
    @endif
</div>
