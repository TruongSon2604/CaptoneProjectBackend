<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CouponController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\LoginGooleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserCouponController;
use App\Http\Controllers\ZaloPayController;
use App\Http\Controllers\ZaloPayOrderController;
use Illuminate\Support\Facades\Log;

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
    Route::get('/getProductByid/{id}', [ProductController::class, 'getProductByid']);
    //discount
    Route::get('/discount', [DiscountController::class, 'index']);
    Route::get('/discount/{discount}', [DiscountController::class, 'show']);
    //category
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{categories}', [CategoryController::class, 'show']);
    Route::post('/getProductByCategory', [CategoryController::class, 'getProductByCategory']);

    Route::middleware('auth:api')->group(function () {
        // Route::apiResource('categories', CategoryController::class)->except(['update'])->middleware('auth:api');
        // Route::post('categories/{category}', [CategoryController::class, 'update']);

        //category
        Route::post('categories/{categories}', [CategoryController::class, 'update']);
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::delete('/categories/{categories}', [CategoryController::class, 'destroy']);

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
        Route::get('/getAddressByUser', [AddressController::class, 'getAddressByUser']);

        //Discount
        Route::post('discount/{discount}', [DiscountController::class, 'update']);
        Route::post('/discount', [DiscountController::class, 'store']);
        Route::delete('/discount/{discount}', [DiscountController::class, 'destroy']);

        //User coupon
        Route::post('/user-coupon/{id}',[UserCouponController::class,'update']);
        Route::get('/user-coupon',[UserCouponController::class,'index']);
        Route::post('/user-coupon', [UserCouponController::class, 'store']);
        Route::post('/delUserCoupon',[UserCouponController::class,'deleteUserCoupon']);

        //Payment method
        Route::post('/payment-method/{id}',[PaymentMethodController::class,'update']);
        Route::get('/payment-method',[PaymentMethodController::class,'index']);
        Route::get('/payment-method/{id}',[PaymentMethodController::class,'show']);
        Route::post('/payment-method', [PaymentMethodController::class, 'store']);
        Route::delete('/payment-method/{id}', [PaymentMethodController::class, 'destroy']);

        //Comment
        Route::post('/comment/{id}',[CommentController::class,'update']);
        Route::get('/comment/{id}',[CommentController::class,'show']);
        Route::get('/comment',[CommentController::class,'index']);
        Route::post('/comment', [CommentController::class, 'store']);
        Route::delete('/comment/{id}', [CommentController::class, 'destroy']);
        Route::post('/UserDeleteComment', [CommentController::class, 'UserDeleteComment']);
        Route::post('/UserUpdateComment', [CommentController::class, 'UserUpdateComment']);

        //Cart
        Route::get('/getCartItem',[CartController::class,'getCartItem']);
        Route::post('/addToCart', [CartController::class, 'addToCart']);
        Route::get('/calculateTotal', [CartController::class, 'calculateTotal']);
        Route::post('/removeFromCart', [CartController::class, 'removeFromCart']);
        Route::post('/addMultipleToCart', [CartController::class, 'addMultipleToCart']);

        //Order
        Route::post('/createOrder',[OrderController::class,'createOrder']);

        //Payment
        Route::post("/UpdatePaymentOrder",[PaymentController::class,'UpdatePaymentOrder']);
        //order_zalopay
        Route::post('/payment2', [ZaloPayOrderController::class, 'payment']);
    });

});

Route::post('/payment2/callback', [ZaloPayOrderController::class, 'paymentCallback']);
Route::get('/payment2/status/{iddh}', [ZaloPayOrderController::class, 'get_status']);
