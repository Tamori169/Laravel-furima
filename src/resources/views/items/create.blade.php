@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')

<div class="sell-form">
    <div class="sell-form__heading">
        <h1 class="sell-form__heading-text">
            商品の出品
        </h1>
    </div>
    <form class="form" action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        <!-- 商品画像 -->
        <div class="form__group">
            <div class="form__title">
                <p class="form__title-label">
                    商品画像
                </p>
            </div>
            <div class="form__image-content">
                <img id="preview" class="form__image-preview" src="" alt="preview" hidden>
                <div class="form__image-upload" id="upload-area">
                    <label class="form__image-file" for="image-upload">
                        画像を選択する
                    </label>
                </div>
                <div class="form__image-overlay" id="overlay" hidden>
                    <label for="image-upload" class="form__image-change">
                        変更
                    </label>
                </div>
                <input id="image-upload" type="file" name="image"
                onchange="previewImage(this)">
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
            <div class="form__categories-content">
                @foreach ($categories as $category)
                    <label class="form__category-label">
                        <input class="form__content-checkbox" type="checkbox" name="categories[]"
                        value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                        <span class="form__content-items">
                            {{ $category->name }}
                        </span>
                    </label>
                @endforeach
            </div>
            <div class="form__error">
                <span class="form__error-text">
                    @error('categories')
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
                    <option class="form__content-option" value="" selected disabled>
                        選択してください
                    </option>
                    @foreach ($conditions as $condition)
                        <option value="{{ $condition->id }}"
                        {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                            {{ $condition->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form__error">
                <span class="form__error-text">
                    @error('condition_id')
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
                <input class="form__content-input" type="text" name="name"
                value="{{ old('name') }}">
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
                <input class="form__content-input" type="text" name="brand"
                value="{{ old('brand') }}">
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
                <textarea class="form__content-textarea" type="text" name="description">{{ old('description') }}</textarea>
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
            <div class="form__price">
                <span class="yen-mark">¥</span>
                <input class="form__price-input" type="text" name="price"
                value="{{ old('price') }}">
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

<script>
function previewImage(input) {
    const file = input.files[0];
    const preview = document.getElementById('preview');
    const overlay = document.getElementById('overlay');
    const uploadArea = document.getElementById('upload-area');

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.hidden = false;
            overlay.hidden = false;

            // ▼変更：初期UIを消す
            uploadArea.style.display = 'none';
        }

        reader.readAsDataURL(file);
    }
}
</script>

@endsection