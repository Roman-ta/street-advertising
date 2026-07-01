@extends('layouts.app')
@section('title', 'Страница не найдена — AdSpot')
@section('content')
    <div class="auth-page">
        <div class="auth-box" style="text-align:center">
            <div style="font-size:64px; margin-bottom:16px">🔍</div>
            <h2 class="auth-box__title">Страница не найдена</h2>
            <p class="auth-box__subtitle">
                Возможно ссылка устарела или страница была удалена.
            </p>
            <a href="{{ route('home') }}" class="btn btn--primary btn--lg" style="margin-top:16px">
                На главную
            </a>
        </div>
    </div>
@endsection
