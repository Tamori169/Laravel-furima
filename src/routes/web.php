<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
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

// メール認証後にプロフィール設定画面を呼び出す //
// Route::get('/setup-profile',[ProfileController::class,'create']
//     )->middleware(['auth','verified'])
//     ->name('profile.create');

// 未認証ユーザが可能な処理 //
Route::get('/',[ItemController::class,'index'])
    ->name('item.index');
Route::get('/item/{item_id}',[ItemController::class,'show'])
    ->name('item.show');

// Stripe webhook受信用ルート //
Route::post('/stripe/webhook', [OrderController::class, 'webhook'])
    ->name('stripe.webhook');

// 認証ユーザのみ可能な処理 //
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/item/{item_id}/comment',[CommentController::class,'store'])
        ->name('comment.store');
    Route::post('/item/{item_id}/favorite',[FavoriteController::class,'store'])
        ->name('favorite.store');
    Route::delete('/item/{item_id}/favorite',[FavoriteController::class,'destroy'])
        ->name('favorite.destroy');
    Route::get('/purchase/{item_id}',[OrderController::class,'create'])
        ->name('order.create');
    Route::post('/purchase/{item_id}',[OrderController::class,'store'])
        ->name('order.store');
    Route::get('/complete', [OrderController::class, 'complete'])
        ->name('order.complete');
    Route::post('/purchase/address/{item_id}',[OrderController::class,'edit'])
        ->name('order.edit');
    Route::patch('/purchase/address/{item_id}',[OrderController::class,'update'])
        ->name('order.update');
    Route::get('/sell',[ItemController::class,'create'])
        ->name('item.create');
    Route::post('/sell',[ItemController::class,'store'])
        ->name('item.store');
    Route::get('/setup-profile',[ProfileController::class,'create'])
        ->name('profile.create');
    Route::post('/setup-profile',[ProfileController::class,'store'])
        ->name('profile.store');
    Route::get('/mypage',[ProfileController::class,'show'])
        ->name('profile.show');
    Route::get('/mypage/profile',[ProfileController::class,'edit'])
        ->name('profile.edit');
    Route::patch('/mypage/profile',[ProfileController::class,'update'])
        ->name('profile.update');
});
