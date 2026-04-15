@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/orders/create.css') }}">
@endsection

@section('content')
<div class="purchase-form">
    <form class="form" action="{{ route('order.store', $item->id) }}" method="post">
        @csrf
        <!-- 画面左側 -->
        <div class="form__left-wrapper">
            <div class="form__item-info">
                <!-- 商品画像 -->
                <div class="form__image-wrapper">
                    <img class="form__image" src="{{ asset($item->image) }}"
                    alt="{{ $item->name }}">
                </div>
                <div class="form__info-wrapper">
                    <!-- 商品名 -->
                    <div class="form__name">
                        <h1 class="form__name--text">
                            {{ $item->name }}
                        </h1>
                    </div>
                    <!-- 商品価格 -->
                    <div class="form__price">
                        <p class="form__price-currency">¥</p>
                        <span class="form__price-amount">
                            {{ number_format($item->price) }}
                        </span>
                    </div>
                </div>
            </div>
            <!-- 支払い方法 -->
            <div class="form__payment-method">
                <div class="form__payment-method--label">
                    <h2 class="form__payment-method--text">
                        支払い方法
                    </h2>
                </div>
                <div class="form__payment-method--content">
                    <select class="form__payment-method--select" name="payment_method" id="payment-select">
                        <option class="form__payment-method--option" value="" selected disabled>選択してください</option>
                        <option class="form__payment-method--option" value="コンビニ払い">コンビニ払い</option>
                        <option class="form__payment-method--option" value="カード支払い">カード支払い</option>
                    </select>
                </div>
                <div class="form__error">
                    <span class="form__error-text">
                        @error('payment_method')
                        {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
            <!-- 配送先 -->
            <div class="form__shipping-address--wrapper">
                <div class="form__shipping-address">
                    <div class="form__shipping-address--label">
                        <h2 class="form__shipping-address--text">
                            配送先
                        </h2>
                    </div>
                    <div class="form__shipping-address--content">
                        <span class="form__shipping-address--character">〒</span>
                        <input class="form__shipping-address--postal-code" type="text" name="postal_code"
                        value="{{ $address->postal_code ?? ''}}" readonly>
                        </input>
                    </div>
                    <div class="form__shipping-address--content">
                        <input class="form__shipping-address--address" type="text" name="address"
                        value="{{ $address->address ?? ''}}" readonly>
                        <input class="form__shipping-address--building" type="text" name="building"
                        value="{{ $address->building ?? ''}}" readonly>
                    </div>
                    <div class="form__error">
                        <span class="form__error-text">
                            @error('postal_code')
                            {{ $message }}
                            @enderror
                        </span>
                    </div>
                    <div class="form__error">
                        <span class="form__error-text">
                            @error('address')
                            {{ $message }}
                            @enderror
                        </span>
                    </div>
                </div>
                <!-- 配送先変更 -->
                <div class="update__form">
                    <a class="update__form-link" href="{{ route('order.edit', $item->id) }}">
                        変更する
                    </a>
                </div>
            </div>
        </div>
        <!-- テーブル -->
        <div class="form__right-wrapper">
            <table class="purchase-table">
                <tr class="purchase-table__row">
                    <th class="purchase-table__header">
                        商品代金
                    </th>
                    <td class="purchase-table__price">
                            ¥{{ number_format($item->price) }}
                    </td>
                </tr>
                <tr class="purchase-table__row">
                    <th class="purchase-table__header">
                        支払い方法
                    </th>
                    <td class="purchase-table__payment-method" id="display-payment">
                        ー
                    </td>
                </tr>
            </table>
            <!-- 購入ボタン -->
            <div class="purchase__button">
                <button class="purchase__button-submit" type="submit">
                    購入する
                </button>
            </div>
        </div>
    </form>
</div>

<!-- セレクトボックスの選択に応じた表示変更 -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('payment-select');
        const display = document.getElementById('display-payment');

        select.addEventListener('change', function() {
            display.textContent = select.value;
        });
    });
</script>

@endsection