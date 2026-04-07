<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Request $request, $item_id)
    {
        Auth::user()->favorites()->syncWithoutDetaching($item_id);

        return redirect('/item/' . $item_id);
    }

    public function destroy(Request $request, $item_id)
    {
        Auth::user()->favorites()->detach($item_id);

        return redirect('/item/' . $item_id);
    }
}
