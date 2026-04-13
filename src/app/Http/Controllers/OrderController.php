<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function store(PurchaseRequest $request)
    {
        $user_id = Auth::id();
        Order::create([
            'user_id'        => $user_id,
            'item_id'        => $request->item_id,
            'postal_code'    => $request->postal_code,
            'address'        => $request->address,
            'building'       => $request->building,
            'payment_method' => $request->payment_method,
        ]);

        return redirect()->route('item.index')->with('message', '商品の購入が完了しました');
    }
}
