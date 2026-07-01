<div>
    <div class="catalog-top">

        {{-- Фильтры слева --}}
        <div class="catalog-top__filters">
            <div class="filters">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('messages.catalog.search') }}"
                    class="filters__search"
                >

                <div class="filters__types">
                    <button
                        wire:click="$set('type', '')"
                        class="filters__type-btn {{ $type === '' ? 'filters__type-btn--active' : '' }}"
                    >{{ __('messages.catalog.all') }}</button>

                    @foreach($spotTypes as $spotType)
                        <button
                            wire:click="$set('type', '{{ $spotType->slug }}')"
                            class="filters__type-btn {{ $type === $spotType->slug ? 'filters__type-btn--active' : '' }}"
                        >{{ $spotType->icon }} {{ $spotType->name }}</button>
                    @endforeach
                </div>

                <select wire:model.live="city" class="filters__select" style="margin-bottom:12px">
                    <option value="">{{ __('messages.catalog.all_cities') }}</option>
                    @foreach(['Chisinau','Balti','Cahul','Ungheni','Soroca','Orhei'] as $c)
                        <option value="{{ $c }}">{{ __('messages.cities.' . $c) }}</option>
                    @endforeach
                </select>

                <select wire:model.live="traffic" class="filters__select" style="margin-bottom:12px">
                    <option value="">{{ __('messages.catalog.any_traffic') }}</option>
                    <option value="high">{{ __('messages.traffic.high') }}</option>
                    <option value="medium">{{ __('messages.traffic.medium') }}</option>
                    <option value="low">{{ __('messages.traffic.low') }}</option>
                </select>

                <div class="filters__price" style="margin-bottom:12px">
                    <span>{{ __('messages.catalog.up_to') }} $</span>
                    <input type="number" wire:model.live.debounce.500ms="price_max" class="filters__select">
                </div>

                <button wire:click="resetFilters" class="filters__reset">
                    {{ __('messages.catalog.reset') }}
                </button>
            </div>
        </div>

        {{-- Карта справа --}}
        <div class="catalog-top__map" wire:ignore>
            <div id="catalog-map"></div>
        </div>

    </div>

    <div class="catalog__count">
        {{ __('messages.catalog.found', ['count' => $spots->total()]) }}
    </div>

    {{-- Список карточек на всю ширину --}}
    @forelse($spots as $spot)
    @empty
        <div class="catalog__empty">
            <p>{{ __('messages.catalog.empty_title') }}</p>
            <p>{{ __('messages.catalog.empty_hint') }}</p>
        </div>
    @endforelse

    @if($spots->isNotEmpty())
        <div class="catalog__grid-full">
            @foreach($spots as $spot)
                <div class="spot-card-compact" id="spot-card-{{ $spot->id }}">
                    <div class="spot-card-compact__image">
                        @if($spot->mainPhoto)
                            <img src="{{ Storage::url($spot->mainPhoto->path) }}" loading="lazy">
                        @endif
                        <span class="spot-card-compact__badge">{{ __('messages.types.' . $spot->type) }}</span>
                    </div>
                    <div class="spot-card-compact__body">
                        <div class="spot-card-compact__title">{{ $spot->title }}</div>
                        <div class="spot-card-compact__address">📍 {{ $spot->address }}</div>
                        <div class="spot-card-compact__footer">
                            <span class="spot-card-compact__price">${{ number_format($spot->price_month, 0) }}<span style="font-size:11px; color:#9ca3af; font-weight:400">{{ __('messages.spot.month_short') }}</span></span>
                            <div style="display:flex; gap:6px">
                                @if($spot->lat && $spot->lng)
                                    <button type="button" class="spot-card-compact__map-btn" onclick="focusSpotOnMap({{ $spot->id }}, {{ $spot->lat }}, {{ $spot->lng }})">📍 {{ __('messages.catalog.on_map') }}</button>
                                @endif
                                <a href="{{ route('spots.show', $spot->id) }}" class="spot-card-compact__map-btn" style="background:#5B21B6; color:white">{{ __('messages.spot.details') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:24px">
            {{ $spots->links() }}
        </div>
    @endif
</div>

<script>
    let catalogMap = null;
    let catalogMarkers = {};

    function initCatalogMap() {
        if (catalogMap) return;

        catalogMap = L.map('catalog-map').setView([47.0245, 28.8322], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(catalogMap);

        setTimeout(() => catalogMap.invalidateSize(), 100);
        loadCatalogMarkers();
    }

    function typeColor(type) {
        const colors = {
            billboard: '#5B21B6', lightbox: '#0D9488', led_screen: '#F59E0B',
            banner: '#EF4444', transport: '#3B82F6', indoor: '#8B5CF6',
            digital: '#06B6D4', event: '#EC4899', radio: '#F43F5E',
            blogger: '#8B5CF6', youtube: '#EF4444', classified: '#0EA5E9',
        };
        return colors[type] || '#5B21B6';
    }

    function makeMarkerIcon(type) {
        const color = typeColor(type);
        return L.divIcon({
            className: '',
            html: `<div style="
            width:32px; height:32px;
            background:${color};
            border-radius:50% 50% 50% 0;
            transform:rotate(-45deg);
            border:3px solid white;
            box-shadow:0 2px 8px rgba(0,0,0,0.3);
        "></div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32],
        });
    }

    function loadCatalogMarkers(params = {}) {
        const qs = new URLSearchParams();
        if (params.type)      qs.append('type', params.type);
        if (params.city)      qs.append('city', params.city);
        if (params.traffic)   qs.append('traffic', params.traffic);
        if (params.price_max) qs.append('price_max', params.price_max);

        fetch('/api/spots/map?' + qs.toString())
            .then(r => r.json())
            .then(spots => {
                Object.values(catalogMarkers).forEach(m => catalogMap.removeLayer(m));
                catalogMarkers = {};

                spots.forEach(spot => {
                    if (!spot.lat || !spot.lng) return;

                    const marker = L.marker([spot.lat, spot.lng], {
                        icon: makeMarkerIcon(spot.type)
                    }).addTo(catalogMap);

                    marker.bindPopup(`
                    <div class="map-popup">
                        ${spot.photo ? `<img src="${spot.photo}" style="width:100%;height:100px;object-fit:cover;border-radius:6px;margin-bottom:8px">` : ''}
                        <div class="map-popup__title">${spot.title}</div>
                        <div class="map-popup__address">📍 ${spot.address}</div>
                        <div class="map-popup__price">${parseInt(spot.price)} lei</div>
                        <a href="${spot.url}" class="map-popup__btn">{{ __('messages.spot.details') }} →</a>
                    </div>
                `, { maxWidth: 220 });

                    marker.on('click', () => highlightCard(spot.id));
                    catalogMarkers[spot.id] = marker;
                });
            });
    }

    function focusSpotOnMap(spotId, lat, lng) {
        if (!catalogMap || !lat || !lng) return;
        catalogMap.setView([lat, lng], 16, { animate: true });
        const marker = catalogMarkers[spotId];
        if (marker) marker.openPopup();
        highlightCard(spotId);
        document.getElementById('catalog-map').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function highlightCard(spotId) {
        document.querySelectorAll('.spot-card-compact').forEach(el => {
            el.classList.remove('spot-card-compact--active');
        });
        const card = document.getElementById('spot-card-' + spotId);
        if (card) card.classList.add('spot-card-compact--active');
    }

    function getCurrentFilters() {
        const filters = {};

        // Активная кнопка типа
        const activeTypeBtn = document.querySelector('.filters__type-btn--active');
        if (activeTypeBtn) {
            // Берём значение из wire:click атрибута кнопки
            const wireClick = activeTypeBtn.getAttribute('wire:click') || '';
            const match = wireClick.match(/'type',\s*'([^']*)'/);
            if (match && match[1]) filters.type = match[1];
        }

        // Селект города
        const citySelect = document.querySelector('select[wire\\:model\\.live="city"]');
        if (citySelect && citySelect.value) filters.city = citySelect.value;

        // Селект трафика
        const trafficSelect = document.querySelector('select[wire\\:model\\.live="traffic"]');
        if (trafficSelect && trafficSelect.value) filters.traffic = trafficSelect.value;

        // Инпут максимальной цены
        const priceInput = document.querySelector('input[wire\\:model\\.live\\.debounce\\.500ms="price_max"]');
        if (priceInput && priceInput.value) filters.price_max = priceInput.value;

        return filters;
    }

    document.addEventListener('DOMContentLoaded', initCatalogMap);
    document.addEventListener('livewire:navigated', initCatalogMap);

    // Livewire v3 — правильный способ слушать обновления
    document.addEventListener('DOMContentLoaded', function() {
        // Ждём пока Livewire инициализируется
        setTimeout(() => {
            if (typeof Livewire === 'undefined') return;

            Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                succeed(({ snapshot, effect }) => {
                    if (!catalogMap) return;

                    try {
                        // snapshot уже объект, не нужен JSON.parse
                        const data = snapshot.data;
                        console.log('Livewire commit, data:', data);
                        loadCatalogMarkers({
                            type:      data.type      || '',
                            city:      data.city      || '',
                            traffic:   data.traffic   || '',
                            price_max: data.price_max || '',
                        });
                    } catch(e) {
                        console.log('Error:', e);
                        loadCatalogMarkers();
                    }

                    setTimeout(() => catalogMap.invalidateSize(), 50);
                });
            });
        }, 500);
    });
</script>
