<?php

use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoginGooleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShipperController;
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


//Categories
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

    //Shipper
    Route::apiResource('shipper', ShipperController::class)->except(['update']);
    Route::post('shipper/{shipper}', [ShipperController::class, 'update']);
    Route::post('shiper/register', [ShipperController::class, 'register']);
    Route::post('shiper/login', [ShipperController::class, 'login']);
    Route::get('shiper/email/verify/{id}/{hash}', [ShipperController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('shipper/forgott-password', [ShipperController::class, 'sendLinkk']);
    Route::post('shipper/resett-password', [ShipperController::class, 'resettPassword']);
    Route::post('shipperAcceptOrder', [ShipperController::class, 'acceptOrder']);

    //Location
    Route::post('/shipperNear', [LocationController::class, 'getNearestAvailableShipper']);
    Route::post('/updateLocation', [LocationController::class, 'updateLocationOrder']);
});

// Register the shipper
// Route::middleware(['auth:api', 'verified'])->group(function () {
//     // Your protected routes here
//     Route::get('/abc', function () {
//         return response()->json(['message' => 'You have access to this route.']);
//     });
// });

Route::post('/payment', [ZaloPayController::class, 'payment']);
Route::post('/payment/callback', [ZaloPayController::class, 'callback']);
Route::get('/payment/status/{iddh}', [ZaloPayController::class, 'get_status']);
