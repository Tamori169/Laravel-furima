@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')

<div class="item-detail">
    <div class="item-detail__inner">
        <!-- 商品画像 -->
        <div class="item-detail__image-wrapper">
            <img class="item-detail__image" src="{{ Storage::url($item->image) }}"
                alt="{{ $item->name }}">
        </div>
        <div class="item-detail__info-wrapper">
            <!-- 商品名 -->
            <div class="item-detail__name">
                <h1 class="item-detail__name-text">
                    {{ $item->name }}
                </h1>
            </div>
            <!-- ブランド名 -->
            <div class="item-detail__brand">
                <p class="item-detail__brand-text">
                    {{ $item->brand }}
                </p>
            </div>
            <!-- 商品価格 -->
            <div class="item-detail__price">
                <p class="item-detail__price-currency">¥</p>
                <span class="item-detail__price-amount">
                    {{ number_format($item->price) }}
                </span>
                <p class="item-detail__price-tax">(税込)</p>
            </div>
            <!-- いいね！とコメント数 -->
            <div class="item-detail__actions">
                <div class="action__favorite">
                    @if(Auth::check() && $item->favorites->contains(Auth::id()))
                    <form action="{{ route('favorite.destroy', $item->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none;">
                            <img class="action__favorite-icon" src="{{ asset('images/logos/favorite-logo-pink.png') }}" alt="いいね解除">
                        </button>
                    </form>
                    @else
                    <form action="{{ route('favorite.store', $item->id) }}" method="post">
                        @csrf
                        <button type="submit" style="background: none; border: none;">
                            <img class="action__favorite-icon" src="{{ asset('images/logos/favorite-logo-default.png') }}" alt="いいね追加">
                        </button>
                    </form>
                    @endif
                    <p class="action__favorites-count">
                        {{ $item->favorites_count ?? 0 }}
                    </p>
                </div>
                <div class="action__comment">
                    <img class="action__comment-icon"
                        src="{{ asset('images/logos/comment-logo.png')}}" alt="コメント">
                    <p class="action__comments-count">
                        {{ $item->comments_count ?? 0 }}
                    </p>
                </div>
            </div>
            <!-- 購入手続き -->
            <div class="purchase__button">
                @if($isSold)
                <button class="sold__button" disabled>
                    売り切れ
                </button>
                @else
                <a class="purchase__link" href="{{ route('order.create', $item->id) }}">
                    購入手続きへ
                </a>
                @endif
            </div>
            <!-- 商品説明 -->
            <div class="item-detail__description">
                <div class="item-detail__description-title">
                    <h2 class="item-detail__description-heading">
                        商品説明
                    </h2>
                </div>
                <div class="item-detail__description-content">
                    <p class="item-detail__description-text">
                        {{ $item->description }}
                    </p>
                </div>
            </div>
            <!-- 商品の情報 -->
            <div class="item-detail__specs">
                <div class="item-detail__specs-title">
                    <h2 class="item-detail__specs-heading">
                        商品の情報
                    </h2>
                </div>
                <!-- カテゴリー -->
                <div class="item-detail__categories">
                    <p class="item-detail__categories-label">
                        カテゴリー
                    </p>
                    <div class="item-detail__categories-wrapper">
                        @foreach($item->categories as $category)
                        <p class="item-detail__categories-content">
                            {{ $category->name }}
                        </p>
                        @endforeach
                    </div>
                </div>
                <!-- コンディション -->
                <div class="item-detail__condition">
                    <p class="item-detail__condition-label">
                        商品の状態
                    </p>
                    <p class="item-detail__condition-content">
                        {{ $item->condition->name }}
                    </p>
                </div>
            </div>
            <!-- コメント -->
            <div class="item-detail__comments">
                <div class="item-detail__comments-title">
                    <h2 class="item-detail__comments-heading">
                        コメント({{ $item->comments_count ?? 0 }})
                    </h2>
                </div>
                <!-- コメント一覧 -->
                <div class="item-detail__comments-list">
                    @foreach($item->comments as $comment)
                    <div class="comment__group">
                        <div class="comment__item-wrapper">
                            <img class="comment__user-image" src="{{ Storage::url($comment->user->profile->image) }}" alt="アイコン">
                            <p class="comment__user-name">
                                {{ $comment->user->name }}
                            </p>
                        </div>
                        <div class="comment__content">
                            <p class="comment__text">
                                {{ $comment->comment }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- コメント投稿フォーム -->
                <form class="comment__form" action="{{ route('comment.store', $item->id) }}" method="post">
                    @csrf
                    <div class="comment__form-group">
                        <span class="comment__form-label">商品へのコメント</span>
                        <textarea class="comment__form-textarea" name="comment">{{ old('comment') }}</textarea>
                    </div>
                    <div class="form__error">
                        <span class="form__error-text">
                            @error('comment')
                            {{ $message }}
                            @enderror
                        </span>
                    </div>
                    <div class="comment__form-button">
                        <button class="comment__form-submit" type="submit">
                            コメントを送信する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection