<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot</title>
    @livewireStyles
</head>
<body>

{{-- Модалка оферты — висит на всех страницах для авторизованных --}}
@auth
    <livewire:auth.legal-modal />
@endauth

{{-- Контент --}}
{{ $slot }}

@livewireScripts
</body>
</html>
