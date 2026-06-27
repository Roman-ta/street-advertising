<div>
    <form wire:submit="submit">

        <div class="form__group">
            <label class="form__label">{{ __('messages.auth.email_address_label') }}</label>
            <input
                type="email"
                wire:model="email"
                placeholder="{{ __('messages.auth.email_placeholder') }}"
                class="form__input"
                autofocus
            >
            @error('email')
            <span class="form__error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form__group">
            <label class="form__label">{{ __('messages.auth.password_label') }}</label>
            <input
                type="password"
                wire:model="password"
                placeholder="{{ __('messages.auth.login_password_ph') }}"
                class="form__input"
            >
            @error('password')
            <span class="form__error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field-row">
            <label>
                <input type="checkbox" wire:model="remember">
                {{ __('messages.auth.remember_me') }}
            </label>
            <a href="{{ route('password.request') }}" class="auth-box__forgot">
                {{ __('messages.auth.forgot_password') }}
            </a>
        </div>

        <button type="submit" class="btn btn--primary btn--full btn--lg">
            <span wire:loading.remove>{{ __('messages.auth.login_btn') }}</span>
            <span wire:loading>{{ __('messages.auth.logging_in') }}</span>
        </button>

    </form>

    <div class="auth-box__divider">{{ __('messages.auth.or_divider') }}</div>

    <div class="auth-box__footer">
        {{ __('messages.auth.no_account') }} <a href="{{ route('register') }}">{{ __('messages.auth.register_free') }}</a>
    </div>
</div>
