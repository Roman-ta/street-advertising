<div>
    <div class="role-cards">
        <button
            type="button"
            wire:click="selectRole('client')"
            class="role-card {{ $role === 'client' ? 'role-card--active' : '' }}"
        >
            <span class="role-card__icon">🏢</span>
            <span class="role-card__title">{{ __('messages.auth.role_client_title') }}</span>
            <span class="role-card__desc">{{ __('messages.auth.role_client_desc') }}</span>
        </button>

        <button
            type="button"
            wire:click="selectRole('partner')"
            class="role-card {{ $role === 'partner' ? 'role-card--active' : '' }}"
        >
            <span class="role-card__icon">📍</span>
            <span class="role-card__title">{{ __('messages.auth.role_partner_title') }}</span>
            <span class="role-card__desc">{{ __('messages.auth.role_partner_desc') }}</span>
        </button>
    </div>

    @error('role')
    <div class="alert alert--error" style="margin-bottom:16px">{{ $message }}</div>
    @enderror

    <form wire:submit="submit">
        <div class="form__group">
            <label class="form__label">{{ __('messages.auth.name_label') }}</label>
            <input
                type="text"
                wire:model.live="name"
                placeholder="{{ __('messages.auth.name_placeholder') }}"
                class="form__input"
            >
            @error('name') <span class="form__error">{{ $message }}</span> @enderror
        </div>

        <div class="form__group">
            <label class="form__label">{{ __('messages.auth.email_label') }}</label>
            <input
                type="email"
                wire:model.live="email"
                placeholder="{{ __('messages.auth.email_placeholder') }}"
                class="form__input"
            >
            @error('email') <span class="form__error">{{ $message }}</span> @enderror
        </div>

        <div class="form__group">
            <label class="form__label">{{ __('messages.auth.password_label') }}</label>
            <input
                type="password"
                wire:model="password"
                placeholder="{{ __('messages.auth.password_placeholder') }}"
                class="form__input"
            >
            @error('password') <span class="form__error">{{ $message }}</span> @enderror
        </div>

        <div class="form__group">
            <label class="form__label">{{ __('messages.auth.password_confirm_label') }}</label>
            <input
                type="password"
                wire:model="password_confirmation"
                placeholder="{{ __('messages.auth.password_confirm_ph') }}"
                class="form__input"
            >
        </div>

        <button
            type="submit"
            class="btn btn--full btn--lg {{ $role ? 'btn--primary' : '' }}"
            style="{{ !$role ? 'background:#e5e7eb; color:#9ca3af; cursor:not-allowed' : '' }}"
        >
            <span wire:loading.remove>
                {{ $role === 'partner' ? __('messages.auth.become_partner_btn') : __('messages.auth.create_account_btn') }}
            </span>
            <span wire:loading>{{ __('messages.auth.creating_account') }}</span>
        </button>
    </form>

    <div class="auth-box__footer">
        {{ __('messages.auth.has_account') }} <a href="{{ route('login') }}">{{ __('messages.auth.login_link') }}</a>
    </div>
</div>
