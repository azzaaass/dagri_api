<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/post', [PostController::class, 'index']);

Route::group(['middleware' => ['checkBearer']], function () {
    Route::post('/post', [PostController::class, 'store']);
});