<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create($item_id)
    {
        $profile = Auth::user()->load('profile');
        $item = Item::findOrFail($item_id);

        return view('orders.create', compact('item', 'item_id'));
    }
}
