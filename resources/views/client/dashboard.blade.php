<div class="client-dashboard">

    {{-- Приветствие --}}
    <div class="partner-welcome">
        <div class="partner-welcome__info">
            <div class="partner-welcome__avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="partner-welcome__name">{{ auth()->user()->name }}</div>
                <div class="partner-welcome__role">Рекламодатель</div>
            </div>
        </div>
        <a href="{{ route('home') }}" class="btn btn--primary">
            + Найти площадку
        </a>
    </div>

    {{-- Навигация --}}
    <nav class="partner-nav">
        <a href="{{ route('client.dashboard') }}" class="partner-nav__item partner-nav__item--active">
            Обзор
        </a>
        <a href="{{ route('client.orders') }}" class="partner-nav__item">
            Мои заказы
        </a>
    </nav>

    {{-- Статистика --}}
    <div class="client-dashboard__stats">
        <div class="stat-card">
            <div class="stat-card__value">{{ $stats['total_orders'] }}</div>
            <div class="stat-card__label">Всего заказов</div>
        </div>
        <div class="stat-card stat-card--active">
            <div class="stat-card__value">{{ $stats['active_orders'] }}</div>
            <div class="stat-card__label">Активных</div>
        </div>
        <div class="stat-card stat-card--pending">
            <div class="stat-card__value">{{ $stats['pending_orders'] }}</div>
            <div class="stat-card__label">Ожидают оплаты</div>
        </div>
        <div class="stat-card stat-card--money">
            <div class="stat-card__value">${{ number_format($stats['total_spent'], 0) }}</div>
            <div class="stat-card__label">Потрачено</div>
        </div>
    </div>

    {{-- Последние заказы --}}
    <div class="client-dashboard__section">
        <div class="client-dashboard__section-header">
            <h2>Последние заказы</h2>
            <a href="{{ route('client.orders') }}" class="client-dashboard__see-all">
                Все заказы →
            </a>
        </div>

        @if($recent_orders->isEmpty())
            <div class="client-dashboard__empty">
                <p style="font-size:48px; margin-bottom:16px">📋</p>
                <p>У вас пока нет заказов</p>
                <a href="{{ route('home') }}" class="btn btn--primary" style="margin-top:16px">
                    Найти площадку
                </a>
            </div>
        @else
            <div class="order-cards">
                @foreach($recent_orders as $order)
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
        @endif
    </div>
</div>
