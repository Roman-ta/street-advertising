@extends('layouts.app')
@section('title', 'Вход — AdSpot')
@section('content')
        <div class="auth-page">
            <div class="auth-box">
                <h2 class="auth-box__title">Войти в аккаунт</h2>
                <livewire:auth.login />
            </div>
        </div>
@endsection
