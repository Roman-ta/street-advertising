<div class="client-dashboard">

    {{-- Приветствие --}}
    <div class="partner-welcome">
        <div class="partner-welcome__info">
            <div class="partner-welcome__avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="partner-welcome__name">{{ auth()->user()->name }}</div>
                <div class="partner-welcome__role">{{ __('messages.client.role') }}</div>
            </div>
        </div>
        <a href="{{ route('home') }}" class="btn btn--primary">
            + {{ __('messages.client.find_spot') }}
        </a>
    </div>

    {{-- Навигация --}}
    <nav class="partner-nav">
        <a href="{{ route('client.dashboard') }}" class="partner-nav__item partner-nav__item--active">
            {{ __('messages.client.nav_overview') }}
        </a>
        <a href="{{ route('client.orders') }}" class="partner-nav__item">
            {{ __('messages.client.nav_orders') }}
        </a>
    </nav>

    {{-- Статистика --}}
    <div class="client-dashboard__stats">
        <div class="stat-card">
            <div class="stat-card__value">{{ $stats['total_orders'] }}</div>
            <div class="stat-card__label">{{ __('messages.client.stat_total_orders') }}</div>
        </div>
        <div class="stat-card stat-card--active">
            <div class="stat-card__value">{{ $stats['active_orders'] }}</div>
            <div class="stat-card__label">{{ __('messages.client.stat_active') }}</div>
        </div>
        <div class="stat-card stat-card--pending">
            <div class="stat-card__value">{{ $stats['pending_orders'] }}</div>
            <div class="stat-card__label">{{ __('messages.client.stat_pending') }}</div>
        </div>
        <div class="stat-card stat-card--money">
            <div class="stat-card__value">{{ money($stats['total_spent'], 0) }}</div>
            <div class="stat-card__label">{{ __('messages.client.stat_spent') }}</div>
        </div>
    </div>

    {{-- Последние заказы --}}
    <div class="client-dashboard__section">
        <div class="client-dashboard__section-header">
            <h2>{{ __('messages.client.recent_orders') }}</h2>
            <a href="{{ route('client.orders') }}" class="client-dashboard__see-all">
                {{ __('messages.client.all_orders') }} →
            </a>
        </div>

        @if($recent_orders->isEmpty())
            <div class="client-dashboard__empty">
                <p style="font-size:48px; margin-bottom:16px">📋</p>
                <p>{{ __('messages.client.no_orders') }}</p>
                <a href="{{ route('home') }}" class="btn btn--primary" style="margin-top:16px">
                    {{ __('messages.client.find_spot') }}
                </a>
            </div>
        @else
            <div class="order-cards">
                @foreach($recent_orders as $order)
                    <a href="{{ route('client.orders.show', $order->id) }}" class="order-card">
                        <div class="order-card__header">
                            <span class="order-card__number">{{ __('messages.client.order_number', ['id' => $order->id]) }}</span>
                            <span class="order-status order-status--{{ $order->status }}">
                                {{ __('messages.order_status.' . $order->status) }}
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
                                <p class="order-card__more">{{ __('messages.client.more_items', ['count' => $order->items->count() - 2]) }}</p>
                            @endif
                        </div>
                        <div class="order-card__footer">
                            <span class="order-card__date">{{ $order->created_at->format('d.m.Y') }}</span>
                            <span class="order-card__total">{{ money($order->total, 2) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
