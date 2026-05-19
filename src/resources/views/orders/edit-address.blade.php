@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/orders/edit-address.css') }}">
@endsection

@section('content')

<div class="edit-form">
    <div class="edit-form__heading">
        <h1 class="edit-form__heading-text">
            住所の変更
        </h1>
    </div>
    <form class="form" action="{{ route('order.update', ['item_id' => $item->id, 'payment_method' => $paymentMethod ?? null]) }}" method="POST" novalidate>
        @csrf
        @method('PATCH')
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
                    value="{{ old('postal_code') }}">
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
                    value="{{ old('address') }}">
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
                    value="{{ old('building') }}">
                </div>
            </div>
        </div>
        <!-- 更新ボタン -->
        <div class="form__button">
            <button class="form__button-submit" type="submit">
                更新する
            </button>
        </div>
    </form>
</div>

@endsection
