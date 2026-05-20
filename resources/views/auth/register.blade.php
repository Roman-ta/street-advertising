<div class="auth-container">

    {{-- Шаг 1: Выбор роли --}}
    <div class="role-select">
        <h2>Создать аккаунт</h2>
        <p class="subtitle">Кто вы на платформе?</p>

        <div class="role-cards">

            <button
                wire:click="selectRole('client')"
                class="role-card {{ $role === 'client' ? 'active' : '' }}"
            >
                <span class="role-icon">🏢</span>
                <span class="role-title">Рекламодатель</span>
                <span class="role-desc">Ищу места для рекламы</span>
            </button>

            <button
                wire:click="selectRole('partner')"
                class="role-card {{ $role === 'partner' ? 'active' : '' }}"
            >
                <span class="role-icon">📍</span>
                <span class="role-title">Владелец площадок</span>
                <span class="role-desc">Сдаю места в аренду</span>
            </button>

        </div>

        @error('role')
        <p class="error">{{ $message }}</p>
        @enderror
    </div>

    {{-- Форма --}}
    <form wire:submit="submit">

        <div class="field">
            <label>Имя / Название компании</label>
            <input
                type="text"
                wire:model.live="name"
                placeholder="SRL Compania Mea"
            >
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label>Email</label>
            <input
                type="email"
                wire:model.live="email"
                placeholder="email@company.md"
            >
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label>Пароль</label>
            <input
                type="password"
                wire:model="password"
                placeholder="Минимум 8 символов"
            >
            @error('password') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label>Повторите пароль</label>
            <input
                type="password"
                wire:model="password_confirmation"
                placeholder="Повторите пароль"
            >
        </div>

        <button
            type="submit"
            class="btn-primary"
            {{ empty($role) ? 'disabled' : '' }}
        >
            Создать аккаунт
        </button>

        <p class="login-link">
            Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a>
        </p>

    </form>

</div>
