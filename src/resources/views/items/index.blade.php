@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('nav')
<form class="search-form" method="get" action="{{ route('item.index') }}">
    @if(request('tab') === 'mylist')
        <input type="hidden" name="tab" value="mylist">
    @endif
    <input class="search-form__input" type="text" name="keyword"
    placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
</form>
</div>
<div class="nav">
    <!-- ログインしている場合 -->
    @auth
    <form class="logout-button" method="post" action="{{ route('logout') }}">
        @csrf
        <button class="logout__button-submit" type="submit">ログアウト</button>
    </form>
    @endauth
    <!-- ログインしていない場合 -->
    @guest
    <a href="{{ route('login') }}" class="login-link">ログイン</a>
    @endguest
    <form class="mypage-button" method="get" action="{{ route('profile.show') }}">
        @csrf
        <button class="mypage__button-submit" type="submit">マイページ</button>
    </form>
    <form class="sell-button" method="get" action="{{ route('item.create') }}">
        @csrf
        <button class="sell__button-submit" type="submit">出品</button>
    </form>
</div>
@endsection

@section('content')

<div class="item__content">
    <div class="tabs">
        <div class="tab__item">
            <a class="tabs__item-link {{ request('tab') != 'mylist' ? 'is-active' : '' }}" href="{{ route('item.index') }}">
                おすすめ
            </a>
        </div>
        <div class="tab__item">
            <a class="tabs__item-link {{ request('tab') == 'mylist' ? 'is-active' : '' }}" href="{{ route('item.index', ['tab' => 'mylist']) }}">
                マイリスト
            </a>
        </div>
    </div>
    <div class="items">
        @foreach ($items as $item)
        <div class="item__card">
            <img class="item__card-image" src="{{ $item->image }}" alt="{{ $item->name }}">
            <span class="item__card-name">{{ $item->name }}</span>
            @if($item->order)
                <span class="item__card-sold">SOLD</span>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection