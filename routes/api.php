<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Website\FaqController;
use App\Http\Controllers\Api\Website\AuthController;
use App\Http\Controllers\Api\Website\CartController;
use App\Http\Controllers\Api\Website\HomeController;
use App\Http\Controllers\Api\Website\OrderController;
use App\Http\Controllers\Api\Website\BannerController;
use App\Http\Controllers\Api\Website\ProductController;
use App\Http\Controllers\Api\Website\CategoryController;
use App\Http\Controllers\Api\Website\FavoriteController;
use App\Http\Controllers\Api\Website\ContactUsController;
use App\Http\Controllers\Api\Website\SocialMediaController;
use App\Http\Controllers\Api\Website\StaticPagesController;
use App\Http\Controllers\Api\Website\UserAddressController;
use App\Http\Controllers\Api\Website\CustomizationOptionsController;


Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {

        Route::prefix('addresses')->group(function () {
            Route::get('/', [UserAddressController::class, 'index']);
            Route::get('/{id}', [UserAddressController::class, 'show']);
            Route::post('/', [UserAddressController::class, 'store']);
            Route::put('/{id}', [UserAddressController::class, 'update']);
            Route::delete('/{id}', [UserAddressController::class, 'destroy']);
        });

        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/add', [CartController::class, 'add'])->name('add');
            Route::put('/items/{cartItem}', [CartController::class, 'update'])->name('update');
            Route::delete('/items/{cartItem}', [CartController::class, 'remove'])->name('remove');
            Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        });


        Route::prefix('auth')->group(function () {
            Route::get('/profile', [AuthController::class, 'profile']);
            Route::post('/change-password', [AuthController::class, 'changePassword']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });

        Route::prefix('favorites')->group(function () {
            Route::get('/', [FavoriteController::class, 'index']);
            Route::post('/toggle', [FavoriteController::class, 'toggle']);
        });

        Route::prefix('order')->name('order.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{orderID}', [OrderController::class, 'show'])->name('show');
            Route::post('/', [OrderController::class, 'store'])->name('store');
            Route::post('cancel/{codeOrder}', [OrderController::class, 'cancelled'])->name('cancel');
        });

        Route::post('coupon/apply', [OrderController::class, 'applyCoupon']);
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

    Route::get('social-media', [SocialMediaController::class, 'index']);

    Route::get('static-pages/{slug}', [StaticPagesController::class, 'index']);

    Route::get('faqs', [FaqController::class, 'index']);

    Route::post('contact-us', [ContactUsController::class, 'store']);


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

    Route::get('/banners', [BannerController::class, 'index']);
    Route::get('/payment-methods', [OrderController::class, 'paymentMethods']);


    Route::get('/customization-options', [CustomizationOptionsController::class, 'index'])->name('customization_options');

    Route::get('trace/{codeOrder}', [OrderController::class, 'traceOrder'])->name('trace');

    Route::post('/paymob/webhook', [OrderController::class, 'webhook'])->withoutMiddleware(['throttle:60,1']);
});
