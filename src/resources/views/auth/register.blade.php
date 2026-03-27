@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')

<div class="register-form">
    <div class="register-form__heading">
        <h1>
            会員登録
        </h1>
    </div>
    <form class="form" action="/register" method="POST">
        @csrf
        <!-- ユーザー名 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">
                    ユーザー名
                </span>
            </div>
            <div class="form__group-content">
                <div class="form__item">
                    <input class="form__item-input" type="text" name="name">
                </div>
                <div class="form__error">
                    <span class="form__error-text">
                        エラーメッセージ
                    </span>
                </div>
            </div>
        </div>
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
                        エラーメッセージ
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
                        エラーメッセージ
                    </span>
                </div>
            </div>
        </div>
        <!-- 確認用パスワード -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">
                    確認用パスワード
                </span>
            </div>
            <div class="form__group-content">
                <div class="form__item">
                    <input class="form__item-input" type="password" name="password_confirmation">
                </div>
                <div class="form__error">
                    <span class="form__error-text">
                        エラーメッセージ
                    </span>
                </div>
            </div>
        </div>
        <!-- 登録ボタン -->
        <div class="from__button">
            <button class="form__button-submit" type="submit">
                登録する
            </button>
        </div>
    </form>
    <div class="register-form__login">
        <a class="register-form__login-link" href="{{ route('login') }}">
            ログインはこちら
        </a>
    </div>
</div>
@endsection