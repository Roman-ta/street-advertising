<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AdSpot — Рекламные площадки Молдовы')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @livewireStyles
</head>
<body>

@auth
    <livewire:auth.legal-modal />
@endauth

@include('layouts.header')

<main>
    @yield('content')
</main>

@include('layouts.footer')

@livewireScripts
</body>
</html>
