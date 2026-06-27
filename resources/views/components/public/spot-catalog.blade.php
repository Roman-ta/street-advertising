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
                    @foreach([
                        ''           => __('messages.catalog.all'),
                        'billboard'  => __('messages.types.billboard'),
                        'lightbox'   => __('messages.types.lightbox'),
                        'led_screen' => __('messages.types.led_screen'),
                        'banner'     => __('messages.types.banner'),
                        'transport'  => __('messages.types.transport'),
                        'indoor'     => __('messages.types.indoor'),
                        'digital'    => __('messages.types.digital'),
                        'event'      => __('messages.types.event'),
                    ] as $value => $label)
                        <button
                            wire:click="$set('type', '{{ $value }}')"
                            class="filters__type-btn {{ $type === $value ? 'filters__type-btn--active' : '' }}"
                        >{{ $label }}</button>
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
                <div class="spot-card-compact" id="spot-card-{{ $spot->id }}" onclick="focusSpotOnMap({{ $spot->id }}, {{ $spot->lat ?? 'null' }}, {{ $spot->lng ?? 'null' }})">
                    <a href="{{ route('spots.show', $spot->id) }}" onclick="event.stopPropagation()">
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
                                        <button type="button" class="spot-card-compact__map-btn" onclick="event.stopPropagation(); focusSpotOnMap({{ $spot->id }}, {{ $spot->lat }}, {{ $spot->lng }})">📍 {{ __('messages.catalog.on_map') }}</button>
                                    @endif
                                    <a href="{{ route('spots.show', $spot->id) }}" onclick="event.stopPropagation()" class="spot-card-compact__map-btn" style="background:#5B21B6; color:white">{{ __('messages.spot.details') }}</a>
                                </div>
                            </div>
                        </div>
                    </a>
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
            digital: '#06B6D4', event: '#EC4899',
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

    function loadCatalogMarkers() {
        fetch('/api/spots/map')
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
                        <div class="map-popup__price">$${parseInt(spot.price)}<span>{{ __('messages.spot.month_short') }}</span></div>
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

    document.addEventListener('DOMContentLoaded', initCatalogMap);
    document.addEventListener('livewire:navigated', initCatalogMap);

    document.addEventListener('livewire:updated', function() {
        if (catalogMap) setTimeout(() => catalogMap.invalidateSize(), 50);
    });
</script>
