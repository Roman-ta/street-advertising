@extends('layouts.app')
@section('title', 'Регистрация — AdSpot')
@section('content')
    <div class="auth-page">
        <div class="auth-box">
            <h2 class="auth-box__title">Создать аккаунт</h2>
            <p class="auth-box__subtitle">Кто вы на платформе?</p>
            <livewire:auth.register />
        </div>
    </div>
@endsection
