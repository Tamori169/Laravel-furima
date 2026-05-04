<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>coachtechフリマ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header">
        <a class="header__logo" href="{{ route('item.index') }}">
            <img class="header__logo-image header__logo-image--desktop" src="{{ asset('images/logos/header-logo-desktop.png') }}"
                alt="COACHTECH">
            <img class="header__logo-image header__logo-image--mobile" src="{{ asset('images/logos/header-logo-mobile.png') }}"
                alt="COACHTECH">
        </a>
        <div class="header__search">
            @section('search')
            <form class="search-form" method="get" action="{{ route('item.index') }}">
                @if(request('tab') === 'mylist')
                <input type="hidden" name="tab" value="mylist">
                @endif
                <input class="search-form__input" type="text" name="keyword"
                    placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
            </form>
            @show
        </div>
        @section('nav')
        <nav class="header__nav">
            <div class="hamburger js-hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="nav-menu js-nav-menu">
                <!-- ログイン/ログアウト -->
                <div class="header__nav-items">
                    <!-- ログインしている場合 -->
                    @auth
                    <form class="logout__button" method="post" action="{{ route('logout') }}">
                        @csrf
                        <button class="logout__button-submit" type="submit">ログアウト</button>
                    </form>
                    @endauth
                    <!-- ログインしていない場合 -->
                    @guest
                    <a href="{{ route('login') }}" class="login__link">ログイン</a>
                    @endguest
                </div>
                <!-- マイページ -->
                <div class="header__nav-items">
                    <a href="{{ route('profile.show') }}" class="mypage__link">マイページ</a>
                </div>
                <!-- 出品 -->
                <div class="header__nav-items">
                    <a href="{{ route('item.create') }}" class="sell__link">出品</a>
                </div>
            </div>
        </nav>
        @show
    </header>
    <main>
        @yield('content')
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.querySelector('.js-hamburger');
            const navMenu = document.querySelector('.js-nav-menu');

            if (hamburger && navMenu) {
                hamburger.addEventListener('click', function() {
                    navMenu.classList.toggle('active');
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>