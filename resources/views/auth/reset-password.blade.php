<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новый пароль — AdSpot</title>
</head>
<body>
<h2>Установить новый пароль</h2>

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label>Email</label><br>
        <input type="email" name="email" required>
        @error('email') <p style="color:red">{{ $message }}</p> @enderror
    </div>
    <br>
    <div>
        <label>Новый пароль</label><br>
        <input type="password" name="password" required>
    </div>
    <br>
    <div>
        <label>Повторите пароль</label><br>
        <input type="password" name="password_confirmation" required>
    </div>
    <br>
    <button type="submit">Сохранить пароль</button>
</form>
</body>
</html>
