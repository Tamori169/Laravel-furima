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
                    <img class="form__image" src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
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
                    @php
                    $selectedPayment = old('payment_method', $paymentMethod ?? '');
                    @endphp
                    <select class="form__payment-method--select" name="payment_method" id="payment-select">
                        <option class="form__payment-method--option" value="" disabled {{ $selectedPayment === '' ? 'selected' : '' }}>選択してください</option>
                        <option class="form__payment-method--option" value="コンビニ払い" {{ $selectedPayment === 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                        <option class="form__payment-method--option" value="カード支払い" {{ $selectedPayment === 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
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
                    <div class="form__shipping-address--content-first">
                        <span class="form__shipping-address--character">〒</span>
                        <input class="form__shipping-address--postal-code" type="text" name="postal_code"
                            value="{{ $address->postal_code ?? ''}}" readonly>
                    </div>
                    <div class="form__shipping-address--content-second">
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
                <div class="shipping-address__edit">
                    <button class="shipping-address__edit-button" formaction="{{ route('order.edit', $item->id) }}" type="submit">
                        変更する
                    </button>
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
                        {{ $selectedPayment ?: 'ー' }}
                    </td>
                </tr>
            </table>
            <!-- 購入ボタン -->
            <div class="purchase__button">
                @if($item->is_owner)
                <button class="purchase__button--owner" disabled>
                    出品者のため購入不可
                </button>
                @elseif($item->is_sold)
                <button class="purchase__button--sold" disabled>
                    売り切れ
                </button>
                @else
                <button class="purchase__button-submit" formaction="{{ route('order.store', $item->id) }}"
                    type="submit" formtarget="_blank">
                    購入する
                </button>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- セレクトボックスの選択に応じた表示変更 -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('payment-select');
        const display = document.getElementById('display-payment');

        const syncPaymentMethod = function() {
            display.textContent = select.value || 'ー';
        };

        syncPaymentMethod();
        select.addEventListener('change', syncPaymentMethod);
    });
</script>

@endsection