<div class="partner-page">

    {{-- Приветствие --}}
    <div class="partner-welcome">
        <div class="partner-welcome__info">
            <div class="partner-welcome__avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="partner-welcome__name">{{ auth()->user()->name }}</div>
                <div class="partner-welcome__role">Партнёр · Владелец площадок</div>
            </div>
        </div>
        <a href="{{ route('partner.spots.create') }}" class="btn btn--primary">
            + Добавить площадку
        </a>
    </div>

    {{-- Навигация --}}
    <nav class="partner-nav">
        <a href="{{ route('partner.dashboard') }}" class="partner-nav__item partner-nav__item--active">
            Обзор
        </a>
        <a href="{{ route('partner.spots') }}" class="partner-nav__item">
            Мои площадки
        </a>
        <a href="{{ route('partner.orders') }}" class="partner-nav__item">
            Заказы
        </a>
    </nav>

    {{-- Статистика --}}
    <div class="client-dashboard__stats" style="margin-bottom:32px">
        <div class="stat-card">
            <div class="stat-card__value">{{ $stats['total_spots'] }}</div>
            <div class="stat-card__label">Всего площадок</div>
        </div>
        <div class="stat-card stat-card--active">
            <div class="stat-card__value">{{ $stats['active_spots'] }}</div>
            <div class="stat-card__label">Активных</div>
        </div>
        <div class="stat-card stat-card--pending">
            <div class="stat-card__value">{{ $stats['new_orders'] }}</div>
            <div class="stat-card__label">Новых заказов</div>
        </div>
        <div class="stat-card stat-card--money">
            <div class="stat-card__value">${{ number_format($stats['total_earned'], 0) }}</div>
            <div class="stat-card__label">Заработано</div>
        </div>
    </div>

    {{-- Последние заказы --}}
    <h2 style="font-size:18px; font-weight:700; margin-bottom:16px">Последние заказы</h2>

    @if($recent_orders->isEmpty())
        <div class="spot-list__empty">
            <p>Заказов пока нет</p>
            <p style="font-size:14px; margin-top:8px">Добавьте площадку и дождитесь первого клиента</p>
        </div>
    @else
        <div class="spot-list">
            @foreach($recent_orders as $item)
                <div class="spot-row">
                    <div class="spot-row__photo">
                        @if($item->spot->mainPhoto)
                            <img src="{{ Storage::url($item->spot->mainPhoto->path) }}" alt="">
                        @else
                            <div class="spot-row__photo-empty">Нет фото</div>
                        @endif
                    </div>
                    <div class="spot-row__info">
                        <div class="spot-row__title">{{ $item->spot->title }}</div>
                        <div class="spot-row__meta">
                            Клиент: {{ $item->order->client->name }} ·
                            {{ \Carbon\Carbon::parse($item->date_from)->format('d.m.Y') }}
                            — {{ \Carbon\Carbon::parse($item->date_to)->format('d.m.Y') }}
                        </div>
                        <span class="order-status order-status--{{ $item->order->status }}">
                            {{ match($item->order->status) {
                                'pending'         => 'Ожидает оплаты',
                                'paid_pending'    => 'Оплачен — ждёт материалов',
                                'materials_ready' => 'Материалы готовы',
                                'active'          => 'Активен',
                                'completed'       => 'Завершён',
                                'cancelled'       => 'Отменён',
                                default           => $item->order->status,
                            } }}
                        </span>
                    </div>
                    <div style="text-align:right; flex-shrink:0">
                        <div style="font-size:18px; font-weight:700; color:#5B21B6">
                            ${{ number_format($item->price * 0.9, 2) }}
                        </div>
                        <div style="font-size:12px; color:#9ca3af">ваша доля (90%)</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
