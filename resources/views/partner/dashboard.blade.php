<div class="partner-page">

    {{-- Приветствие --}}
    <div class="partner-welcome">
        <div class="partner-welcome__info">
            <div class="partner-welcome__avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="partner-welcome__name">{{ auth()->user()->name }}</div>
                <div class="partner-welcome__role">{{ __('messages.partner.role') }}</div>
            </div>
        </div>
        <a href="{{ route('partner.spots.create') }}" class="btn btn--primary">
            + {{ __('messages.partner.add_spot') }}
        </a>
    </div>

    {{-- Навигация --}}
    <nav class="partner-nav">
        <a href="{{ route('partner.dashboard') }}" class="partner-nav__item partner-nav__item--active">
            {{ __('messages.partner.nav_overview') }}
        </a>
        <a href="{{ route('partner.spots') }}" class="partner-nav__item">
            {{ __('messages.partner.nav_spots') }}
        </a>
        <a href="{{ route('partner.orders') }}" class="partner-nav__item">
            {{ __('messages.partner.nav_orders') }}
        </a>
    </nav>

    {{-- Статистика --}}
    <div class="client-dashboard__stats" style="margin-bottom:32px">
        <div class="stat-card">
            <div class="stat-card__value">{{ $stats['total_spots'] }}</div>
            <div class="stat-card__label">{{ __('messages.partner.stat_total_spots') }}</div>
        </div>
        <div class="stat-card stat-card--active">
            <div class="stat-card__value">{{ $stats['active_spots'] }}</div>
            <div class="stat-card__label">{{ __('messages.partner.stat_active') }}</div>
        </div>
        <div class="stat-card stat-card--pending">
            <div class="stat-card__value">{{ $stats['new_orders'] }}</div>
            <div class="stat-card__label">{{ __('messages.partner.stat_new_orders') }}</div>
        </div>
        <div class="stat-card stat-card--money">
            <div class="stat-card__value">${{ number_format($stats['total_earned'], 0) }}</div>
            <div class="stat-card__label">{{ __('messages.partner.stat_earned') }}</div>
        </div>
    </div>

    {{-- Последние заказы --}}
    <h2 style="font-size:18px; font-weight:700; margin-bottom:16px">{{ __('messages.partner.recent_orders') }}</h2>

    @if($recent_orders->isEmpty())
        <div class="spot-list__empty">
            <p>{{ __('messages.partner.no_orders') }}</p>
            <p style="font-size:14px; margin-top:8px">{{ __('messages.partner.no_orders_hint') }}</p>
        </div>
    @else
        <div class="spot-list">
            @foreach($recent_orders as $item)
                <div class="spot-row">
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
                </div>
            @endforeach
        </div>
    @endif

</div>
