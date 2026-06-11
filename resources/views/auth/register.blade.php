<div>
    <div class="role-cards">
        <button
            type="button"
            wire:click="selectRole('client')"
            class="role-card {{ $role === 'client' ? 'role-card--active' : '' }}"
        >
            <span class="role-card__icon">🏢</span>
            <span class="role-card__title">Рекламодатель</span>
            <span class="role-card__desc">Ищу места для размещения рекламы</span>
        </button>

        <button
            type="button"
            wire:click="selectRole('partner')"
            class="role-card {{ $role === 'partner' ? 'role-card--active' : '' }}"
        >
            <span class="role-card__icon">📍</span>
            <span class="role-card__title">Владелец площадок</span>
            <span class="role-card__desc">Сдаю рекламные места в аренду</span>
        </button>
    </div>

    @error('role')
    <div class="alert alert--error" style="margin-bottom:16px">{{ $message }}</div>
    @enderror

    <form wire:submit="submit">
        <div class="form__group">
            <label class="form__label">Имя / Название компании</label>
            <input
                type="text"
                wire:model.live="name"
                placeholder="SRL Compania Mea"
                class="form__input"
            >
            @error('name') <span class="form__error">{{ $message }}</span> @enderror
        </div>

        <div class="form__group">
            <label class="form__label">Email</label>
            <input
                type="email"
                wire:model.live="email"
                placeholder="email@company.md"
                class="form__input"
            >
            @error('email') <span class="form__error">{{ $message }}</span> @enderror
        </div>

        <div class="form__group">
            <label class="form__label">Пароль</label>
            <input
                type="password"
                wire:model="password"
                placeholder="Минимум 8 символов"
                class="form__input"
            >
            @error('password') <span class="form__error">{{ $message }}</span> @enderror
        </div>

        <div class="form__group">
            <label class="form__label">Повторите пароль</label>
            <input
                type="password"
                wire:model="password_confirmation"
                placeholder="Повторите пароль"
                class="form__input"
            >
        </div>

        <button
            type="submit"
            class="btn btn--full btn--lg {{ $role ? 'btn--primary' : '' }}"
            style="{{ !$role ? 'background:#e5e7eb; color:#9ca3af; cursor:not-allowed' : '' }}"
        >
            <span wire:loading.remove>
                {{ $role === 'partner' ? '🚀 Стать партнёром' : '✨ Создать аккаунт' }}
            </span>
            <span wire:loading>Создаём аккаунт...</span>
        </button>
    </form>

    <div class="auth-box__footer">
        Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a>
    </div>
</div>
