@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')

<div class="items__content">
    <!-- 購入完了後のメッセージ -->
    @if (session('message'))
        <span id="flash-message" class="flash-message">
            {{ session('message') }}
        </span>
    @endif
    <!-- タブ -->
    <div class="tabs">
        <div class="tab__item">
            <a class="tab__item-link {{ request('tab') != 'mylist' ? 'is-active' : '' }}"
            href="{{ route('item.index', ['keyword' => request('keyword')]) }}">
                おすすめ
            </a>
        </div>
        <div class="tab__item">
            <a class="tab__item-link {{ request('tab') == 'mylist' ? 'is-active' : '' }}"
            href="{{ route('item.index', ['tab' => 'mylist','keyword' => request('keyword')]) }}">
                マイリスト
            </a>
        </div>
    </div>
    <!-- 商品一覧 -->
    <div class="items-list">
        @foreach ($items as $item)
        <div class="item-card">
            <a class="item-card__link" href="{{ route('item.show', $item->id) }}">
                <div class="item-card__image-wrapper">
                    <img class="item-card__image" src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                    @if($item->order)
                    <div class="item-card__sold">
                        <span class="item-card__sold-text">
                            Sold
                        </span>
                    </div>
                    @endif
                </div>
                <div class="item-card__name">
                    <span class="item-card__name-text">
                        {{ $item->name }}
                    </span>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const message = document.getElementById('flash-message');

    if (message) {
        setTimeout(() => {
            message.classList.add('fade-out');

            // 完全に消す（DOMから削除）
            setTimeout(() => {
                message.remove();
            }, 500); // CSSのtransition時間と合わせる
        }, 5000); // 5秒後
    }
});
</script>

@endsection