<?php

use App\Http\Controllers\LoginGooleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => ''], function () {
    Route::get('/auth/google/redirect', [LoginGooleController::class, 'redirectToGoogle']);
    Route::get('/auth/google/callback', [LoginGooleController::class, 'handleGoogleCallback']);
});
