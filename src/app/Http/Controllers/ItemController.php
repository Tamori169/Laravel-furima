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
        // 1. 現在の状態（ユーザーID、検索ワード、選択中のタブ）を取得
        $myId = Auth::id();
        $keyword = $request->query('keyword');
        $tab = $request->query('tab'); // 'mylist' または null

        // 2. クエリの土台を作る（Eager LoadingでN+1問題を防止）
        $query = Item::with('order');

        // 3. 【共通】検索ワードがあれば「商品名」で部分一致検索
        // おすすめタブでもマイリストタブでも、この絞り込みは常に有効になります
        $query->when($keyword, function ($q) use ($keyword) {
            return $q->where('name', 'like', '%' . $keyword . '%');
        });

        // 4. 【分岐】タブに応じた絞り込み
        if ($tab === 'mylist') {
            // --- マイリストタブの場合 ---
            if ($myId) {
                // ログイン中：自分がお気に入り（favorites）した商品だけに絞り込む
                $query->whereHas('favorites', function ($q) use ($myId) {
                    $q->where('user_id', $myId);
                });
            } else {
                // 未ログイン：お気に入りは存在しないので、強制的に0件にする
                $query->whereRaw('1 = 0');
            }
        } else {
            // --- おすすめタブ（デフォルト）の場合 ---
            // 自分以外の出品商品を表示（仕様に合わせて変更してください）
            $query->where('user_id', '!=', $myId);
        }

        // 5. 結果を取得
        $items = $query->latest()->get();

        // 6. ビューに現在の状態をすべて渡す（これによって、検索窓やタブのリンクを制御します）
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
        $imagePath = null;

        $file = $request->file('image');
        $fileName = uniqid() . '_' . $file->getClientOriginalName();
        $file->storeAs('images/items', $fileName, 'public');
        $imagePath = '/storage/images/items/' . $fileName;

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
