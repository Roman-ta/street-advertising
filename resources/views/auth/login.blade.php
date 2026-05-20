<div class="auth-container">

    <h2>Войти в аккаунт</h2>

    <form wire:submit="submit">

        <div class="field">
            <label>Email</label>
            <input type="email" wire:model="email" placeholder="email@company.md">
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label>Пароль</label>
            <input type="password" wire:model="password" placeholder="Ваш пароль">
            @error('password') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="field-row">
            <label>
                <input type="checkbox" wire:model="remember">
                Запомнить меня
            </label>
            <a href="{{ route('password.request') }}">Забыли пароль?</a>
        </div>

        <button type="submit" class="btn-primary">
            <span wire:loading.remove>Войти</span>
            <span wire:loading>Входим...</span>
        </button>

        <p class="login-link">
            Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a>
        </p>

    </form>

</div>
