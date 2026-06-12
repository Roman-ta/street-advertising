<header class="header">
    <div class="container header__inner">
        <a href="{{ route('home') }}" class="header__logo">AdSpot</a>
        <nav class="header__nav">
            @auth
                <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="header__link">
                    Личный кабинет
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn--outline btn--sm">Выйти</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="header__link">Войти</a>
                <a href="{{ route('register') }}" class="btn btn--primary btn--sm">Регистрация</a>
            @endauth

            <a href="{{ route('cart') }}" class="header__cart header__link">
                🛒
                @php $cartCount = count(session()->get('cart', [])); @endphp
                @if($cartCount > 0)
                    <span class="header__cart-badge">{{ $cartCount }}</span>
                @endif
            </a>
        </nav>
    </div>
</header>
