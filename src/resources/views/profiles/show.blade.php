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
                <img class="profile__image-item" src="{{ $profile && $profile->image ? asset($profile->image) : asset('images/profiles/profile-image-gray.jpeg') }}">
            </div>
            <!-- 名前 -->
            <div class="profile__name">
                <h1 class="profile__name-text">{{ $user->name }}</h1>
            </div>
        </div>
        <div class="edit-profile">
            <a class="edit-profile__link " href="{{ route('profile.edit') }}">
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
@endsection