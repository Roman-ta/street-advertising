<div class="order-show">

    @if($successMessage)
        <div class="alert alert--success" style="margin-bottom:24px">
            {{ $successMessage }}
        </div>
    @endif

    <div class="order-show__hero">
        <div class="order-show__hero-emoji">
            {{ match($order->status) {
                'pending'         => '⏳',
                'paid_pending'    => '💳',
                'materials_ready' => '📦',
                'active'          => '🚀',
                'completed'       => '✅',
                default           => '📋',
            } }}
        </div>
        <h2>{{ __('messages.order_show.order_number', ['id' => $order->id]) }}</h2>
        <p>{{ $order->created_at->format('d.m.Y') }}</p>
    </div>

    <div class="order-show__status order-show__status--{{ $order->status }}">
        {{ __('messages.order_show_status.' . $order->status) }}
    </div>
        {{-- Блок "Осталось дней" для активных заказов --}}
        @if($order->status === 'active')
            @foreach($order->items as $item)
                @php
                    $endDate   = \Carbon\Carbon::parse($item->date_to);
                    $today     = \Carbon\Carbon::today();
                    $daysLeft  = $today->diffInDays($endDate, false);
                @endphp

                @if($daysLeft >= 0)
                    <div style="
                border-radius:12px;
                padding:20px;
                margin-bottom:16px;
                background:{{ $daysLeft <= 3 ? '#FEF3C7' : '#EFF6FF' }};
                border:1px solid {{ $daysLeft <= 3 ? '#FDE68A' : '#BFDBFE' }};
            ">
                        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px;">
                            <div>
                                <div style="font-size:13px; color:#6b7280; margin-bottom:4px;">
                                    {{ $item->spot->title }}
                                </div>
                                <div style="font-size:22px; font-weight:700; color:{{ $daysLeft <= 3 ? '#92400E' : '#1E40AF' }}">
                                    @if($daysLeft === 0)
                                        🔴 Последний день размещения
                                    @elseif($daysLeft <= 3)
                                        ⚠️ Осталось {{ $daysLeft }} дн.
                                    @else
                                        ✅ Осталось {{ $daysLeft }} дн.
                                    @endif
                                </div>
                                <div style="font-size:13px; color:#6b7280; margin-top:2px;">
                                    до {{ $endDate->format('d.m.Y') }}
                                </div>
                            </div>
                            <button
                                wire:click="extendOrder"
                                class="btn btn--primary"
                                style="white-space:nowrap; flex-shrink:0;"
                            >
                                🔄 Продлить
                            </button>
                        </div>

                        @if($daysLeft <= 3)
                            <div style="font-size:12px; color:#92400E; margin-top:12px; padding-top:12px; border-top:1px solid #FDE68A;">
                                ⏰ Реклама скоро закончится. Продлите сейчас чтобы не потерять место!
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        @endif
    {{-- Позиции заказа --}}
    <div class="order-show__items">
        @foreach($order->items as $item)
            @php
                $photoReports = $order->files->where('type', 'photo_report');
                $materials    = $order->files->where('type', 'material');
                $startDate    = \Carbon\Carbon::parse($item->date_from);
                $endDate      = \Carbon\Carbon::parse($item->date_to);
                $today        = \Carbon\Carbon::today();
                $isActive     = $item->placement_started_at !== null;
                $isFuture     = $today->lt($startDate);
                $isCurrent    = $today->between($startDate, $endDate);
                $isPast       = $today->gt($endDate);
            @endphp

            <div style="border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; margin-bottom:16px;">

                {{-- Шапка позиции --}}
                <div style="padding:16px; display:flex; gap:12px; align-items:flex-start;">
                    <div class="order-show__item-photo">
                        @if($item->spot->mainPhoto)
                            <img src="{{ Storage::url($item->spot->mainPhoto->path) }}" alt="">
                        @endif
                    </div>
                    <div style="flex:1;">
                        <div class="order-show__item-title">{{ $item->spot->title }}</div>
                        <div class="order-show__item-dates">
                            📅 {{ $startDate->format('d.m.Y') }} — {{ $endDate->format('d.m.Y') }}
                        </div>
                        {{-- Статус позиции --}}
                        <div style="margin-top:8px;">
                            @if(!$isActive)
                                <span class="order-status order-status--materials_ready">
                                    📦 Ожидает монтажа
                                </span>
                            @elseif($isFuture)
                                <span class="order-status order-status--paid_pending">
                                    🗓 Монтаж выполнен · старт {{ $startDate->format('d.m.Y') }}
                                </span>
                            @elseif($isCurrent)
                                <span class="order-status order-status--active">
                                    🚀 Активна сейчас
                                </span>
                            @else
                                <span class="order-status order-status--completed">
                                    ✅ Завершена
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="order-show__item-price">
                        {{ money($item->price + $item->commission, 2) }}
                    </div>
                </div>

                {{-- Фотоотчёт партнёра --}}
                @if($isActive && $photoReports->isNotEmpty())
                    <div style="border-top:1px solid #e5e7eb; padding:16px; background:#f8f8f8;">
                        <div style="font-weight:600; font-size:14px; margin-bottom:12px; color:#065F46;">
                            📸 Фотоотчёт — реклама установлена
                        </div>
                        <div style="display:flex; gap:10px; flex-wrap:wrap;">
                            @foreach($photoReports as $report)
                                <a href="{{ Storage::url($report->path) }}" target="_blank" style="display:block; border-radius:8px; overflow:hidden; border:2px solid #e5e7eb; transition:border-color 0.2s;" onmouseover="this.style.borderColor='#5B21B6'" onmouseout="this.style.borderColor='#e5e7eb'">
                                    <img src="{{ Storage::url($report->path) }}" style="width:120px; height:90px; object-fit:cover; display:block;">
                                </a>
                            @endforeach
                        </div>
                        <div style="font-size:12px; color:#9ca3af; margin-top:8px;">
                            Нажмите на фото чтобы открыть в полном размере
                        </div>
                    </div>
                @endif

                {{-- Форматы материалов --}}
                @if($item->spot->file_types_allowed)
                    <div style="border-top:1px solid #e5e7eb; padding:10px 16px; background:#fafafa;">
                        <span style="font-size:12px; color:#9ca3af;">
                            {{ __('messages.order_show.formats_label') }}
                            {{ implode(', ', array_map('strtoupper', $item->spot->file_types_allowed)) }}
                        </span>
                    </div>
                @endif

            </div>
        @endforeach
    </div>

    <div class="order-show__total">
        <span>{{ __('messages.order_show.total') }}</span>
        <span>{{ money($order->total, 2) }}</span>
    </div>

    {{-- Оплата --}}
    @if($order->status === 'pending')
        <button wire:click="simulatePayment" class="btn btn--primary btn--full btn--lg">
            <span wire:loading.remove>{{ __('messages.order_show.pay_button') }}</span>
            <span wire:loading>{{ __('messages.order_show.paying') }}</span>
        </button>
        <p class="order-show__hint">{{ __('messages.order_show.pay_hint') }}</p>
    @endif

    {{-- Загрузка материалов --}}
    @if($order->status === 'paid_pending')
        <div class="materials-upload">
            <h3 class="materials-upload__title">📁 {{ __('messages.order_show.upload_materials_title') }}</h3>
            <p class="materials-upload__hint">
                {{ __('messages.order_show.upload_materials_hint') }}
            </p>

            {{-- Кнопка дизайнера --}}
            <div style="background:#FEF3C7; border:1px dashed #F59E0B; border-radius:8px; padding:14px 16px; margin-bottom:16px; display:flex; align-items:center; justify-content:space-between; gap:12px;">
                <div>
                    <strong style="font-size:14px; color:#92400E;">🎨 Нет качественного макета?</strong>
                    <p style="font-size:13px; color:#92400E; margin:2px 0 0;">Закажите дизайн у нашей команды</p>
                </div>
                <button type="button" disabled style="background:#e5e7eb; color:#9ca3af; border:none; padding:8px 16px; border-radius:6px; font-size:13px; font-weight:600; cursor:not-allowed; white-space:nowrap;">
                    Заказать дизайнера<br><span style="font-size:10px; font-weight:400;">скоро</span>
                </button>
            </div>

            <div class="form__group">
                <input type="file" wire:model="uploadedFiles" multiple class="form__input">
                @error('uploadedFiles.*')
                <span class="form__error">{{ $message }}</span>
                @enderror
            </div>

            @if(!empty($uploadedFiles))
                <div class="materials-upload__preview">
                    @foreach($uploadedFiles as $file)
                        <div class="materials-upload__file">
                            <span class="materials-upload__file-icon">📄</span>
                            <span class="materials-upload__file-name">{{ $file->getClientOriginalName() }}</span>
                            <span class="materials-upload__file-size">{{ round($file->getSize() / 1024 / 1024, 2) }} MB</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <button
                wire:click="uploadMaterials"
                class="btn btn--primary btn--full btn--lg"
                {{ empty($uploadedFiles) ? 'disabled' : '' }}
            >
                <span wire:loading.remove wire:target="uploadMaterials">{{ __('messages.order_show.upload_btn') }}</span>
                <span wire:loading wire:target="uploadMaterials">{{ __('messages.order_show.uploading') }}</span>
            </button>
        </div>
    @endif

    {{-- Все загруженные файлы материалов --}}
    @if($order->files->where('type', 'material')->isNotEmpty())
        <div class="materials-list">
            <h3 class="materials-list__title">{{ __('messages.order_show.uploaded_materials') }}</h3>
            @foreach($order->files->where('type', 'material') as $file)
                <div class="materials-list__item">
                    <span class="materials-list__icon">
                        {{ str_contains($file->mime_type ?? '', 'video') ? '🎬' : '📄' }}
                    </span>
                    <div class="materials-list__info">
                        <div class="materials-list__name">{{ basename($file->path) }}</div>
                        <div class="materials-list__meta">
                            {{ round($file->size_bytes / 1024 / 1024, 2) }} MB ·
                            {{ $file->created_at->format('d.m.Y H:i') }}
                        </div>
                    </div>
                    <a href="{{ Storage::url($file->path) }}" download class="btn btn--outline btn--sm">
                        {{ __('messages.order_show.download') }}
                    </a>
                </div>
            @endforeach
        </div>
    @endif

</div>
