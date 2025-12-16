<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\SubscribeController;
use App\Http\Controllers\Admin\BannerItemController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\LogisticServiceController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for the admin panel, including authentication and resource management.
|
*/

// Add this route BEFORE any middleware groups
Route::get('admin/categories/tree', [CategoryController::class, 'getTree'])->name('admin.categories.tree.data');

// Authentication Routes
Route::prefix('admin')->name('admin.')->middleware('guest:admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'loginPage'])->name('login.page');
    Route::post('login/post', [AdminAuthController::class, 'login'])->name('login');

    // Password Reset Routes
    Route::get('forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [AdminAuthController::class, 'sendResetOtp'])->name('password.email');
    Route::get('reset-password/{token}', [AdminAuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('reset-password', [AdminAuthController::class, 'resetPassword'])->name('password.update');
});

// Admin Routes (Authenticated)
Route::prefix('admin')->as('admin.')->middleware('auth:admin')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/visitors/chart', [VisitorController::class, 'chartData'])
        ->name('visitors.chart');

    // Settings
    Route::prefix('settings')->as('setting.')->group(function () {
        Route::get('pages', [SettingController::class, 'pages'])->name('pages');
        Route::get('edit', [SettingController::class, 'edit'])->name('edit');
        Route::post('update', [SettingController::class, 'update'])->name('update');
        Route::post('update-pages', [SettingController::class, 'updatepages'])->name('updatepages');
    });

    // Resource Routes
    Route::resources([
        'admins' => AdminController::class,
        'permissions' => PermissionsController::class,
        'roles' => RolesController::class,
        'countries' => CountryController::class,
        'contactus' => ContactUsController::class,
        'faqs' => FaqController::class,
        'logistic-services' => LogisticServiceController::class,
        'employees' => EmployeeController::class,
        'managers' => ManagerController::class,
        'regions' => RegionController::class,
    ]);

    // Users
    Route::prefix('users')->as('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index')->withoutMiddleware('admin:1')->middleware('admin:1,0');
        Route::get('show/{id}', [UserController::class, 'show'])->name('show')->withoutMiddleware('admin:1')->middleware('admin:1,0');
        Route::get('verify/email/{id}', [UserController::class, 'verifyEmail'])->name('verify-email');
        Route::get('verify/{id}', [UserController::class, 'verify'])->name('verify');
        Route::post('reject/{id}', [UserController::class, 'reject'])->name('reject');
        Route::post('notify', [UserController::class, 'sendNotify'])->name('sendnotify');
        Route::get('archive', [UserController::class, 'archive'])->name('archive');
        Route::get('restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::delete('force-delete/{id}', [UserController::class, 'forceDelete'])->name('forcedelete');
        Route::post('wallet-control', [UserController::class, 'walletControl'])->name('walletcontrol')->withoutMiddleware('admin:1')->middleware('admin:1,0');
        Route::post('package-control', [UserController::class, 'packageControl'])->name('package-control');
    });

    // categories (without tree route)
    Route::prefix('categories')->as('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        Route::post('/update-order', [CategoryController::class, 'updateOrder'])->name('updateOrder');
        Route::get('/export', [CategoryController::class, 'export'])->name('export');
        Route::post('/{category}/duplicate', [CategoryController::class, 'duplicate'])->name('duplicate');
    });

    // Products
    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [ProductController::class, 'show'])->name('show');
        Route::post('quick-add', [ProductController::class, 'quickAdd'])->name('quick-add');
        Route::post('bulk-action', [ProductController::class, 'bulkAction'])->name('bulk-action');
        Route::get('export', [ProductController::class, 'export'])->name('export');
        Route::post('{product}/duplicate', [ProductController::class, 'duplicate'])->name('duplicate');
    });

    // Contacts
    Route::prefix('contacts')->as('contact.')->group(function () {
        Route::get('/', [ContactController::class, 'index'])->name('index');
        Route::get('read/{id}', [ContactController::class, 'read'])->name('read');
        Route::delete('delete/{id}', [ContactController::class, 'destroy'])->name('destroy');
    });

    // Subscriptions
    Route::prefix('subscriptions')->as('subscribe.')->group(function () {
        Route::get('/', [SubscribeController::class, 'index'])->name('index');
    });

    // Additional Routes
    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/export', [ProductController::class, 'export'])->name('export');
    });

    // Payment Method
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::patch('payment-methods/{paymentMethod}/toggle-status', [PaymentMethodController::class, 'toggleStatus'])->name('payment-methods.toggle-status');

    // Users
    Route::prefix('users')->as('users.')->group(function () {
        Route::resource('/', UserController::class);
        Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/{user}/orders', [UserController::class, 'orders'])->name('orders');
        Route::get('/{user}/reviews', [UserController::class, 'reviews'])->name('reviews');
        Route::get('/{user}/favourites', [UserController::class, 'favourites'])->name('favourites');
        Route::get('/{user}/activities', [UserController::class, 'activities'])->name('activities');
    });


// Banner Routes
Route::prefix('banners')->name('banners.')->group(function () {
    Route::get('/', [BannerController::class, 'index'])->name('index');
    Route::get('/create', [BannerController::class, 'create'])->name('create');
    Route::post('/', [BannerController::class, 'store'])->name('store');
    Route::get('/{banner}', [BannerController::class, 'show'])->name('show');
    Route::get('/{banner}/edit', [BannerController::class, 'edit'])->name('edit');
    Route::put('/{banner}', [BannerController::class, 'update'])->name('update');
    Route::delete('/{banner}', [BannerController::class, 'destroy'])->name('destroy');
    Route::post('/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])->name('toggle-status');

    // Banner Items Routes - إضافة route للعرض
    Route::get('/items/{bannerItem}', [BannerItemController::class, 'show'])->name('items.show'); // أضف هذا السطر
    Route::post('/items', [BannerItemController::class, 'store'])->name('items.store');
    Route::put('/items/{bannerItem}', [BannerItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{bannerItem}', [BannerItemController::class, 'destroy'])->name('items.destroy');
    Route::post('/items/{bannerItem}/toggle-status', [BannerItemController::class, 'toggleStatus'])->name('items.toggle-status');
    Route::post('/items/reorder', [BannerItemController::class, 'reorder'])->name('items.reorder');
});

    // Orders
    Route::prefix('orders')->as('orders.')->group(function () {
        Route::resource('/', OrderController::class);
        Route::post('/{order}/update-status', [OrderController::class, 'updateStatus'])->name('update-status');
        Route::get('/{order}/print', [OrderController::class, 'print'])->name('print');
        Route::get('/export', [OrderController::class, 'export'])->name('export');
    });
    Route::get('order/statistics', [OrderController::class, 'statistics'])->name('orders.statistics');



});

// Visitor stats route (outside admin group)
Route::get('/orders/stats/{year}', [VisitorController::class, 'ordersStats']);
