<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\CodeController;
use App\Http\Controllers\Admin\NoteController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\LockerController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SubscribeController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\Admin\LogisticServiceController;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for the admin panel, including authentication and resource management.
|
*/

// Authentication Routes
Route::prefix('admin')->name('admin.')->middleware('guest:admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'loginPage'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login');


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

    // Route::get('home', [AdminController::class, 'home'])->name('home');

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
        'payments' => PaymentController::class,
        'contactus' => ContactUsController::class,
        'faqs' => FaqController::class,
        'products' => ProductController::class,
        // 'categories' => CategoryController::class,
        'sliders' => SliderController::class,
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

    // Clients
    // Route::prefix('clients')->as('client.')->group(function () {
    //     Route::get('/', [ClientController::class, 'index'])->name('index');
    //     Route::get('providers', [ClientController::class, 'providers'])->name('providers');
    //     Route::get('get-regions/{country_id}', [ClientController::class, 'getRegions'])->name('getRegions');
    //     Route::get('search-by-phone', [ClientController::class, 'searchByPhone'])->name('search-by-phone');
    //     Route::get('send-notification/{id}', [ClientController::class, 'sendNotificationToUser'])->name('sendNotificationToUser');
    //     Route::get('send-notifications', [ClientController::class, 'sendnotifications'])->name('sendnotifications');
    //     Route::post('send-notification/{id}', [ClientController::class, 'sendnotification'])->name('sendnotification');
    //     Route::post('send-notification/single', [ClientController::class, 'sendNotificationSingle'])->name('sendnotification.single');
    //     Route::get('change-status/{id}', [ClientController::class, 'changeStatus'])->name('changeStatus');
    // });

    // categories
    Route::prefix('categories')->as('categories.')->group(function () {
       Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        Route::post('/update-order', [CategoryController::class, 'updateOrder'])->name('updateOrder');
        Route::get('/tree', [CategoryController::class, 'getTree'])->name('getTree');
        Route::get('/export', [CategoryController::class, 'export'])->name('export');
        Route::post('/{category}/duplicate', [CategoryController::class, 'duplicate'])->name('duplicate');
    });
    // Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

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

    // // Notifications
    // Route::prefix('notifications')->as('notifications.')->group(function () {
    //     Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
    //     Route::get('all', [AdminNotificationController::class, 'allNotifications'])->name('all-notifications');
    //     Route::get('mark-read/{id?}', [AdminNotificationController::class, 'markNotification'])->name('mark_read');
    // });

    // Additional Routes can be added here
    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/export', [ProductController::class, 'export'])->name('export');
    });
});
Route::get('/orders/stats/{year}', [VisitorController::class, 'ordersStats']);
