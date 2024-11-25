<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/user/{id}', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', [HomeController::class, 'index'])
    ->name('home');
Route::get('user/{id}/messages', [HomeController::class, 'messages'])
    ->name('messages');
Route::post('user/{id}/message', [HomeController::class, 'message'])
    ->name('message');
Route::get('/logout', [HomeController::class, 'logout']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/current/user', [UserController::class, 'currentUserId']);
