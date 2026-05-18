<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdsController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BannerItemController;
use App\Http\Controllers\Admin\BannerItemV2Controller;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ErrorController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LogisticServiceController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\StaticPageController;
use App\Http\Controllers\Admin\SubscribeController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VisitorController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/', [AdminController::class, 'home'])->name('index');
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
    // Ads Management Routes
    Route::prefix('ads')->name('ads.')->group(function () {
        Route::get('/', [AdsController::class, 'index'])->name('index');           // List all ads
        Route::post('/', [AdsController::class, 'store'])->name('store');          // Store new ad
        Route::get('/{id}', [AdsController::class, 'show'])->name('show');         // Show single ad (AJAX)
        Route::put('/{id}', [AdsController::class, 'update'])->name('update');     // Update ad
        Route::delete('/{id}', [AdsController::class, 'destroy'])->name('destroy'); // Delete ad

        // Optional: Additional useful routes
        Route::get('/create', [AdsController::class, 'create'])->name('create');   // Show create form (if needed)
        Route::get('/{id}/edit', [AdsController::class, 'edit'])->name('edit');    // Show edit form (if needed)
    });

    Route::prefix('admins')->name('admins.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/create', [AdminController::class, 'create'])->name('create');
        Route::post('/', [AdminController::class, 'store'])->name('store');
        Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('edit');
        Route::put('/{admin}', [AdminController::class, 'update'])->name('update');
        Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('destroy');
    });

    // 'admins' => AdminController::class,

    // Resource Routes
    Route::resources([
        // 'permissions' => PermissionsController::class,
        'roles' => RolesController::class,
        'countries' => CountryController::class,
        'contactus' => ContactUsController::class,
        // 'faqs' => FaqController::class,
        'logistic-services' => LogisticServiceController::class,
        'employees' => EmployeeController::class,
        'managers' => ManagerController::class,
        'regions' => RegionController::class,
    ]);

    // Testimonials
    Route::prefix('testimonials')->name('testimonials.')->group(function () {
        // Listing & CRUD
        Route::get('/', [TestimonialController::class, 'index'])->name('index');
        Route::get('/create', [TestimonialController::class, 'create'])->name('create');
        Route::post('/', [TestimonialController::class, 'store'])->name('store');
        Route::get('/{testimonial}', [TestimonialController::class, 'show'])->name('show');
        Route::get('/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('edit');
        Route::put('/{testimonial}', [TestimonialController::class, 'update'])->name('update');
        Route::delete('/{testimonial}', [TestimonialController::class, 'destroy'])->name('destroy');

        // Bulk Actions
        Route::post('/bulk-action', [TestimonialController::class, 'bulkAction'])->name('bulk-action');

        // Duplicate
        Route::post('/{testimonial}/duplicate', [TestimonialController::class, 'duplicate'])->name('duplicate');

        // Export CSV
        Route::get('/export', [TestimonialController::class, 'export'])->name('export');

        // AJAX Actions
        Route::patch('/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/update-order', [TestimonialController::class, 'updateOrder'])->name('update-order');

        // Search (AJAX Autocomplete)
        Route::get('/search', [TestimonialController::class, 'search'])->name('search');
    });

    // Faqs
    Route::prefix('faqs')->name('faqs.')->group(function () {
        // Listing & CRUD
        Route::get('/', [FaqController::class, 'index'])->name('index');
        Route::get('/create', [FaqController::class, 'create'])->name('create');
        Route::post('/', [FaqController::class, 'store'])->name('store');

        // Bulk Actions
        Route::post('/bulk-action', [FaqController::class, 'bulkAction'])->name('bulk-action');

        // Export CSV
        Route::get('/export', [FaqController::class, 'export'])->name('export');

        // AJAX Actions
        Route::post('/update-order', [FaqController::class, 'updateOrder'])->name('update-order');

        // Search (AJAX Autocomplete)
        Route::get('/search', [FaqController::class, 'search'])->name('search');

        Route::get('/{faq}', [FaqController::class, 'show'])->name('show');
        Route::get('/{faq}/edit', [FaqController::class, 'edit'])->name('edit');
        Route::put('/{faq}', [FaqController::class, 'update'])->name('update');
        Route::delete('/{faq}', [FaqController::class, 'destroy'])->name('destroy');

        // Duplicate
        Route::post('/{faq}/duplicate', [FaqController::class, 'duplicate'])->name('duplicate');
        Route::patch('/{faq}/toggle-status', [FaqController::class, 'toggleStatus'])->name('toggle-status');
    });

    // coupons
    Route::prefix('coupons')->name('coupons.')->group(function () {
        //  Route::resource('/', CouponController::class);
        Route::get('/', [CouponController::class, 'index'])->name('index');
        Route::get('/create', [CouponController::class, 'create'])->name('create');
        Route::post('/', [CouponController::class, 'store'])->name('store');
        Route::get('/{coupon}/edit', [CouponController::class, 'edit'])->name('edit');
        Route::put('/{coupon}', [CouponController::class, 'update'])->name('update');
        Route::delete('/{coupon}', [CouponController::class, 'destroy'])->name('destroy');
        Route::get('/{coupon}', [CouponController::class, 'show'])->name('show');
        Route::post('bulk-action', [CouponController::class, 'bulkAction'])->name('bulk-action');
        Route::post('{coupon}/duplicate', [CouponController::class, 'duplicate'])->name('duplicate');
        Route::post('generate-code', [CouponController::class, 'generateCode'])->name('generate-code');
        Route::post('validate-code', [CouponController::class, 'validateCode'])->name('validate-code');
        Route::get('export', [CouponController::class, 'export'])->name('export');
    });

    // errors
    Route::prefix('errors')->name('errors.')->group(function () {
        Route::get('/', [ErrorController::class, 'index'])->name('index');
        Route::get('/php-errors', [ErrorController::class, 'phpErrors'])->name('php-errors');
        Route::get('/search', [ErrorController::class, 'search'])->name('search');
        Route::get('/download/{filename}', [ErrorController::class, 'download'])->name('download');
        Route::delete('/destroy', [ErrorController::class, 'destroy'])->name('destroy');
        Route::post('/clear-all', [ErrorController::class, 'clearAll'])->name('clear-all');
    });

    // social-media
    Route::prefix('social-media')->name('social-media.')->group(function () {
        Route::get('/', [SocialMediaController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [SocialMediaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SocialMediaController::class, 'update'])->name('update');
        Route::post('/bulk-update', [SocialMediaController::class, 'bulkUpdate'])->name('bulk-update');
    });

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

        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
        Route::get('/show/{id}', [ProductController::class, 'show'])->name('show');
        Route::post('quick-add', [ProductController::class, 'quickAdd'])->name('quick-add');
        Route::post('bulk-action', [ProductController::class, 'bulkAction'])->name('bulk-action');
        Route::get('export', [ProductController::class, 'export'])->name('export');
        Route::post('{product}/duplicate', [ProductController::class, 'duplicate'])->name('duplicate');
    });
    Route::post('products/update/{id}', [ProductController::class, 'update'])->name('products.update');

    // Contacts
    Route::prefix('contacts')->as('contact.')->group(function () {
        Route::get('/', [ContactUsController::class, 'index'])->name('index');
        Route::get('read/{id}', [ContactUsController::class, 'read'])->name('read');
        Route::delete('delete/{id}', [ContactUsController::class, 'destroy'])->name('destroy');
    });

    // Subscriptions
    Route::prefix('subscriptions')->as('subscribe.')->group(function () {
        Route::get('/', [SubscribeController::class, 'index'])->name('index');
    });

    // Additional Routes
    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/export', [ProductController::class, 'export'])->name('export');
        Route::post('/{product}/update-image', [ProductController::class, 'updateImage'])
            ->name('update-image');
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
        Route::post('/update-order', [BannerController::class, 'updateOrder'])->name('update-order');
        Route::get('export', [BannerController::class, 'export'])->name('export');
        Route::post('bulk-actions', [BannerController::class, 'bulkActions'])->name('bulk-actions');

        // Banner Items Routes - إضافة route للعرض
        // Route::get('/items/{bannerItem}', [BannerItemController::class, 'show'])->name('items.show'); // أضف هذا السطر
        // Route::post('/items', [BannerItemController::class, 'store'])->name('items.store');
        // Route::put('/items/{bannerItem}', [BannerItemController::class, 'update'])->name('items.update');
        // Route::delete('/items/{bannerItem}', [BannerItemController::class, 'destroy'])->name('items.destroy');
        // Route::post('/items/{bannerItem}/toggle-status', [BannerItemController::class, 'toggleStatus'])->name('items.toggle-status');
        // Route::post('/items/reorder', [BannerItemController::class, 'reorder'])->name('items.reorder');
    });
    // Banner Items Routes - إضافة جميع routes لعناصر البانر
    Route::prefix('banners/items/resource')->name('banners.items.')->group(function () {
        Route::get('/', [BannerItemV2Controller::class, 'index'])->name('index');
        Route::get('/create', [BannerItemV2Controller::class, 'create'])->name('create');
        Route::post('/', [BannerItemV2Controller::class, 'store'])->name('store');
        Route::get('/{bannerItem}', [BannerItemV2Controller::class, 'show'])->name('show');
        Route::get('/{bannerItem}/edit', [BannerItemV2Controller::class, 'edit'])->name('edit');
        Route::put('/{bannerItem}', [BannerItemV2Controller::class, 'update'])->name('update');
        Route::delete('/{bannerItem}', [BannerItemV2Controller::class, 'destroy'])->name('destroy');
        Route::post('/{bannerItem}/toggle-status', [BannerItemV2Controller::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/reorder', [BannerItemV2Controller::class, 'reorder'])->name('reorder');
    });
    // Orders
    Route::prefix('orders')->as('orders.')->group(function () {
        // Route::resource('/', OrderController::class);
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/edit', [OrderController::class, 'edit'])->name('edit');
        Route::put('/{order}', [OrderController::class, 'update'])->name('update');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');

        Route::post('/{order}/update-status', [OrderController::class, 'updateStatus'])->name('update-status');
        Route::get('/{order}/print', [OrderController::class, 'print'])->name('print');
        Route::get('/export', [OrderController::class, 'export'])->name('export');
    });

    // Routes for Roles and Permissions
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}', [RoleController::class, 'show'])->name('show');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        Route::get('/{role}/permissions', [RoleController::class, 'permissions'])->name('permissions');
        Route::post('/{role}/permissions', [RoleController::class, 'syncPermissions'])->name('permissions.sync');
    });

    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/create', [PermissionController::class, 'create'])->name('create');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::post('/generate', [PermissionController::class, 'generateForModule'])->name('generate');
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('articles')->name('articles.')->group(function () {
        // مقالات
        // Route::resource('/', ArticleController::class);
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('/create', [ArticleController::class, 'create'])->name('create');
        Route::post('/', [ArticleController::class, 'store'])->name('store');
        Route::get('/{article}', [ArticleController::class, 'show'])->name('show');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{article}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-actions', [ArticleController::class, 'bulkActions'])->name('bulk-actions');
        Route::patch('/{article}/toggle-status', [ArticleController::class, 'toggleStatus'])->name('toggle-status');
        Route::patch('/{article}/toggle-featured', [ArticleController::class, 'toggleFeatured'])->name('toggle-featured');
    });

    // إحصائيات المقالات
    Route::get('/articles/statistics', [ArticleController::class, 'statistics'])->name('articles.statistics');

    Route::prefix('assign-roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'assignIndex'])->name('assign.index');
        Route::post('/', [RoleController::class, 'assignRoles'])->name('assign.store');
    });
    Route::prefix('static-pages')->name('static-pages.')->group(function () {
        Route::get('/', [StaticPageController::class, 'index'])->name('index');
        Route::get('/create', [StaticPageController::class, 'create'])->name('create');
        Route::post('/', [StaticPageController::class, 'store'])->name('store');
        Route::get('/{staticPage}', [StaticPageController::class, 'show'])->name('show');
        Route::get('/{staticPage}/edit', [StaticPageController::class, 'edit'])->name('edit');
        Route::put('/{staticPage}', [StaticPageController::class, 'update'])->name('update');
        Route::delete('/{staticPage}', [StaticPageController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [StaticPageController::class, 'bulkAction'])
            ->name('bulk-action');
    });

    Route::get('order/statistics', [OrderController::class, 'statistics'])->name('orders.statistics');
});

// Visitor stats route (outside admin group)
Route::get('/orders/stats/{year}', [VisitorController::class, 'ordersStats']);
