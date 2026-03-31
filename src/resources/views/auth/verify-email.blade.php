@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')

<div class="verify-email">
    <div class="verify-email__content">
        <p class="verify-email__text">
            登録していただいたメールアドレスに認証メールを送付しました。</br>
            メール認証を完了してください。
        </p>
        <a class="verify-email__verify-link" href="http://127.0.0.1:8025/" target="_blank">認証はこちらから</a>
        <form class="verify-email__resend-form" method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="verify-email__resend-button" type="submit">
                認証メールを再送する
            </button>
        </form>
    </div>
</div>
@endsection