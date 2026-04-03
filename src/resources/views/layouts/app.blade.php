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
        <img class="header-logo" src="{{ asset('images/logos/COACHTECHヘッダーロゴ.png') }}" alt="COACHTECHヘッダーロゴ">
        @if (View::hasSection('nav'))
        <nav class="header-nav">
        @yield('nav')
        </nav>
        @endif
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>