<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CouponController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
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
    //public//

    //product
    Route::get('/product', [ProductController::class, 'index']);
    Route::get('/product/{product}', [ProductController::class, 'show']);
    //discount
    Route::get('/discount', [DiscountController::class, 'index']);
    Route::get('/discount/{discount}', [DiscountController::class, 'show']);

    Route::middleware('auth:api')->group(function () {
        Route::apiResource('categories', CategoryController::class)->except(['update'])->middleware('auth:api');
        Route::post('categories/{category}', [CategoryController::class, 'update']);

        //Status
        Route::apiResource('status', StatusController::class)->except(['update']);
        Route::post('status/{status}', [StatusController::class, 'update']);

        //Product
        Route::post('product/{product}', [ProductController::class, 'update']);
        Route::post('/product', [ProductController::class, 'store']);
        Route::delete('/product/{product}', [ProductController::class, 'destroy']);
        Route::get('/getDiscountProduct', [ProductController::class, 'getDiscountedPrice']);

        //Coupon
        Route::get('/coupon', [CouponController::class, 'index']);
        Route::post('/coupon', [CouponController::class, 'store']);
        Route::get('/coupon/{coupon}', [CouponController::class, 'show']);
        Route::delete('/coupon/{coupon}', [CouponController::class, 'destroy']);
        Route::post('/coupon/{coupon}', [CouponController::class, 'update']);

        //Address
        Route::apiResource('address', AddressController::class)->except(['update']);
        Route::post('address/{address}', [AddressController::class, 'update']);

        //Discount
        Route::post('discount/{discount}', [DiscountController::class, 'update']);
        Route::post('/discount', [DiscountController::class, 'store']);
        Route::delete('/discount/{discount}', [DiscountController::class, 'destroy']);
    });

});

Route::post('/payment', [ZaloPayController::class, 'payment']);
Route::post('/payment/callback', [ZaloPayController::class, 'callback']);
Route::get('/payment/status/{iddh}', [ZaloPayController::class, 'get_status']);
