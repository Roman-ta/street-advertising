<header class="header">
    <div class="container header__inner">
        <a href="{{ route('home') }}" class="header__logo">AdSpot</a>
        <nav class="header__nav">
            @auth
                <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="header__link">
                    {{ __('messages.nav.cabinet') }}

                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn--outline btn--sm">{{ __('messages.nav.logout') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="header__link"> {{ __('messages.nav.login') }}</a>
                <a href="{{ route('register') }}" class="btn btn--primary btn--sm">{{ __('messages.nav.register') }} </a>
            @endauth

            <a href="{{ route('cart') }}" class="header__cart header__link">
                🛒
                @php $cartCount = count(session()->get('cart', [])); @endphp
                @if($cartCount > 0)
                    <span class="header__cart-badge">{{ $cartCount }}</span>
                @endif
            </a>
                <div class="lang-switcher" x-data="{ open: false }" :class="{ 'lang-switcher--open': open }" @click.outside="open = false">
                    <div class="lang-switcher__trigger" @click="open = !open">
        <span class="lang-switcher__flag">
            @switch(app()->getLocale())
                @case('ru') 🇷🇺 @break
                @case('ro') 🇲🇩 @break
                @case('en') 🇬🇧 @break
            @endswitch
        </span>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                        <span class="lang-switcher__arrow">▾</span>
                    </div>

                    <div class="lang-switcher__dropdown" x-show="open" x-cloak style="display:none">
                        <a href="{{ route('lang.switch', 'ru') }}" class="lang-switcher__option {{ app()->getLocale() === 'ru' ? 'lang-switcher__option--active' : '' }}">
                            🇷🇺 Русский
                        </a>
                        <a href="{{ route('lang.switch', 'ro') }}" class="lang-switcher__option {{ app()->getLocale() === 'ro' ? 'lang-switcher__option--active' : '' }}">
                            🇲🇩 Română
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" class="lang-switcher__option {{ app()->getLocale() === 'en' ? 'lang-switcher__option--active' : '' }}">
                            en English
                        </a>
                    </div>
                </div>
        </nav>

    </div>
</header>
