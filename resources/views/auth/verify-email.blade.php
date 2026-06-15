@extends('layouts.app')
@section('title', 'Подтвердите email — AdSpot')
    @section('content')
        <div class="auth-page">
            <div class="auth-box" style="text-align:center">

                <div style="font-size:64px; margin-bottom:16px">📧</div>

                <h2 class="auth-box__title">Подтвердите ваш email</h2>
                <p class="auth-box__subtitle">
                    Мы отправили письмо со ссылкой для подтверждения на ваш email.
                    Перейдите по ссылке в письме чтобы продолжить.
                </p>

                @if(session('status') === 'verification-link-sent')
                    <div class="alert alert--success" style="margin-bottom:20px">
                        Письмо отправлено повторно!
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}" style="margin-bottom:12px">
                    @csrf
                    <button type="submit" class="btn btn--primary btn--full btn--lg">
                        Отправить письмо повторно
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn--outline btn--full">
                        Выйти
                    </button>
                </form>

                <p style="margin-top:20px; font-size:13px; color:#9ca3af">
                    Не получили письмо? Проверьте папку "Спам"
                </p>

            </div>
        </div>
    @endsection
