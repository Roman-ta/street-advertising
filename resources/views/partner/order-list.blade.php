<div>
    <div class="partner-page">

        <div class="partner-header">
            <h2>Все заказы</h2>
            <a href="{{ route('partner.dashboard') }}" class="btn btn--outline">← Назад</a>
        </div>

        <div class="order-list__filters" style="margin-bottom:24px">
            @foreach([
                ''                => 'Все',
                'paid_pending'    => 'Новые',
                'materials_ready' => 'Материалы готовы',
                'active'          => 'Активные',
                'completed'       => 'Завершённые',
            ] as $value => $label)
                <button
                    wire:click="$set('status', '{{ $value }}')"
                    class="order-list__filter {{ $status === $value ? 'order-list__filter--active' : '' }}"
                >{{ $label }}</button>
            @endforeach
        </div>

        @if($orders->isEmpty())
            <div class="spot-list__empty">
                <p>Заказов не найдено</p>
            </div>
        @else
            <div class="spot-list">
                @foreach($orders as $item)
                    <a href="{{ route('partner.orders.show', $item->id) }}" class="spot-row" style="text-decoration:none; color:inherit; display:flex; align-items:center">
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
                    </a>
                @endforeach
            </div>

            <div style="margin-top:24px">
                {{ $orders->links() }}
            </div>
        @endif

    </div>
</div>
