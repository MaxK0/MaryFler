<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="Интернет-магазин 'МэриФлер' Цветы & Праздник - Уфа">
    <link rel="stylesheet" href="{{ asset('css/style.css') . '?v=3' }}">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="icon" href="{{ asset('img/logo.png') }}">
    <script src="https://unpkg.com/imask"></script>
</head>
<body>
<div id="site">
    <header class="header">
        <div class="container header__container">
            <nav class="header__nav">
                <div class="header__title">
                    <a href="{{ route('home') }}" class="link-nav link-title">
                        <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}">
                    </a>
                </div>
                <i id="header__menu" class="fa-solid fa-bars"></i>
                <ul class="header__ul">
                    <li>
                        <a href="{{ route('cart.index') }}" class="link-nav">
                            <i class="fa-solid fa-cart-shopping"></i>
                            @if(session('cart'))
                                <span class="cart-count">{{ count(session('cart')) }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}"
                           class="link-nav {{ request()->routeIs('home') ? 'active' : '' }}">
                            Главная
                        </a>
                    </li>
                    <li class="categories-dropdown">
                        <div class="categories-dropdown-toggle link-nav">
                            Категории <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="categories-dropdown-menu">
                            @foreach(\App\Models\Category::where('is_active', true)->get() as $category)
                                <a href="{{ route('home', ['category' => $category->id]) }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </li>
                    <li>
                        <a href="{{ route('about') }}"
                           class="link-nav {{ request()->routeIs('about') ? 'active' : '' }}">
                            О нас
                        </a>
                    </li>
                    @auth
                        <li>
                            <a href="{{ route('profile.index') }}"
                               class="link-nav {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                {{ auth()->user()->name }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}"
                               class="link-nav {{ request()->routeIs('logout') ? 'active' : '' }}">
                                Выйти
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}"
                               class="link-nav {{ request()->routeIs('login') ? 'active' : '' }}">
                                Войти
                            </a>
                        </li>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container footer__container">
            <div class="footer__blocks">
                <div class="footer__info">
                    <div class="footer__info__block">
                        <i class="fa-solid fa-house"></i>
                        <p>Первомайская, 2/30</p>
                    </div>
                    <div class="footer__info__block">
                        <i class="fa-solid fa-clock"></i>
                        <p>10.00 - 21.00 ч.</p>
                    </div>
                    <div class="footer__info__block">
                        <i class="fa-solid fa-phone"></i>
                        <a href="tel:+79872425251" class="link-nav">+7 (987) 258 72-67</a>
                    </div>
                </div>
                <div class="footer__social">
                    <a href="https://vk.ru/maryfleur_ufa" class="link-nav">
                        <i class="fa-brands fa-vk"></i>
                    </a>
                    <a href="https://t.me/MaryFleurUfa" class="link-nav">
                        <i class="fa-brands fa-telegram"></i>
                    </a>
                </div>
            </div>
            <p class="footer__copyright">© MaryFler Company</p>
        </div>
    </footer>
</div>
{{--<div id="back-to-top" class="back-to-top">--}}
{{--    <i class="fa-solid fa-arrow-up"></i>--}}
{{--</div>--}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
