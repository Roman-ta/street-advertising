@extends('layouts.app')
@section('title', __('messages.auth.forgot_title') . ' — AdSpot')
@section('content')
    <div class="auth-page">
        <div class="auth-box">

            <div class="auth-box__logo"><span>AdSpot</span></div>

            <h2 class="auth-box__title">{{ __('messages.auth.forgot_title') }}</h2>
            <p class="auth-box__subtitle">{{ __('messages.auth.forgot_subtitle') }}</p>

            @if(session('status'))
                <div class="alert alert--success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form__group">
                    <label class="form__label">{{ __('messages.auth.email_label') }}</label>
                    <input type="email" name="email" required class="form__input">
                    @error('email') <span class="form__error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn--primary btn--full btn--lg">
                    {{ __('messages.auth.send_link_btn') }}
                </button>
            </form>

            <div class="auth-box__footer">
                <a href="{{ route('login') }}">{{ __('messages.auth.back_to_login') }}</a>
            </div>

        </div>
    </div>
@endsection
