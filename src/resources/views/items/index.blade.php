@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('nav')
<div class="nav">
    <form class="logout-button" method="post" action="{{ url('/logout') }}">
        @csrf
        <button class="logout__button-submit" type="submit">ログアウト</button>
    </form>
    <form class="mypage-button" method="get" action="{{ url('/mypage') }}">
        @csrf
        <button class="mypage__button-submit" type="submit">マイページ</button>
    </form>
    <form class="sell-button" method="get" action="{{ url('/sell') }}">
        @csrf
        <button class="sell__button-submit" type="submit">出品</button>
    </form>
</div>
@endsection

@section('content')

<div class="item__content">
    <div class="tabs">
        <div class="tab__item">
            <a class="tabs__item-link {{ request('tab') != 'mylist' ? 'is-active' : '' }}" href="{{ url('/') }}">
                おすすめ
            </a>
        </div>
        <div class="tab__item">
            <a class="tabs__item-link {{ request('tab') != 'mylist' ? 'is-active' : '' }}" href="{{ url('/?tab=mylist') }}">
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