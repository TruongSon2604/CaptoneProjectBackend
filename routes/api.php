<?php

use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginGooleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ZaloPayController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

//google
// Route::group([
//     'middleware' => 'api',
// ], function () {
//     Route::get('/auth/google/redirect', [LoginGooleController::class, 'redirectToGoogle']);
//     Route::get('/auth/google/callback', [LoginGooleController::class, 'handleGoogleCallback']);
// });


Route::group([
    'middleware' => 'api',
], function () {
    Route::apiResource('categories', CategoryController::class)->except(['update'])->middleware('auth:api');
    Route::post('categories/{category}', [CategoryController::class, 'update'])->middleware('auth:api');

    //Status
    Route::apiResource('status', StatusController::class)->except(['update'])->middleware('auth:api');
    Route::post('status/{status}', [StatusController::class, 'update'])->middleware('auth:api');

    //Product
    Route::apiResource('product', ProductController::class)->except(['update'])->middleware('auth:api');
    Route::post('product/{product}', [ProductController::class, 'update'])->middleware('auth:api');

    //Address
    Route::apiResource('address', AddressController::class)->except(['update'])->middleware('auth:api');
    Route::post('address/{address}', [AddressController::class, 'update'])->middleware('auth:api');
});

Route::post('/payment', [ZaloPayController::class, 'payment']);
Route::post('/payment/callback', [ZaloPayController::class, 'callback']);
Route::get('/payment/status/{iddh}', [ZaloPayController::class, 'get_status']);
