<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Подтвердите email — AdSpot</title>
</head>
<body>

<h2>Подтвердите ваш email</h2>

<p>
    Мы отправили письмо со ссылкой для подтверждения на ваш email.
    Перейдите по ссылке в письме чтобы продолжить.
</p>

@if(session('status') === 'verification-link-sent')
    <p style="color: green;">Письмо отправлено повторно!</p>
@endif

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit">Отправить письмо повторно</button>
</form>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Выйти</button>
</form>

</body>
</html>
