@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')

<div class="items__content">
    <!-- タブ -->
    <div class="tabs">
        <div class="tab__item">
            <a class="tab__item-link {{ request('tab') != 'mylist' ? 'is-active' : '' }}" href="{{ route('item.index') }}">
                おすすめ
            </a>
        </div>
        <div class="tab__item">
            <a class="tab__item-link {{ request('tab') == 'mylist' ? 'is-active' : '' }}" href="{{ route('item.index', ['tab' => 'mylist']) }}">
                マイリスト
            </a>
        </div>
    </div>
    <!-- 商品一覧 -->
    <div class="items-list">
        @foreach ($items as $item)
        <div class="item-card">
            <form class="item-card__name" action="{{ route('item.show', $item->id) }}" method="get">
                <button class="item-card__button-submit" type="submit">
                    <div class="item-card__image-wrapper">
                        <img class="item-card__image" src="{{ $item->image }}" alt="{{ $item->name }}">
                        @if($item->order)
                        <div class="item-card__sold">
                            <span class="item-card__sold-text">SOLD</span>
                        </div>
                        @endif
                    </div>
                    {{ $item->name }}
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endsection