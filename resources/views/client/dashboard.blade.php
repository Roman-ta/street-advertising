<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Кабинет Клиена — AdSpot</title>
    @livewireStyles
</head>
<body>

<livewire:auth.legal-modal />

<h1>Кабинет партнёра</h1>
<p>Добро пожаловать, {{ auth()->user()->name }}</p>
<p>Оферта принята: {{ auth()->user()->legal_signed ? 'ДА' : 'НЕТ' }}</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Выйти</button>
</form>

@livewireScripts
</body>
</html>
