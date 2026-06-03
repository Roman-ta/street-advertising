@extends('layouts.app')
@section('title', 'AdSpot — Рекламные площадки Молдовы')
@section('content')

    <section class="hero">
        <div class="hero__inner">
            <div class="hero__label">🇲🇩 Платформа №1 в Молдове</div>
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
            <div class="hero__stats">
                <div class="hero__stat">
                    <strong>60+</strong>
                    <span>Площадок</span>
                </div>
                <div class="hero__stat">
                    <strong>5</strong>
                    <span>Городов</span>
                </div>
                <div class="hero__stat">
                    <strong>100%</strong>
                    <span>Онлайн</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Карта --}}
    <div class="container" style="padding-top:48px; padding-bottom:16px">
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
            // Хедер тень при скролле
            const header = document.querySelector('.header');
            window.addEventListener('scroll', () => {
                header.classList.toggle('header--scrolled', window.scrollY > 10);
            });

            const map = L.map('main-map').setView([47.0245, 28.8322], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const typeColors = {
                'billboard': '#5B21B6', 'lightbox': '#0D9488',
                'led_screen': '#F59E0B', 'banner': '#EF4444',
                'transport': '#3B82F6', 'indoor': '#8B5CF6',
                'digital': '#06B6D4', 'event': '#EC4899',
            };

            function makeIcon(type) {
                const color = typeColors[type] || '#5B21B6';
                return L.divIcon({
                    className: '',
                    html: `<div style="
                width:36px; height:36px;
                background:${color};
                border-radius:50% 50% 50% 0;
                transform:rotate(-45deg);
                border:3px solid white;
                box-shadow:0 3px 10px rgba(0,0,0,0.3);
            "></div>`,
                    iconSize: [36, 36],
                    iconAnchor: [18, 36],
                    popupAnchor: [0, -36],
                });
            }

            fetch('/api/spots/map')
                .then(r => r.json())
                .then(spots => {
                    spots.forEach(spot => {
                        if (!spot.lat || !spot.lng) return;
                        const marker = L.marker([spot.lat, spot.lng], {
                            icon: makeIcon(spot.type)
                        }).addTo(map);

                        marker.bindPopup(`
                    <div class="map-popup">
                        ${spot.photo ? `<img src="${spot.photo}" style="width:100%;height:110px;object-fit:cover;border-radius:8px;margin-bottom:10px">` : ''}
                        <div class="map-popup__title">${spot.title}</div>
                        <div class="map-popup__address">📍 ${spot.address}</div>
                        <div class="map-popup__price">$${parseInt(spot.price)}<span>/мес</span></div>
                        <a href="${spot.url}" class="map-popup__btn">Подробнее →</a>
                    </div>
                `, { maxWidth: 240 });
                    });
                });
        });
    </script>

@endsection
