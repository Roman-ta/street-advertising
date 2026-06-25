<div class="partner-page">

    <div style="margin-bottom:24px">
        <a href="{{ route('partner.orders') }}" style="color:#5B21B6; font-size:14px">← {{ __('messages.partner.back_to_orders') }}</a>
    </div>

    @if($successMessage)
        <div class="alert alert--success">{{ $successMessage }}</div>
    @endif

    @if($error)
        <div class="alert alert--error">{{ $error }}</div>
    @endif

    {{-- Информация о заказе --}}
    <div class="spot-row" style="margin-bottom:24px">
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
                    default           => $item->order->status,
                } }}
            </span>
        </div>
        <div style="text-align:right; flex-shrink:0">
            <div style="font-size:20px; font-weight:700; color:#5B21B6">
                ${{ number_format($item->price * 0.9, 2) }}
            </div>
            <div style="font-size:12px; color:#9ca3af">{{ __('messages.partner.your_share_short') }}</div>
        </div>
    </div>

    {{-- Таймер если активен --}}
    @if($item->placement_started_at)
        <div style="background:#D1FAE5; border:1px solid #6EE7B7; border-radius:12px; padding:16px; margin-bottom:24px">
            <h3 style="color:#065F46; margin:0 0 8px; font-size:16px">🚀 {{ __('messages.partner.ad_active') }}</h3>
            <div style="font-size:14px; color:#065F46">
                {{ __('messages.partner.start_label') }} {{ \Carbon\Carbon::parse($item->placement_started_at)->format('d.m.Y H:i') }}
            </div>
            <div style="font-size:14px; color:#065F46">
                {{ __('messages.partner.end_label') }} {{ \Carbon\Carbon::parse($item->placement_ends_at)->format('d.m.Y H:i') }}
            </div>
        </div>
    @endif

    {{-- Рекламные материалы клиента --}}
    @php
        $materials = $item->order->files->where('type', 'material');
        $photoReports = $item->order->files->where('type', 'photo_report');
    @endphp

    @if($materials->isNotEmpty())
        <div class="materials-list" style="margin-bottom:24px">
            <h3 class="materials-list__title">📁 {{ __('messages.partner.client_materials') }}</h3>
            @foreach($materials as $file)
                <div class="materials-list__item">
                    <span class="materials-list__icon">
                        {{ str_contains($file->mime_type ?? '', 'video') ? '🎬' : '📄' }}
                    </span>
                    <div class="materials-list__info">
                        <div class="materials-list__name">{{ basename($file->path) }}</div>
                        <div class="materials-list__meta">
                            {{ round($file->size_bytes / 1024 / 1024, 2) }} MB
                        </div>
                    </div>
                    <a href="{{ Storage::url($file->path) }}" download class="btn btn--primary btn--sm">
                        {{ __('messages.partner.download') }}
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Загрузка фотоотчёта --}}
    @if($item->order->status === 'materials_ready')
        <div class="materials-upload">
            <h3 class="materials-upload__title">📸 {{ __('messages.partner.upload_report_title') }}</h3>
            <p class="materials-upload__hint">
                {{ __('messages.partner.upload_report_hint') }}
            </p>

            <div
                class="spot-form__upload-area"
                onclick="document.getElementById('report-input').click()"
                style="cursor:pointer;"
            >
                <div style="font-size:40px; margin-bottom:8px;">📸</div>
                <strong style="font-size:15px; color:#374151; display:block; margin-bottom:6px;">{{ __('messages.partner.report_click') }}</strong>
                <p style="margin:0; color:#9ca3af; font-size:13px;">{{ __('messages.partner.report_hint') }}</p>
            </div>

            <input
                id="report-input"
                type="file"
                wire:model="photos"
                multiple
                accept="image/*"
                style="display:none"
            >

            <div wire:loading wire:target="photos" style="text-align:center; padding:12px; color:#5B21B6; font-size:14px;">
                ⏳ {{ __('messages.partner.uploading') }}
            </div>

            @if(!empty($photos))
                <div style="display:flex; gap:8px; flex-wrap:wrap; margin:16px 0;">
                    @foreach($photos as $photo)
                        <div style="width:100px; height:75px;">
                            <img src="{{ $photo->temporaryUrl() }}" alt="preview" style="width:100%; height:100%; object-fit:cover; border-radius:6px; display:block;">
                        </div>
                    @endforeach
                </div>
            @endif

            <button
                wire:click="uploadPhotoReport"
                class="btn btn--primary btn--full btn--lg"
                {{ empty($photos) ? 'disabled' : '' }}
                style="margin-top:16px;"
            >
                <span wire:loading.remove wire:target="uploadPhotoReport">
                    🚀 {{ __('messages.partner.submit_and_start_timer') }}
                </span>
                <span wire:loading wire:target="uploadPhotoReport">
                    {{ __('messages.partner.uploading') }}
                </span>
            </button>
        </div>
    @endif

    {{-- Уже загруженные фотоотчёты --}}
    @if($photoReports->isNotEmpty())
        <div class="materials-list">
            <h3 class="materials-list__title">✅ {{ __('messages.partner.uploaded_reports') }}</h3>
            @foreach($photoReports as $file)
                <div class="materials-list__item">
                    <span class="materials-list__icon">📸</span>
                    <div class="materials-list__info">
                        <div class="materials-list__name">{{ basename($file->path) }}</div>
                        <div class="materials-list__meta">
                            {{ $file->created_at->format('d.m.Y H:i') }}
                        </div>
                    </div>
                    <a href="{{ Storage::url($file->path) }}" target="_blank" class="btn btn--outline btn--sm">
                        {{ __('messages.partner.view') }}
                    </a>
                </div>
            @endforeach
        </div>
    @endif

</div>
