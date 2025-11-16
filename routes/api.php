<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Website\AuthController;
use App\Http\Controllers\Api\Website\CartController;
use App\Http\Controllers\Api\Website\HomeController;
use App\Http\Controllers\Api\Website\ProductController;
use App\Http\Controllers\Api\Website\CategoryController;
use App\Http\Controllers\Api\Website\UserAddressController;


Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {

        Route::prefix('addresses')->group(function () {
            Route::get('/', [UserAddressController::class, 'index']);
            Route::get('/{id}', [UserAddressController::class, 'show']);
            Route::post('/', [UserAddressController::class, 'store']);
            Route::put('/{id}', [UserAddressController::class, 'update']);
            Route::delete('/{id}', [UserAddressController::class, 'destroy']);
        });

        Route::prefix('carts')->group(function () {
            Route::get('/', [CartController::class, 'index']);
            //   Route::get('/{id}', [CartController::class, 'show']);
            Route::post('/', [CartController::class, 'store']);
            Route::put('/{id}', [CartController::class, 'update']);
            Route::delete('/{id}/item', [CartController::class, 'destroy']);
        });

        Route::prefix('auth')->group(function () {
            Route::get('/profile', [AuthController::class, 'profile']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
        Route::post('/social-login', [AuthController::class, 'socialLogin']);
        Route::post('send-otp', [AuthController::class, 'sendOtp']);
        Route::post('reset-password',  [AuthController::class, 'resetPassword']);
        Route::post('verify-otp',  [AuthController::class, 'verifyOtp']);

    });

    Route::get('home', [HomeController::class, 'index']);

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/color', [ProductController::class, 'getColor']);
        Route::get('/material', [ProductController::class, 'getMaterial']);
        Route::get('/{id}', [ProductController::class, 'show']);
    });
});
