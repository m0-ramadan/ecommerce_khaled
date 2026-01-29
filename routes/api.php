<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Website\AdsController;
use App\Http\Controllers\Api\Website\FaqController;
use App\Http\Controllers\Api\Website\AuthController;
use App\Http\Controllers\Api\Website\CartController;
use App\Http\Controllers\Api\Website\HomeController;
use App\Http\Controllers\Api\Website\OrderController;
use App\Http\Controllers\Api\Website\BannerController;
use App\Http\Controllers\Api\Website\ReviewController;
use App\Http\Controllers\Api\Website\ArticleController;
use App\Http\Controllers\Api\Website\ProductController;
use App\Http\Controllers\Api\Website\CategoryController;
use App\Http\Controllers\Api\Website\FavoriteController;
use App\Http\Controllers\Api\Website\ShippingController;
use App\Http\Controllers\Api\Website\ContactUsController;
use App\Http\Controllers\Api\Website\SocialMediaController;
use App\Http\Controllers\Api\Website\StaticPagesController;
use App\Http\Controllers\Api\Website\UserAddressController;
use App\Http\Controllers\Api\Website\OtoV2ShippingController;
use App\Http\Controllers\Api\Website\ArticleCommentController;
use App\Http\Controllers\Api\Website\ArticleCategoryController;
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
            Route::post('/upload-image', [CartController::class, 'uploadImage'])->name('upload-image');
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
            Route::get('/payment-status', [OrderController::class, 'paymentStatus']);
        });
    Route::get('/shippings-options', [ShippingController::class, 'getDeliveryOptions']);


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

    Route::get('ads', [AdsController::class, 'index']);

    Route::post('contact-us', [ContactUsController::class, 'store']);

    // تقييمات المنتجات
    Route::prefix('reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'index']); // جميع التقييمات مع فلترة
        Route::get('/my-reviews', [ReviewController::class, 'myReviews'])->middleware('auth:sanctum');
        Route::get('/product/{productId}', [ReviewController::class, 'productReviews']);
        Route::get('/{id}', [ReviewController::class, 'show']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [ReviewController::class, 'store']);
            Route::put('/{id}', [ReviewController::class, 'update']);
            Route::delete('/{id}', [ReviewController::class, 'destroy']);
        });
    });

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

    // مقالات
    Route::prefix('articles')->group(function () {
        Route::get('/', [ArticleController::class, 'index']);
        Route::get('/featured', [ArticleController::class, 'featured']);
        Route::get('/popular', [ArticleController::class, 'popular']);
        Route::get('/{slug}', [ArticleController::class, 'show']);
        Route::get('/{slug}/related', [ArticleController::class, 'related']);

        // التعليقات
        Route::get('/{article}/comments', [ArticleCommentController::class, 'index']);
        Route::post('/comments', [ArticleCommentController::class, 'store']);
    });

    // أقسام المقالات
    Route::prefix('article-categories')->group(function () {
        Route::get('/', [ArticleCategoryController::class, 'index']);
        Route::get('/with-counts', [ArticleCategoryController::class, 'withCounts']);
        Route::get('/{slug}', [ArticleCategoryController::class, 'show']);
    });



    // // OTO V2 Shipping Routes
    // Route::prefix('shipping')->middleware(['auth:sanctum'])->group(function () {
    //     // إنشاء الشحنات
    //     Route::post('/create', [OtoV2ShippingController::class, 'createShipment']);
    //     Route::post('/create-from-address/{orderId}/{addressId}', [OtoV2ShippingController::class, 'createShipmentFromAddress']);

    //     // تتبع الشحنات
    //     Route::get('/track/{trackingNumber}', [OtoV2ShippingController::class, 'trackShipment']);
    //     Route::post('/track-driver', [OtoV2ShippingController::class, 'trackDriver']);

    //     // الحسابات والاستعلامات
    //     Route::post('/calculate', [OtoV2ShippingController::class, 'calculateShipping']);
    //     Route::get('/cities', [OtoV2ShippingController::class, 'getCities']);
    //     Route::get('/cities/{cityCode}/districts', [OtoV2ShippingController::class, 'getDistricts']);

    //     // إدارة الشحنات
    //     Route::get('/label/{trackingNumber}', [OtoV2ShippingController::class, 'getLabel']);
    //     Route::post('/cancel', [OtoV2ShippingController::class, 'cancelShipment']);
    //     Route::post('/sync', [OtoV2ShippingController::class, 'syncShipments']);
    //     Route::get('/orders', [OtoV2ShippingController::class, 'getShippingOrders']);
    // });

    // تتبع عام للعملاء
    Route::get('/public/track/{trackingNumber}', [OtoV2ShippingController::class, 'trackShipment'])
        ->name('shipping.track.public');

        
Route::get('payment/callback/{orderId}', [PaymentController::class, 'handleSuccess'])
    ->name('payment.callback.paymob');

Route::get('payment/success/tabby', [PaymentController::class, 'handleSuccess'])
    ->name('payment.success.tabby');
Route::get('payment/failure/tabby', [PaymentController::class, 'handleSuccess'])
    ->name('payment.failure.tabby');
Route::get('payment/cancel/tabby', [PaymentController::class, 'handlecancel'])
    ->name('payment.cancel.tabby');

Route::get('payment/callback/{orderId}', [PaymentController::class, 'handleSuccess'])
    ->name('payment.callback.tabby');
Route::get('payment/callback/{orderId}', [PaymentController::class, 'handleSuccess'])
    ->name('payment.callback.tamara');
    Route::get('payment/callback/{orderId}', [PaymentController::class, 'handleSuccess'])
    ->name('payment.callback.paymob');

Route::get('payment/success/tamara', [PaymentController::class, 'handleSuccess'])
    ->name('payment.success.tamara');
Route::get('payment/failure/tamara', [PaymentController::class, 'handleSuccess'])
    ->name('payment.failure.tamara');
Route::get('payment/cancel/tamara', [PaymentController::class, 'handlecancel'])
    ->name('payment.cancel.tamara');
Route::get('payment/webhook/tamara', [PaymentController::class, 'handlewebhook'])
    ->name('payment.webhook.tamara');
});
