<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class OrderController extends Controller
{
    public function create($item_id)
    {
        $user = Auth::user();
        $profile = $user->profile;
        $item = Item::findOrFail($item_id);

        // 住所変更からのリダイレクト対応 //
        $address = (object)session('custom_address', [
            'postal_code' => $user->profile->postal_code,
            'address'     => $user->profile->address,
            'building'    => $user->profile->building,
        ]);

        return view('orders.create', compact('item', 'profile', 'address', 'item_id'));
    }

    public function edit($item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        return view('orders.edit-address',compact('user','item'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        $address = $request->only(['postal_code', 'address', 'building']);
        session(['custom_address' => $address]);

        return redirect()->route('order.create', ['item_id' => $item_id]);
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',

            'metadata' => [
                'item_id'        => $item->id,
                'postal_code'    => $request->postal_code,
                'address'        => $request->address,
                'building'       => $request->building,
                'payment_method' => $request->payment_method,
            ],

            'success_url' => route('order.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => url('/'),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::retrieve($request->session_id);

        $data = $session->metadata;

        Order::create([
            'user_id'        => auth()->id(),
            'item_id'        => $data->item_id,
            'postal_code'    => $data->postal_code,
            'address'        => $data->address,
            'building'       => $data->building,
            'payment_method' => $data->payment_method,
        ]);

        return redirect()->route('item.index')->with('message', '商品の購入が完了しました');
    }
}
