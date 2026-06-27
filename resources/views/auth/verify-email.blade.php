@extends('layouts.app')
@section('title', __('messages.auth.verify_title') . ' — AdSpot')
@section('content')
    <div class="auth-page">
        <div class="auth-box" style="text-align:center">

            <div style="font-size:64px; margin-bottom:16px">📧</div>

            <h2 class="auth-box__title">{{ __('messages.auth.verify_title') }}</h2>
            <p class="auth-box__subtitle">{{ __('messages.auth.verify_subtitle') }}</p>

            @if(session('status') === 'verification-link-sent')
                <div class="alert alert--success" style="margin-bottom:20px">
                    {{ __('messages.auth.verify_resent') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" style="margin-bottom:12px">
                @csrf
                <button type="submit" class="btn btn--primary btn--full btn--lg">
                    {{ __('messages.auth.verify_resend_btn') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn--outline btn--full">
                    {{ __('messages.auth.verify_logout_btn') }}
                </button>
            </form>

            <p style="margin-top:20px; font-size:13px; color:#9ca3af">
                {{ __('messages.auth.verify_spam_hint') }}
            </p>

        </div>
    </div>
@endsection
