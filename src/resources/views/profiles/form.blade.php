@extends('layouts.app')

<!-- 新規と更新両方に対応 -->
@php
$isUpdate = isset($profile->id);
$route = $isUpdate ? route('profile.update') : route('profile.store');
@endphp

@section('css')
<link rel="stylesheet" href="{{ asset('css/profiles/form.css') }}">
@endsection

@section('content')

<div class="profile-form">
    <div class="profile-form__heading">
        <h1 class="profile-form__heading-text">
            プロフィール設定
        </h1>
    </div>
    <form class="form" action="{{ $route }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @if($isUpdate)
        @method('PATCH')
        @endif
        <!-- 画像アップロード -->
        <div class="form__image">
            <div class="form__image-content">
                <div class="form__image-preview">
                    <img class="form__image--item" id="preview"
                        src="{{ $profile && $profile->image
                    ? asset($profile->image) : asset('images/profiles/profile-image-gray.jpeg') }}">
                </div>
                <div class="form__image-upload">
                    <label class="form__image-file" for="image-upload">
                        画像を選択する
                    </label>
                    <input id="image-upload" type="file" name="image"
                        onchange="previewImage(this)">
                </div>
            </div>
            <div class="form__error-first">
                <span class="form__error-text">
                    @error('image')
                    {{ $message }}
                    @enderror
                </span>
            </div>
        </div>
        <!-- ユーザー名 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">
                    ユーザー名
                </span>
            </div>
            <div class="form__group-content">
                <div class="form__item">
                    <input class="form__item-input" type="text" name="name"
                        value="{{ old('name', $user->name) }}">
                </div>
                <div class="form__error">
                    <span class="form__error-text">
                        @error('name')
                        {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
        </div>
        <!-- 郵便番号 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">
                    郵便番号
                </span>
            </div>
            <div class="form__group-content">
                <div class="form__item">
                    <input class="form__item-input" type="text" name="postal_code"
                        value="{{ old('postal_code', $profile->postal_code) }}">
                </div>
                <div class="form__error">
                    <span class="form__error-text">
                        @error('postal_code')
                        {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
        </div>
        <!-- 住所 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">
                    住所
                </span>
            </div>
            <div class="form__group-content">
                <div class="form__item">
                    <input class="form__item-input" type="text" name="address"
                        value="{{ old('address', $profile->address) }}">
                </div>
                <div class="form__error">
                    <span class="form__error-text">
                        @error('address')
                        {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
        </div>
        <!-- 建物名 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">
                    建物名
                </span>
            </div>
            <div class="form__group-content">
                <div class="form__item">
                    <input class="form__item-input" type="text" name="building"
                        value="{{ old('building', $profile->building) }}">
                </div>
                <div class="form__error">
                    <span class="form__error-text">
                        @error('building')
                        {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
        </div>
        <!-- 登録ボタン -->
        <div class="form__button">
            <button class="form__button-submit" type="submit">
                更新する
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(obj) {
        const fileReader = new FileReader();

        fileReader.onload = function() {
            document.getElementById('preview').src = fileReader.result;
        };

        if (obj.files && obj.files[0]) {
            fileReader.readAsDataURL(obj.files[0]);
        }
    }
</script>
@endpush