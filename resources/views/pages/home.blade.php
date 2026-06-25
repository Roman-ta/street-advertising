@extends('layouts.app')
@section('title', __('messages.seo.home_title'))
@section('content')

    <section class="hero">
        <div class="hero__inner">
            <div class="hero__label">{{ __('messages.hero.label') }}</div>
            <h1 class="hero__title">{{ __('messages.hero.title_line1') }}<br>{{ __('messages.hero.title_line2') }}</h1>
            <p class="hero__subtitle">{{ __('messages.hero.subtitle') }}</p>
            <div class="hero__actions">
                <a href="#catalog" class="hero__btn-primary">{{ __('messages.hero.find') }}</a>
                @guest
                    <a href="{{ route('register') }}" class="hero__btn-outline">{{ __('messages.hero.place') }}</a>
                @endguest
            </div>
            <div class="hero__stats">
                <div class="hero__stat">
                    <strong>60+</strong>
                    <span>{{ __('messages.hero.stats_spots') }}</span>
                </div>
                <div class="hero__stat">
                    <strong>5</strong>
                    <span>{{ __('messages.hero.stats_cities') }}</span>
                </div>
                <div class="hero__stat">
                    <strong>100%</strong>
                    <span>{{ __('messages.hero.stats_online') }}</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Каталог + карта в одном блоке (двухколоночный лэйаут) --}}
    <div id="catalog" class="container" style="padding-top:32px">
        <h2 class="catalog__title">{{ __('messages.catalog.all_title') }}</h2>
        <livewire:public.spot-catalog />
    </div>

@endsection
