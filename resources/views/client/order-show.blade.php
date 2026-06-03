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
        <h2>Заказ #{{ $order->id }}</h2>
        <p>{{ $order->created_at->format('d.m.Y') }}</p>
    </div>

    <div class="order-show__status order-show__status--{{ $order->status }}">
        {{ match($order->status) {
            'pending'         => '⏳ Ожидает оплаты',
            'paid_pending'    => '💳 Оплачен — загрузите рекламные материалы',
            'materials_ready' => '📦 Материалы загружены — ожидаем монтажа',
            'active'          => '🚀 Реклама размещена и работает',
            'completed'       => '✅ Размещение завершено',
            'cancelled'       => '❌ Отменён',
            default           => $order->status,
        } }}
    </div>

    <div class="order-show__items">
        @foreach($order->items as $item)
            <div class="order-show__item">
                <div class="order-show__item-photo">
                    @if($item->spot->mainPhoto)
                        <img src="{{ Storage::url($item->spot->mainPhoto->path) }}" alt="">
                    @endif
                </div>
                <div class="order-show__item-info">
                    <div class="order-show__item-title">{{ $item->spot->title }}</div>
                    <div class="order-show__item-dates">
                        📅 {{ \Carbon\Carbon::parse($item->date_from)->format('d.m.Y') }}
                        — {{ \Carbon\Carbon::parse($item->date_to)->format('d.m.Y') }}
                    </div>
                    @if($item->spot->file_types_allowed)
                        <div style="font-size:12px; color:#9ca3af; margin-top:4px">
                            Форматы: {{ implode(', ', array_map('strtoupper', $item->spot->file_types_allowed)) }}
                        </div>
                    @endif
                </div>
                <div class="order-show__item-price">
                    ${{ number_format($item->price + $item->commission, 2) }}
                </div>
            </div>
        @endforeach
    </div>

    <div class="order-show__total">
        <span>Итого</span>
        <span>${{ number_format($order->total, 2) }}</span>
    </div>

    {{-- Оплата --}}
    @if($order->status === 'pending')
        <button wire:click="simulatePayment" class="btn btn--primary btn--full btn--lg">
            <span wire:loading.remove>Перейти к оплате →</span>
            <span wire:loading>Обрабатываем...</span>
        </button>
        <p class="order-show__hint">Вы будете перенаправлены на страницу безопасной оплаты</p>
    @endif

    {{-- Загрузка материалов --}}
    @if($order->status === 'paid_pending')
        <div class="materials-upload">
            <h3 class="materials-upload__title">📁 Загрузите рекламные материалы</h3>
            <p class="materials-upload__hint">
                После загрузки партнёр получит уведомление и приступит к монтажу.
            </p>

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
                            <span class="materials-upload__file-name">
                                {{ $file->getClientOriginalName() }}
                            </span>
                            <span class="materials-upload__file-size">
                                {{ round($file->getSize() / 1024 / 1024, 2) }} MB
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif

            <button
                wire:click="uploadMaterials"
                class="btn btn--primary btn--full btn--lg"
                {{ empty($uploadedFiles) ? 'disabled' : '' }}
            >
                <span wire:loading.remove wire:target="uploadMaterials">Загрузить материалы</span>
                <span wire:loading wire:target="uploadMaterials">Загружаем...</span>
            </button>
        </div>
    @endif

    {{-- Загруженные файлы --}}
    @if($order->files->isNotEmpty())
        <div class="materials-list">
            <h3 class="materials-list__title">Загруженные материалы</h3>
            @foreach($order->files as $file)
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
                        Скачать
                    </a>
                </div>
            @endforeach
        </div>
    @endif

</div>
