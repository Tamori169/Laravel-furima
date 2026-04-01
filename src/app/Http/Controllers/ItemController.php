<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $myId = auth()->id();

        $items = Item::with('order')
            // 1. ログインしている場合のみ、自分が出品したものを除外
            ->when($myId, function ($query) use ($myId) {
                return $query->where('user_id', '!=', $myId);
            })
            // 2. マイリストタブの場合
            ->when($request->query('tab') === 'mylist', function ($query) use ($myId) {
                if ($myId) {
                    // ログイン中：自分がお気に入りした商品に絞り込む
                    return $query->whereHas('favorites', function ($q) use ($myId) {
                        $q->where('user_id', $myId);
                    });
                } else {
                    // 未ログイン：絶対に見つからない条件をあえて入れる（結果を空にする）
                    return $query->whereRaw('1 = 0');
                }
            })
            ->get();

        return view('items.index', compact('items'));
    }
}
