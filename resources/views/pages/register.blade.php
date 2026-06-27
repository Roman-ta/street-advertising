@extends('layouts.app')
@section('title', __('messages.auth.register_title') . ' — AdSpot')
@section('content')
    <div class="auth-page">
        <div class="auth-box">
            <div class="auth-box__logo"><span>AdSpot</span></div>
            <h2 class="auth-box__title">{{ __('messages.auth.register_title') }}</h2>
            <p class="auth-box__subtitle">{{ __('messages.auth.register_subtitle') }}</p>
            <livewire:auth.register />
        </div>
    </div>
@endsection
