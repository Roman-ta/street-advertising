@extends('layouts.app')

@section('title', 'AdSpot — Рекламные площадки Молдовы')

@section('content')
    <section class="hero">
        <div class="hero__inner">
            <h1 class="hero__title">Ваша реклама.<br>В нужном месте.</h1>
            <p class="hero__subtitle">
                Единая платформа для поиска и бронирования рекламных площадок по всей Молдове
            </p>
            <div class="hero__actions">
                <a href="#catalog" class="hero__btn-primary">Найти площадку →</a>
                @guest
                    <a href="{{ route('register') }}" class="hero__btn-outline">Разместить площадку</a>
                @endguest
            </div>
        </div>
    </section>

    <div id="catalog" class="container">
        <div class="catalog">
            <h2 class="catalog__title">Рекламные площадки</h2>
            <livewire:public.spot-catalog />
        </div>
    </div>
@endsection
