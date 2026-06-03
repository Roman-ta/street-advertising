@extends('layouts.app')
@section('title', 'AdSpot — Рекламные площадки Молдовы')
@section('content')

    <section class="hero">
        <div class="hero__inner">
            <h1 class="hero__title">Ваша реклама.<br>В нужном месте.</h1>
            <p class="hero__subtitle">
                Единая платформа для поиска и бронирования рекламных площадок по всей Молдове
            </p>
            <div class="hero__actions">
                <a href="#catalog" class="hero__btn-primary">Найти площадку →</a>
                @guest
                    <a href="{{ route('register') }}" class="hero__btn-outline">Разместить площадку</a>
                @endguest
            </div>
        </div>
    </section>

    {{-- Карта --}}
    <div class="container" style="padding-top:32px">
        <h2 class="catalog__title">Площадки на карте</h2>
        <div class="map-section">
            <div id="main-map"></div>
        </div>
    </div>

    {{-- Каталог --}}
    <div id="catalog" class="container">
        <div class="catalog">
            <h2 class="catalog__title">Все площадки</h2>
            <livewire:public.spot-catalog />
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Инициализация карты с центром на Кишинёве
            const map = L.map('main-map').setView([47.0245, 28.8322], 13);

            // OpenStreetMap тайлы
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Цвета маркеров по типу
            const typeColors = {
                'billboard':  '#5B21B6',
                'lightbox':   '#0D9488',
                'led_screen': '#F59E0B',
                'banner':     '#EF4444',
                'transport':  '#3B82F6',
                'indoor':     '#8B5CF6',
                'digital':    '#06B6D4',
                'event':      '#EC4899',
            };

            // Кастомный маркер
            function makeIcon(type) {
                const color = typeColors[type] || '#5B21B6';
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

            // Загрузка маркеров с API
            fetch('/api/spots/map')
                .then(r => r.json())
                .then(spots => {
                    spots.forEach(spot => {
                        if (!spot.lat || !spot.lng) return;

                        const marker = L.marker([spot.lat, spot.lng], {
                            icon: makeIcon(spot.type)
                        }).addTo(map);

                        const typeNames = {
                            'billboard': 'Билборд', 'lightbox': 'Лайтбокс',
                            'led_screen': 'LED экран', 'banner': 'Баннер',
                            'transport': 'Транспорт', 'indoor': 'В помещении',
                            'digital': 'Digital', 'event': 'Event',
                        };

                        marker.bindPopup(`
                        <div class="map-popup">
                            ${spot.photo ? `<img src="${spot.photo}" style="width:100%;height:100px;object-fit:cover;border-radius:6px;margin-bottom:8px">` : ''}
                            <div class="map-popup__title">${spot.title}</div>
                            <div class="map-popup__address">📍 ${spot.address}</div>
                            <div class="map-popup__price">$${parseInt(spot.price)}/мес</div>
                            <a href="${spot.url}" class="map-popup__btn">Подробнее →</a>
                        </div>
                    `, { maxWidth: 220 });
                    });
                });
        });
    </script>

@endsection
