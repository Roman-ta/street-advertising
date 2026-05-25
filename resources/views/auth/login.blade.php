<div>
    <form wire:submit="submit">
        <div class="form__group">
            <label class="form__label">Email</label>
            <input type="email" wire:model="email" placeholder="email@company.md" class="form__input">
            @error('email') <span class="form__error">{{ $message }}</span> @enderror
        </div>

        <div class="form__group">
            <label class="form__label">Пароль</label>
            <input type="password" wire:model="password" placeholder="Ваш пароль" class="form__input">
            @error('password') <span class="form__error">{{ $message }}</span> @enderror
        </div>

        <div class="field-row">
            <label>
                <input type="checkbox" wire:model="remember">
                Запомнить меня
            </label>
            <a href="{{ route('password.request') }}" class="auth-box__forgot">Забыли пароль?</a>
        </div>

        <button type="submit" class="btn btn--primary btn--full btn--lg">
            <span wire:loading.remove>Войти</span>
            <span wire:loading>Входим...</span>
        </button>
    </form>

    <div class="auth-box__footer">
        Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a>
    </div>
</div>
