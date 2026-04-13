@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')

<div class="sell-form">
    <div class="sell-form__heading">
        <h1>
            商品の出品
        </h1>
    </div>
    <form class="form" action="{{ route('item.create') }}" method="POST" enctype="multipart/form-data" novalidate>
        <!-- 商品画像 -->
        <div class="form__group">
            <div class="form__title">
                <p class="form__title-label">
                    商品画像
                </p>
            </div>
            <div class="form__image-content">
                <div class="form__image-preview">
                    <img class="form__image-item" src="" alt="">
                </div>
                <div class="form__image-upload">
                    <label class="form__image-file" for="image-upload">画像を選択する</label>
                    <input id="image-upload" type="file">
                </div>
            </div>
            <div class="form__error">
                <span class="form__error-text">
                    @error('image')
                    {{ $message }}
                    @enderror
                </span>
            </div>
        </div>
        <div class="sell-form__subheading">
            <h2 class="sell-form__subheading-text">
                商品の詳細
            </h2>
        </div>
        <!-- カテゴリー -->
        <div class="form__group">
            <div class="form__title">
                <p class="form__title-label">
                    カテゴリー
                </p>
            </div>
            <div class="form__content">
                <p class="form__content-items">
                    あああ
                </p>
            </div>
            <div class="form__error">
                <span class="form__error-text">
                    @error('category')
                    {{ $message }}
                    @enderror
                </span>
            </div>
        </div>
        <!-- 商品の状態 -->
        <div class="form__group">
            <div class="form__title">
                <p class="form__title-label">
                    商品の状態
                </p>
            </div>
            <div class="form__content">
                <select class="form__content-select" name="condition_id">
                    <option class="form__content--option" value="" selected disabled>選択してください</option>
                </select>
            </div>
            <div class="form__error">
                <span class="form__error-text">
                    @error('condition')
                    {{ $message }}
                    @enderror
                </span>
            </div>
        </div>
        <div class="sell-form__subheading">
            <h2 class="sell-form__subheading-text">
                商品名と説明
            </h2>
        </div>
        <!-- 商品名 -->
        <div class="form__group">
            <div class="form__title">
                <p class="form__title-label">
                    商品名
                </p>
            </div>
            <div class="form__content">
                <input class="form__content-input" type="text" name="name">
            </div>
            <div class="form__error">
                <span class="form__error-text">
                    @error('name')
                    {{ $message }}
                    @enderror
                </span>
            </div>
        </div>
        <!-- ブランド名 -->
        <div class="form__group">
            <div class="form__title">
                <p class="form__title-label">
                    ブランド名
                </p>
            </div>
            <div class="form__content">
                <input class="form__content-input" type="text" name="brand">
            </div>
            <div class="form__error">
                <span class="form__error-text">
                    @error('brand')
                    {{ $message }}
                    @enderror
                </span>
            </div>
        </div>
        <!-- 商品の説明 -->
        <div class="form__group">
            <div class="form__title">
                <p class="form__title-label">
                    商品の説明
                </p>
            </div>
            <div class="form__content">
                <textarea class="form__content-textarea" type="text" name="description"></textarea>
            </div>
            <div class="form__error">
                <span class="form__error-text">
                    @error('description')
                    {{ $message }}
                    @enderror
                </span>
            </div>
        </div>
        <!-- 販売価格 -->
        <div class="form__group">
            <div class="form__title">
                <p class="form__title-label">
                    販売価格
                </p>
            </div>
            <div class="form__content">
                <input class="form__content-input" type="text" name="price">
            </div>
            <div class="form__error">
                <span class="form__error-text">
                    @error('price')
                    {{ $message }}
                    @enderror
                </span>
            </div>
        </div>
        <!-- 出品ボタン -->
        <div class="form__button">
            <button class="form__button-submit" type="submit">
                出品する
            </button>
        </div>
    </form>
</div>

@endsection