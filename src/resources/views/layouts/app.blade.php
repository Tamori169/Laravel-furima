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
        <div class="header__logo">
            <img class="header__logo-image" src="{{ asset('images/logos/COACHTECHヘッダーロゴ.png') }}"
            alt="COACHTECHヘッダーロゴ">
        </div>
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
        <nav class="header__nav">
            @section('nav')
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
            @show
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>