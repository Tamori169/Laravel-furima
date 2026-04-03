<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/setup-profile', function () {
    return view('profiles.form');
})->middleware(['auth', 'verified']);

Route::get('/',[ItemController::class,'index'])
    ->name('item.index');
Route::get('/item/{item_id}',[ItemController::class,'show'])
    ->name('item.show');

Route::middleware(['auth'])->group(function () {
    Route::post('/item/{item_id}/comment',[CommentController::class,'store'])
    ->name('comment.store');
    Route::get('/purchase/{item_id}',[OrderController::class,'create'])
    ->name('order.create');
    Route::get('/sell',[ItemController::class,'create'])
    ->name('item.create');
    Route::get('/mypage',[ProfileController::class,'show'])
    ->name('profile.show');
});
