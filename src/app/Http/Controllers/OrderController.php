<?php

namespace App\Http\Controllers;

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

        return view('orders.create', compact('item', 'profile','item_id'));
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

        return redirect()->route('item.index');
    }
}
