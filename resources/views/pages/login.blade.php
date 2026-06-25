@extends('layouts.app')
@section('title', 'Вход — AdSpot')
@section('content')
        <div class="auth-page">
            <div class="auth-box">
                <h2 class="auth-box__title">{{ __('messages.auth.login_title') }}</h2>
                <livewire:auth.login />
            </div>
        </div>
@endsection
