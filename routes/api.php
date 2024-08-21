<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('jwt.auth');


Route::post('/sign-up', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
