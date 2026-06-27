@extends('layouts.app')
@section('title', __('messages.auth.login_title') . ' — AdSpot')
@section('content')
    <div class="auth-page">
        <div class="auth-box">
            <div class="auth-box__logo"><span>AdSpot</span></div>
            <h2 class="auth-box__title">{{ __('messages.auth.welcome') }}</h2>
            <p class="auth-box__subtitle">{{ __('messages.auth.welcome_subtitle') }}</p>
            <livewire:auth.login />
        </div>
    </div>
@endsection
