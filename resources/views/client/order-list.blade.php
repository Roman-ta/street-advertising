<div class="order-list">

    <div class="order-list__header">
        <h2>{{ __('messages.client.orders_title') }}</h2>
        <div class="order-list__filters">
            @foreach([
                ''          => __('messages.client.filter_all'),
                'pending'   => __('messages.client.filter_pending'),
                'active'    => __('messages.client.filter_active'),
                'completed' => __('messages.client.filter_completed'),
                'cancelled' => __('messages.client.filter_cancelled'),
            ] as $value => $label)
                <button wire:click="$set('status', '{{ $value }}')" class="order-list__filter {{ $status === $value ? 'order-list__filter--active' : '' }}">{{ $label }}</button>
            @endforeach
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="order-list__empty">
            <p>{{ __('messages.client.orders_not_found') }}</p>
            <a href="{{ route('home') }}" class="btn btn--primary">{{ __('messages.client.find_spot') }}</a>
        </div>
    @else
        <div class="order-cards">
            @foreach($orders as $order)

                {{-- Группируем позиции заказа по партнёру --}}
                @php
                    $byPartner = $order->items->groupBy(fn($item) => $item->spot->partner_id);
                @endphp

                <div style="border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; margin-bottom:20px;">

                    {{-- Шапка заказа --}}
                    <div style="padding:16px 20px; background:#f8f8f8; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <span style="font-weight:700; font-size:15px;">{{ __('messages.client.order_number', ['id' => $order->id]) }}</span>
                            <span style="color:#9ca3af; font-size:13px; margin-left:8px;">{{ $order->created_at->format('d.m.Y') }}</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <span style="font-size:16px; font-weight:700; color:#5B21B6;">{{ money($order->total, 2) }}</span>
                            <a href="{{ route('client.orders.show', $order->id) }}" class="btn btn--outline btn--sm">Детали →</a>
                        </div>
                    </div>

                    {{-- Блоки по партнёрам --}}
                    @foreach($byPartner as $partnerId => $items)
                        @php
                            $partner = $items->first()->spot->partner;

                            // Считаем общий статус по позициям этого партнёра
                            $statuses = $items->map(function($item) {
                                $isActive  = $item->placement_started_at !== null;
                                $today     = \Carbon\Carbon::today();
                                $startDate = \Carbon\Carbon::parse($item->date_from);
                                $endDate   = \Carbon\Carbon::parse($item->date_to);

                                if (!$isActive) return 'pending_mount';
                                if ($today->lt($startDate)) return 'mounted_waiting';
                                if ($today->between($startDate, $endDate)) return 'active';
                                return 'completed';
                            });

                            $dominantStatus = $statuses->groupBy(fn($s) => $s)->sortByDesc(fn($g) => $g->count())->keys()->first();

                            $statusLabel = match($dominantStatus) {
                                'pending_mount'   => ['label' => '⏳ Ожидает монтажа',      'bg' => '#FEF3C7', 'color' => '#92400E'],
                                'mounted_waiting' => ['label' => '🔧 Смонтировано',          'bg' => '#EFF6FF', 'color' => '#1E40AF'],
                                'active'          => ['label' => '🚀 Активна сейчас',        'bg' => '#D1FAE5', 'color' => '#065F46'],
                                'completed'       => ['label' => '✅ Завершено',              'bg' => '#F3F4F6', 'color' => '#374151'],
                                default           => ['label' => $order->status,              'bg' => '#F3F4F6', 'color' => '#374151'],
                            };
                        @endphp

                        <div style="padding:14px 20px; {{ !$loop->last ? 'border-bottom:1px solid #e5e7eb;' : '' }}">

                            {{-- Партнёр + его статус --}}
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="width:36px; height:36px; background:#5B21B6; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:14px; flex-shrink:0;">
                                        {{ strtoupper(substr($partner->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600; font-size:14px;">{{ $partner->name }}</div>
                                        <div style="font-size:12px; color:#9ca3af;">{{ $items->count() }} {{ $items->count() === 1 ? 'площадка' : ($items->count() < 5 ? 'площадки' : 'площадок') }}</div>
                                    </div>
                                </div>
                                <span style="padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; background:{{ $statusLabel['bg'] }}; color:{{ $statusLabel['color'] }};">
                                    {{ $statusLabel['label'] }}
                                </span>
                            </div>

                            {{-- Площадки этого партнёра --}}
                            <div style="display:flex; flex-direction:column; gap:8px;">
                                @foreach($items as $item)
                                    @php
                                        $isActive  = $item->placement_started_at !== null;
                                        $today     = \Carbon\Carbon::today();
                                        $startDate = \Carbon\Carbon::parse($item->date_from);
                                        $endDate   = \Carbon\Carbon::parse($item->date_to);

                                        if (!$isActive) {
                                            $itemStatus = ['icon' => '⏳', 'color' => '#92400E'];
                                        } elseif ($today->lt($startDate)) {
                                            $itemStatus = ['icon' => '🔧', 'color' => '#1E40AF'];
                                        } elseif ($today->between($startDate, $endDate)) {
                                            $itemStatus = ['icon' => '🚀', 'color' => '#065F46'];
                                        } else {
                                            $itemStatus = ['icon' => '✅', 'color' => '#374151'];
                                        }
                                    @endphp

                                    <div style="display:flex; align-items:center; gap:10px; padding:8px; background:#f9fafb; border-radius:8px;">
                                        <div style="width:48px; height:36px; background:#e5e7eb; border-radius:4px; overflow:hidden; flex-shrink:0;">
                                            @if($item->spot->mainPhoto)
                                                <img src="{{ Storage::url($item->spot->mainPhoto->path) }}" style="width:100%; height:100%; object-fit:cover; display:block;">
                                            @endif
                                        </div>
                                        <div style="flex:1; min-width:0;">
                                            <div style="font-size:13px; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $item->spot->title }}</div>
                                            <div style="font-size:11px; color:#9ca3af;">
                                                {{ $startDate->format('d.m.Y') }} — {{ $endDate->format('d.m.Y') }}
                                            </div>
                                        </div>
                                        <span style="font-size:16px; flex-shrink:0;" title="{{ $itemStatus['icon'] }}">{{ $itemStatus['icon'] }}</span>
                                        <span style="font-size:13px; font-weight:600; color:#5B21B6; flex-shrink:0; white-space:nowrap;">{{ money($item->price + $item->commission, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    @endforeach

                </div>

            @endforeach
        </div>

        <div style="margin-top:24px;">
            {{ $orders->links() }}
        </div>
    @endif
</div>
