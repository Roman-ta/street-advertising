<div>
    {{-- Фильтры --}}
    <div class="filters">

        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Поиск по названию или адресу..."
            class="filters__search"
        >

        <div class="filters__types">
            @foreach([
                ''           => 'Все',
                'billboard'  => 'Билборды',
                'lightbox'   => 'Лайтбоксы',
                'led_screen' => 'LED экраны',
                'banner'     => 'Баннеры',
                'transport'  => 'Транспорт',
                'indoor'     => 'В помещении',
                'digital'    => 'Digital',
                'event'      => 'Event',
            ] as $value => $label)
                <button
                    wire:click="$set('type', '{{ $value }}')"
                    class="filters__type-btn {{ $type === $value ? 'filters__type-btn--active' : '' }}"
                >{{ $label }}</button>
            @endforeach
        </div>

        <div class="filters__row">
            <select wire:model.live="city" class="filters__select">
                <option value="">Все города</option>
                <option value="Chisinau">Кишинёв</option>
                <option value="Balti">Бельцы</option>
                <option value="Cahul">Кагул</option>
                <option value="Ungheni">Унгены</option>
                <option value="Soroca">Сорока</option>
                <option value="Orhei">Орхей</option>
            </select>

            <select wire:model.live="traffic" class="filters__select">
                <option value="">Любой трафик</option>
                <option value="high">Высокий</option>
                <option value="medium">Средний</option>
                <option value="low">Низкий</option>
            </select>

            <div class="filters__price">
                <span>От</span>
                <input
                    type="number"
                    wire:model.live.debounce.500ms="price_min"
                    class="filters__select"
                >
            </div>

            <div class="filters__price">
                <span>До</span>
                <input
                    type="number"
                    wire:model.live.debounce.500ms="price_max"
                    class="filters__select"
                >
            </div>

            <label class="filters__checkbox">
                <input type="checkbox" wire:model.live="lighting">
                <span>С подсветкой</span>
            </label>

            <button wire:click="resetFilters" class="filters__reset">
                Сбросить фильтры
            </button>
        </div>
    </div>

    <div class="catalog__count">
        Найдено: {{ $spots->total() }} площадок
    </div>

    @if($spots->isEmpty())
        <div class="catalog__empty">
            <p>Ничего не найдено</p>
            <p>Попробуйте изменить фильтры</p>
        </div>
    @else
        <div class="catalog__grid">
            @foreach($spots as $spot)
                <a href="{{ route('spots.show', $spot->id) }}" class="spot-card">

                    <div class="spot-card__image">
                        @if($spot->mainPhoto)
                            <img
                                src="{{ Storage::url($spot->mainPhoto->path) }}"
                                alt="{{ $spot->title }}"
                                loading="lazy"
                            >
                        @else
                            <div class="spot-card__no-photo">Нет фото</div>
                        @endif

                        <div class="spot-card__badges">
                            <span class="spot-card__type-badge">
                                {{ match($spot->type) {
                                    'billboard'  => 'Билборд',
                                    'lightbox'   => 'Лайтбокс',
                                    'led_screen' => 'LED экран',
                                    'banner'     => 'Баннер',
                                    'transport'  => 'Транспорт',
                                    'indoor'     => 'В помещении',
                                    'digital'    => 'Digital',
                                    'event'      => 'Event',
                                    default      => $spot->type,
                                } }}
                            </span>
                            @if($spot->lighting)
                                <span class="spot-card__light-badge">☀ Подсветка</span>
                            @endif
                        </div>
                    </div>

                    <div class="spot-card__body">
                        <h3 class="spot-card__title">{{ $spot->title }}</h3>
                        <p class="spot-card__address">📍 {{ $spot->address }}</p>

                        <div class="spot-card__meta">
                            @if($spot->size_w && $spot->size_h)
                                <span>📐 {{ $spot->size_w }}×{{ $spot->size_h }}м</span>
                            @endif
                            <span>
                                {{ match($spot->traffic) {
                                    'high'   => '🔴 Высокий',
                                    'medium' => '🟡 Средний',
                                    'low'    => '🟢 Низкий',
                                    default  => '',
                                } }}
                            </span>
                        </div>

                        <div class="spot-card__footer">
                            <div class="spot-card__price">
                                ${{ number_format($spot->price_month, 0) }}
                                <span>/месяц</span>
                            </div>
                            <span class="spot-card__cta">Подробнее →</span>
                        </div>
                    </div>

                </a>
            @endforeach
        </div>

        <div style="margin-top:32px">
            {{ $spots->links() }}
        </div>
    @endif
</div>
