@extends('layouts.app')

@section('title', __('messages.seo.home_title'))

@section('content')
    <section class="hero">
        <div class="hero__inner">
            <h1 class="hero__title">{{ __('messages.hero.title_line1') }}<br>{{ __('messages.hero.title_line2') }}</h1>
            <p class="hero__subtitle">
                {{ __('messages.hero.subtitle') }}
            </p>
            <div class="hero__actions">
                <a href="#catalog" class="hero__btn-primary">{{ __('messages.hero.find') }}</a>
                @guest
                    <a href="{{ route('register') }}" class="hero__btn-outline">{{ __('messages.hero.place') }}</a>
                @endguest
            </div>
        </div>
    </section>

    <div id="catalog" class="container">
        <div class="catalog">
            <h2 class="catalog__title">{{ __('messages.catalog.title') }}</h2>
            <livewire:public.spot-catalog />
        </div>
    </div>
@endsection
