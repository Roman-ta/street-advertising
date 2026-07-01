@extends('layouts.app')
@section('title', 'Ошибка сервера — AdSpot')
@section('content')
    <div class="auth-page">
        <div class="auth-box" style="text-align:center">
            <div style="font-size:64px; margin-bottom:16px">😔</div>
            <h2 class="auth-box__title">Что-то пошло не так</h2>
            <p class="auth-box__subtitle">
                Мы уже работаем над исправлением. Попробуйте вернуться на главную.
            </p>
            <a href="{{ route('home') }}" class="btn btn--primary btn--lg" style="margin-top:16px">
                На главную
            </a>
        </div>
    </div>
@endsection
