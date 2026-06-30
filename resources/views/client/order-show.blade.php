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
                            {{ __('messages.order_show.formats_label') }} {{ implode(', ', array_map('strtoupper', $item->spot->file_types_allowed)) }}
                        </div>
                    @endif
                </div>
                <div class="order-show__item-price">
                    {{ money($item->price + $item->commission, 2) }}
                </div>
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

            <div class="materials-upload__design">
                <div>
                    <strong style="font-size:14px; color:#92400E;">🎨 Нет качественного макета?</strong>
                    <p style="font-size:13px; color:#92400E; margin:2px 0 0;">Закажите дизайн у нашей команды</p>
                </div>
                <button class="materials-upload__btn" type="button" disabled >
                    Заказать дизайнера<br><span>скоро</span>
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
                <span wire:loading.remove wire:target="uploadMaterials">{{ __('messages.order_show.upload_btn') }}</span>
                <span wire:loading wire:target="uploadMaterials">{{ __('messages.order_show.uploading') }}</span>
            </button>
        </div>
    @endif

    {{-- Загруженные файлы --}}
    @if($order->files->isNotEmpty())
        <div class="materials-list">
            <h3 class="materials-list__title">{{ __('messages.order_show.uploaded_materials') }}</h3>
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
                        {{ __('messages.order_show.download') }}
                    </a>
                </div>
            @endforeach
        </div>
    @endif

</div>
