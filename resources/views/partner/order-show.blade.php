<div class="partner-page">

    <div style="margin-bottom:24px">
        <a href="{{ route('partner.orders') }}" style="color:#5B21B6; font-size:14px">← Назад к заказам</a>
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
                    'paid_pending'    => 'Оплачен',
                    'materials_ready' => 'Материалы готовы — нужен монтаж',
                    'active'          => 'Активен',
                    'completed'       => 'Завершён',
                    default           => $item->order->status,
                } }}
            </span>
        </div>
        <div style="text-align:right; flex-shrink:0">
            <div style="font-size:20px; font-weight:700; color:#5B21B6">
                ${{ number_format($item->price * 0.9, 2) }}
            </div>
            <div style="font-size:12px; color:#9ca3af">ваша доля</div>
        </div>
    </div>

    {{-- Таймер если активен --}}
    @if($item->placement_started_at)
        <div style="background:#D1FAE5; border:1px solid #6EE7B7; border-radius:12px; padding:16px; margin-bottom:24px">
            <h3 style="color:#065F46; margin:0 0 8px; font-size:16px">🚀 Реклама активна</h3>
            <div style="font-size:14px; color:#065F46">
                Начало: {{ \Carbon\Carbon::parse($item->placement_started_at)->format('d.m.Y H:i') }}
            </div>
            <div style="font-size:14px; color:#065F46">
                Конец: {{ \Carbon\Carbon::parse($item->placement_ends_at)->format('d.m.Y H:i') }}
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
            <h3 class="materials-list__title">📁 Рекламные материалы клиента</h3>
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
                        Скачать
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Загрузка фотоотчёта --}}
    @if($item->order->status === 'materials_ready')
        <div class="materials-upload">
            <h3 class="materials-upload__title">📸 Загрузите фотоотчёт</h3>
            <p class="materials-upload__hint">
                Сделайте фото установленной рекламы и загрузите здесь.
                В момент загрузки автоматически запустится таймер аренды.
            </p>

            <div
                class="spot-form__upload-area"
                onclick="document.getElementById('report-input').click()"
                style="cursor:pointer;"
            >
                <div style="font-size:40px; margin-bottom:8px;">📸</div>
                <strong style="font-size:15px; color:#374151; display:block; margin-bottom:6px;">Нажмите чтобы выбрать фото</strong>
                <p style="margin:0; color:#9ca3af; font-size:13px;">Фото установленной рекламы · JPG, PNG</p>
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
                ⏳ Загружаем...
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
                    🚀 Сдать работу и запустить таймер
                </span>
                <span wire:loading wire:target="uploadPhotoReport">
                    Загружаем...
                </span>
            </button>
        </div>
    @endif

    {{-- Уже загруженные фотоотчёты --}}
    @if($photoReports->isNotEmpty())
        <div class="materials-list">
            <h3 class="materials-list__title">✅ Загруженные фотоотчёты</h3>
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
                        Посмотреть
                    </a>
                </div>
            @endforeach
        </div>
    @endif

</div>
