
<div class="spot-show">

    <a href="{{ route('home') }}" class="spot-show__back">← Назад к каталогу</a>

    <div class="spot-show__layout">

        {{-- Левая часть --}}
        <div>
            <div class="spot-show__gallery">
                @if($spot->photos->isNotEmpty())
                    <img
                        src="{{ Storage::url($spot->photos->first()->path) }}"
                        alt="{{ $spot->title }}"
                        class="spot-show__main-photo"
                    >
                    @if($spot->photos->count() > 1)
                        <div class="spot-show__thumbnails">
                            @foreach($spot->photos->skip(1) as $photo)
                                <img
                                    src="{{ Storage::url($photo->path) }}"
                                    class="spot-show__thumbnail"
                                >
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="spot-show__no-photo">Нет фото</div>
                @endif
            </div>

            <h1 class="spot-show__title">{{ $spot->title }}</h1>
            <p class="spot-show__address">📍 {{ $spot->address }}, {{ $spot->city }}</p>

            @if($spot->description)
                <div class="spot-show__description">
                    <h3>Описание</h3>
                    <p>{{ $spot->description }}</p>
                </div>
            @endif

            <div class="spot-show__specs">
                <h3>Характеристики</h3>
                <div class="spot-show__specs-grid">
                    <div class="spot-show__spec-item">
                        <span>Тип</span>
                        <p>{{ match($spot->type) {
                            'billboard'  => 'Билборд',
                            'lightbox'   => 'Лайтбокс',
                            'led_screen' => 'LED экран',
                            'banner'     => 'Баннер',
                            'transport'  => 'Транспорт',
                            'indoor'     => 'В помещении',
                            'digital'    => 'Digital',
                            'event'      => 'Event',
                            default      => $spot->type,
                        } }}</p>
                    </div>
                    @if($spot->size_w && $spot->size_h)
                        <div class="spot-show__spec-item">
                            <span>Размер</span>
                            <p>{{ $spot->size_w }}×{{ $spot->size_h }} м</p>
                        </div>
                    @endif
                    <div class="spot-show__spec-item">
                        <span>Трафик</span>
                        <p>{{ match($spot->traffic) {
                            'high'   => 'Высокий',
                            'medium' => 'Средний',
                            'low'    => 'Низкий',
                            default  => $spot->traffic,
                        } }}</p>
                    </div>
                    <div class="spot-show__spec-item">
                        <span>Подсветка</span>
                        <p>{{ $spot->lighting ? 'Есть' : 'Нет' }}</p>
                    </div>
                    @if($spot->file_types_allowed)
                        <div class="spot-show__spec-item spot-show__spec-item--full">
                            <span>Форматы материалов</span>
                            <p>{{ implode(', ', array_map('strtoupper', $spot->file_types_allowed)) }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Правая часть --}}
        <div>
            <div class="spot-show__booking">

                <div class="spot-show__price">
                    ${{ number_format($spot->price_month, 0) }}
                </div>
                <div class="spot-show__price-hint">
                    в месяц · ${{ number_format($spot->price_month / 30, 1) }} в день
                </div>

                <div class="spot-show__dates">
                    <div>
                        <label>Дата начала</label>
                        <input
                            type="date"
                            wire:model="date_from"
                            wire:change="$set('date_from', $event.target.value)"
                            min="{{ now()->addDay()->format('Y-m-d') }}"
                        >
                    </div>
                    <div>
                        <label>Дата окончания</label>
                        <input
                            type="date"
                            wire:model="date_to"
                            wire:change="$set('date_to', $event.target.value)"
                            min="{{ now()->addDays(2)->format('Y-m-d') }}"
                        >
                    </div>
                </div>

                @if($error)
                    <div class="alert alert--error">{{ $error }}</div>
                @endif

                @if(!empty($occupiedDates))
                    <div class="alert alert--warning">
                        ⚠ Некоторые даты уже заняты
                    </div>
                @endif

                @if($days > 0 && !$error)
                    <div class="spot-show__calc">
                        <div class="spot-show__calc-row">
                            <span>${{ number_format($spot->price_month / 30, 1) }} × {{ $days }} дней</span>
                            <span>${{ number_format($base_price, 2) }}</span>
                        </div>
{{--                        <div class="spot-show__calc-row">--}}
{{--                            <span>Комиссия платформы (10%)</span>--}}
{{--                            <span>${{ number_format($commission, 2) }}</span>--}}
{{--                        </div>--}}
                        <div class="spot-show__calc-total">
                            <span>Итого</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                @endif

                @if($days > 0 && !$error)
                    @auth
                        <button wire:click="addToCart" class="btn btn--primary btn--full btn--lg">
                            <span wire:loading.remove>Добавить в корзину</span>
                            <span wire:loading>Добавляем...</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn--primary btn--full btn--lg">
                            Войти для бронирования
                        </a>
                    @endauth
                @else
                    <button disabled class="btn btn--full btn--lg" style="background:$gray-200; color:$gray-400; cursor:not-allowed">
                        Выберите даты
                    </button>
                @endif

                <p class="spot-show__hint">
                    Деньги заморозятся — спишутся только после монтажа
                </p>

                <div class="spot-show__owner">
                    <p>Владелец площадки</p>
                    <p>{{ $spot->partner->name }}</p>
                </div>

            </div>
        </div>

    </div>
</div>
