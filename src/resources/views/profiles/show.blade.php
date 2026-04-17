@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profiles/show.css') }}">
@endsection

@section('content')

<div class="mypage__content">
    <!-- プロフィール -->
    <div class="profile__content">
        <div class="profile__content-wrapper">
            <!-- プロフィール画像 -->
            <div class="profile__image">
                <img class="profile__image-item"
                src="{{ $profile && $profile->image
                ? asset($profile->image) : asset('images/profiles/Gray__profile-image.jpeg') }}">
            </div>
            <!-- 名前 -->
            <div class="profile__name">
                <h1 class="profile__name-text">{{ $user->name }}</h1>
            </div>
        </div>
        <div class="edit-profile">
            <a class="edit-profile__link "href="{{ route('profile.edit') }}">
                プロフィールを編集
            </a>
        </div>
    </div>
    <!-- タブ -->
    <div class="tabs">
        <div class="tab__item">
            <a class="tab__item-link {{ request('page', 'sell') == 'sell' ? 'is-active' : '' }}"
            href="{{ route('profile.show', ['page' => 'sell']) }}">
                出品した商品
            </a>
        </div>
        <div class="tab__item">
            <a class="tab__item-link {{ request('page') == 'buy' ? 'is-active' : '' }}"
            href="{{ route('profile.show', ['page' => 'buy']) }}">
                購入した商品
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
                        <img class="item-card__image" src="{{ asset($item->image) }}" alt="{{ $item->name }}">
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