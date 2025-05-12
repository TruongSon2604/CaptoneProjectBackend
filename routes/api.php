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
use App\Http\Controllers\PostController;
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
    Route::post('/forget-password', [AuthController::class, 'changePasswordByEmail'])->name('changePasswordByEmail');
    Route::post('/update-password', [AuthController::class, 'updatePasswordManually'])->name('updatePasswordManually');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/updateUser', [AuthController::class, 'updateUser'])->name('updateUser');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});


Route::group([
    'middleware' => 'api',
], function () {
    //public//
    //dashboard
    Route::get('/productDashboard', [ProductController::class, 'getProductDashboard']);
    Route::get('/getUserDashBoard', [AuthController::class, 'getUserDashBoard']);
    Route::get('/getOrderDashBoard', [OrderController::class, 'getOrderDashBoard']);
    Route::get('/getTotal', [OrderController::class, 'getTotal']);
    Route::get('/getRevenueByMonth', [OrderController::class, 'getRevenueByMonth']);
    Route::get('/getOrderByMonth', [OrderController::class, 'getOrderByMonth']);
    Route::get('/getTotalProductOfCategory', [ProductController::class, 'getTotalProductOfCategory']);
    Route::get('/getDetailProductSoldByMonth/{month}', [OrderController::class, 'getDetailProductSoldByMonth']);


    //product
    Route::get('/product', [ProductController::class, 'index']);
    Route::get('/products/search', [ProductController::class, 'search']);

    Route::get('/product2', [ProductController::class, 'index2']);
    Route::get('/product/{product}', [ProductController::class, 'show']);
    Route::get('/getProductByid/{id}', [ProductController::class, 'getProductByid']);
    Route::get('/getProductLimit', [ProductController::class, 'getProductLimit']);
    Route::post('/filterProductBySelect', [ProductController::class, 'filterProductBySelect']);
    Route::get('/getproductDiscount', [ProductController::class, 'getproductDiscount']);
    Route::post('/findProductByImage', [ProductController::class, 'findProductByImage']);

    //discount
    Route::get('/discount', [DiscountController::class, 'index']);
    Route::get('/getProductNotDiscounted', [DiscountController::class, 'getProductNotDiscounted']);
    Route::get('/discount/{discount}', [DiscountController::class, 'show']);
    //category
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{categories}', [CategoryController::class, 'show']);
    Route::post('/getProductByCategory', [CategoryController::class, 'getProductByCategory']);

    Route::get('/post', [PostController::class, 'index']);
    Route::get('/post/{post}', [PostController::class, 'show']);

    Route::get('/getCommentByProductId/{id}', [CommentController::class, 'getCommentByProductId']);

    //post
    Route::get('/post/{id}', [PostController::class, 'show']);


    Route::middleware('auth:api')->group(function () {
        Route::middleware('is_admin')->group(function () {
            //category
            Route::post('categories/{categories}', [CategoryController::class, 'update']);
            Route::post('/categories', [CategoryController::class, 'store']);
            Route::delete('/categories/{categories}', [CategoryController::class, 'destroy']);
            Route::delete('/deleteMoreCategory', [CategoryController::class, 'deleteMoreCategory']);

            //Product
            Route::post('product/{product}', [ProductController::class, 'update']);
            Route::post('/product', [ProductController::class, 'store']);
            Route::delete('/product/{product}', [ProductController::class, 'destroy']);
            Route::delete('/deleteMoreProduct', [ProductController::class, 'deleteMoreProduct']);

            //Coupon
            Route::delete('/coupon/{coupon}', [CouponController::class, 'destroy']);
            Route::post('/coupon/{coupon}', [CouponController::class, 'update']);
            Route::post('/coupon', [CouponController::class, 'store']);
            Route::delete('/deleteMoreCoupon', [CouponController::class, 'deleteMoreCoupon']);

            //post
            Route::delete('/post/{coupposton}', [PostController::class, 'destroy']);
            Route::post('/post/{post}', [PostController::class, 'update']);
            Route::post('/post', [PostController::class, 'store']);
            Route::delete('/deleteMorePost', [PostController::class, 'destroy']);
            Route::post('/post/{id}', [PostController::class, 'update']);

            //Discount
            Route::post('discount/{discount}', [DiscountController::class, 'update']);
            Route::post('/discount', [DiscountController::class, 'store']);
            Route::delete('/discount/{discount}', [DiscountController::class, 'destroy']);
            Route::delete('/deleteMoreDiscount', [DiscountController::class, 'deleteMore']);

        });

        //Coupon
        Route::get('/coupon', [CouponController::class, 'index']);
        Route::get('/coupon2', [CouponController::class, 'index2']);
        Route::get('/coupon/{coupon}', [CouponController::class, 'show']);
        //Status
        Route::apiResource('status', StatusController::class)->except(['update']);
        Route::post('status/{status}', [StatusController::class, 'update']);

        //Product
        Route::get('/getDiscountProduct', [ProductController::class, 'getDiscountedPrice']);




        //Address
        Route::apiResource('address', AddressController::class)->except(['update']);
        Route::post('address/{address}', [AddressController::class, 'update']);
        Route::get('/getAddressByUser', [AddressController::class, 'getAddressByUser']);
        Route::delete('address/{address}', [AddressController::class, 'destroy']);



        //User coupon
        Route::post('/user-coupon/{id}', [UserCouponController::class, 'update']);
        Route::get('/user-coupon', [UserCouponController::class, 'index']);
        Route::post('/user-coupon', [UserCouponController::class, 'store']);
        Route::post('/delUserCoupon', [UserCouponController::class, 'deleteUserCoupon']);
        Route::get('/getUserWithCoupon', [UserCouponController::class, 'getUserWithCoupon']);
        Route::get('/getAllUserWithCoupon', [UserCouponController::class, 'getAllUserWithCoupon']);

        //Payment method
        Route::post('/payment-method/{id}', [PaymentMethodController::class, 'update']);
        Route::get('/payment-method', [PaymentMethodController::class, 'index']);
        Route::get('/payment-method/{id}', [PaymentMethodController::class, 'show']);
        Route::post('/payment-method', [PaymentMethodController::class, 'store']);
        Route::delete('/payment-method/{id}', [PaymentMethodController::class, 'destroy']);

        //Comment
        Route::post('/comment/{id}', [CommentController::class, 'update']);
        Route::get('/comment/{id}', [CommentController::class, 'show']);
        Route::get('/comment', [CommentController::class, 'index']);
        Route::post('/comment', [CommentController::class, 'store']);
        Route::delete('/comment/{id}', [CommentController::class, 'destroy']);
        Route::post('/UserDeleteComment', [CommentController::class, 'UserDeleteComment']);
        Route::post('/UserUpdateComment', [CommentController::class, 'UserUpdateComment']);

        //Cart
        Route::get('/getCartItem', [CartController::class, 'getCartItem']);
        Route::post('/addToCart', [CartController::class, 'addToCart']);
        Route::get('/calculateTotal', [CartController::class, 'calculateTotal']);
        Route::post('/removeFromCart', [CartController::class, 'removeFromCart']);
        Route::post('/addMultipleToCart', [CartController::class, 'addMultipleToCart']);
        Route::post('/updateQuantityCart', [CartController::class, 'updateQuantityCart']);
        Route::post('/deleteMoreItemFromCart', [CartController::class, 'deleteMoreItemFromCart']);
        Route::post('/getProductByListId', [CartController::class, 'getProductByListId']);

        //Order
        Route::post('/createOrder', [OrderController::class, 'createOrder']);
        Route::get('/getOrder', [OrderController::class, 'index']);
        Route::get('/getAllOrderOfUser', [OrderController::class, 'getAllOrderOfUser']);
        Route::post('/getOrderDetailOfUser', [OrderController::class, 'getOrderDetailOfUser']);
        Route::post('/updateOrderStatus', [OrderController::class, 'updateOrderStatus']);
        Route::post('/cancelOrder', [OrderController::class, 'cancelOrder']);
        //Payment
        Route::post("/UpdatePaymentOrder", [PaymentController::class, 'UpdatePaymentOrder']);
        //order_zalopay
        Route::post('/payment2', [ZaloPayOrderController::class, 'payment']);
    });

});

Route::post('/payment2/callback', [ZaloPayOrderController::class, 'paymentCallback']);
Route::get('/payment2/status/{iddh}', [ZaloPayOrderController::class, 'get_status']);



use App\Http\Controllers\VnPayController;
Route::get('/vnpay/payment', [VnPayController::class, 'createPayment']);
Route::post('/vnpay/payment', [VnPayController::class, 'createPayment']);
Route::get('/vnpay/return', [VnPayController::class, 'vnpayReturn']);
Route::get('/vnpay/checkPaymentStatus/{id}', [VnPayController::class, 'checkPaymentStatus']);
