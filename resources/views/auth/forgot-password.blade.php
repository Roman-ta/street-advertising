<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Восстановление пароля — AdSpot</title>
</head>
<body>
<h2>Восстановление пароля</h2>

@if(session('status'))
    <p style="color:green">{{ session('status') }}</p>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div>
        <label>Email</label><br>
        <input type="email" name="email" required>
        @error('email') <p style="color:red">{{ $message }}</p> @enderror
    </div>
    <br>
    <button type="submit">Отправить ссылку</button>
</form>

<p><a href="{{ route('login') }}">Вернуться ко входу</a></p>
</body>
</html>
