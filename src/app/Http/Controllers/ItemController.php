<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellRequest;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        $myId = Auth::id();
        $keyword = $request->query('keyword');
        $tab = $request->query('tab');

        $query = Item::with('order');

        $query->when($keyword, function ($q) use ($keyword) {
            return $q->where('name', 'like', '%' . $keyword . '%');
        });

        if ($tab === 'mylist') {
            if ($myId) {
                $query->whereHas('favorites', function ($q) use ($myId) {
                    $q->where('user_id', $myId);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        } else {
            $query->where('user_id', '!=', $myId);
        }

        $items = $query->latest()->get();

        return view('items.index', compact('items', 'keyword', 'tab'));
    }

    public function show($item_id)
    {
        $item = Item::with(['comments.user.profile', 'favorites', 'categories', 'condition'])
                ->withCount('comments','favorites')
                ->findOrFail($item_id);

        $isSold = Order::where('item_id', $item_id)->exists();

        return view('items.show',compact('item','isSold'));
    }

    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('items.create',compact('categories','conditions'));
    }

    public function store(SellRequest $request)
    {
        $imagePath = $request->image->store('images/items', 'public');

        $item = Item::create([
            'user_id'        => auth()->id(),
            'name'           => $request->name,
            'description'    => $request->description,
            'image'          => $imagePath,
            'condition_id'   => $request->condition_id,
            'brand'          => $request->brand,
            'price'          => $request->price,
        ]);

        $item->categories()->attach($request->categories);

        return redirect()->route('item.index');
    }
}
