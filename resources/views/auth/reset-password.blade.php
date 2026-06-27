@extends('layouts.app')
@section('title', __('messages.auth.reset_title') . ' — AdSpot')
@section('content')
    <div class="auth-page">
        <div class="auth-box">

            <div class="auth-box__logo"><span>AdSpot</span></div>

            <h2 class="auth-box__title">{{ __('messages.auth.reset_title') }}</h2>
            <p class="auth-box__subtitle">{{ __('messages.auth.reset_subtitle') }}</p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form__group">
                    <label class="form__label">{{ __('messages.auth.email_label') }}</label>
                    <input type="email" name="email" required class="form__input">
                    @error('email') <span class="form__error">{{ $message }}</span> @enderror
                </div>

                <div class="form__group">
                    <label class="form__label">{{ __('messages.auth.new_password_label') }}</label>
                    <input type="password" name="password" required class="form__input">
                </div>

                <div class="form__group">
                    <label class="form__label">{{ __('messages.auth.password_confirm_label') }}</label>
                    <input type="password" name="password_confirmation" required class="form__input">
                </div>

                <button type="submit" class="btn btn--primary btn--full btn--lg">
                    {{ __('messages.auth.save_password_btn') }}
                </button>
            </form>

        </div>
    </div>
@endsection
