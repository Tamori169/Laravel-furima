<?php

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

Route::get('/login', function () {
    return view('auth.login');
})->middleware(['verified']);
