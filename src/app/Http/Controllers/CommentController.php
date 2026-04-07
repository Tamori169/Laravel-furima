<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        return redirect('/item/' . $item_id);
    }
}
