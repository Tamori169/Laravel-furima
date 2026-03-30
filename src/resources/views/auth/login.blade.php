@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')

<div class="login-form">
    <div class="login-form__heading">
        <h1>
            ログイン
        </h1>
    </div>
    <form class="form" action="/login" method="POST">
        @csrf
        <!-- メールアドレス -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">
                    メールアドレス
                </span>
            </div>
            <div class="form__group-content">
                <div class="form__item">
                    <input class="form__item-input" type="email" name="email">
                </div>
                <div class="form__error">
                    <span class="form__error-text">
                        @error('email')
                        {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
        </div>
        <!-- パスワード -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">
                    パスワード
                </span>
            </div>
            <div class="form__group-content">
                <div class="form__item">
                    <input class="form__item-input" type="password" name="password">
                </div>
                <div class="form__error">
                    <span class="form__error-text">
                        @error('password')
                        {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
        </div>
        <!-- ログインボタン -->
        <div class="form__button">
            <button class="form__button-submit" type="submit">
                ログインする
            </button>
        </div>
    </form>
    <div class="login-form__register">
        <a class="login-form__register-link" href="{{ route('register') }}">
            会員登録はこちら
        </a>
    </div>
</div>
@endsection