<?php

use App\Http\Controllers\Admin\AnalysisController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\FaqsController;
use App\Http\Controllers\Admin\BounsController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\StoryController;
use App\Http\Controllers\Admin\couponController;
use App\Http\Controllers\Admin\TitleController;
use App\Http\Controllers\Vendor\HomeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CitiesController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\GroupsController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\ArchiveController;
use App\Http\Controllers\Admin\DetailsController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactsController;
use App\Http\Controllers\Admin\giftCardController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Vendor\OrdersVController;
use App\Http\Controllers\Vendor\SubCateController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Vendor\ProductVController;
use App\Http\Controllers\WebNotificationController;
use App\Http\Controllers\Admin\InspirationController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\ConstructionController;
use App\Http\Controllers\Admin\EmployeeUserController;
use App\Http\Controllers\Vendor\VendorSettingController;
use App\Http\Controllers\Admin\Message\MessageController;
use App\Http\Controllers\Admin\Partner\Expense\ExpenseController;
use App\Http\Controllers\Admin\Partner\Obligation\ObligationController;
use App\Http\Controllers\Admin\Partner\PartnerController;
use App\Http\Controllers\Admin\Partner\UserRequest\UserRequestController;
use App\Http\Controllers\Admin\ProductFeaturesController;
use App\Http\Controllers\Admin\ProductImagesController;
use App\Http\Controllers\Front\UserController;

use App\Http\Controllers\Admin\Setting\NewSettingController;
use App\Http\Controllers\Admin\SalesManagement\SalesManagementController;

// Route::group(['prefix' => 'admin', 'middleware' => 'auth:web'], function () {
//     Route::get('testing', function () {
//         return view('admin.other_users.dashboards.testing.dashboard');
//     })->name('admin.testing');
//     Route::get('investment-partners', function () {
//         return view('admin.other_users.dashboards.investment_partners.dashboard');
//     })->name('admin.investment_partner');
//     Route::get('sales-management', function () {
//         return view('admin.other_users.dashboards.sales_management.dashboard');
//     })->name('admin.sales_management');
//     Route::get('data-entry', function () {
//         return view('admin.other_users.dashboards.data_entry.dashboard');
//     })->name('admin.data_entry');
// });

Route::group([
   'middleware' => ['redirect_if_not_authenticated']
], function () {
    Route::get('/admin', function () {
        return view('admin.home');
    });

    Route::group(['prefix' => 'admin'], function () {

        Route::get('/visitors', [AnalysisController::class, 'visitors'])->name('visitors')->middleware('permission:view details');
        Route::get('/visits', [AnalysisController::class, 'visits'])->name('visits')->middleware('permission:view details');
        Route::get('/storehouse', [AnalysisController::class, 'storehouse'])->name('storehouse')->middleware('permission:view details');

        Route::group([
            'prefix' => 'partners',
            'as' => 'partners.',
        ], function () {

            Route::get('/', [PartnerController::class, 'index'])->name('index');

            Route::group([
                'prefix' => 'user-requests',
                'as' => 'requests.',
                'controller' => UserRequestController::class,
            ], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::delete('/{userRequest}', 'destroy')->name('delete');
            });

            Route::group([
                'prefix' => 'obligations',
                'as' => 'obligation.',
                'controller' => ObligationController::class,
            ], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::delete('/{obligation}', 'destroy')->name('delete');
            });

            Route::group([
                'prefix' => 'expenses',
                'as' => 'expenses.',
                'controller' => ExpenseController::class,
            ], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::delete('/{id}', 'destroy')->name('delete');
            });

        });

        Route::get('dashboard', [DashboardController::class, 'dashboard']);
        Route::get('users-statistics', [DashboardController::class, 'userStatistics']);
        Route::get('', [DashboardController::class, 'productimages']);

        Route::get('productimages/{id}', [ProductImagesController::class, 'index'])->name('productimages');
        Route::get('productimages/create/{id}', [ProductImagesController::class, 'create'])->name('productimagesCreate');
        Route::post('productimages/store', [ProductImagesController::class, 'store'])->name('productimagestore');
        Route::patch('productimages/update/{id}', [ProductImagesController::class, 'update'])->name('productimagesupdate');
        Route::delete('productimages/delete', [ProductImagesController::class, 'destroy'])->name('productimagesdestroy');


        Route::get('signout', [DashboardController::class, 'signout'])->name('signout');
        Route::resource('blog', BlogController::class)->middleware('permission:view cities');
        Route::resource('store', StoreController::class)->middleware('permission:view cities');
        Route::resource('category', CategoryController::class)->middleware('permission:view categories');
        Route::resource('countries', CountriesController::class)->middleware('permission:view cities');
        Route::resource('cities', CitiesController::class)->middleware('permission:view cities');

        Route::resource('services', ServicesController::class)->middleware('permission:view services');
        Route::resource('subcategory', SubCategoryController::class)->middleware('permission:view subcategories');
        Route::resource('bonuses', BounsController::class);
        Route::resource('pages', PagesController::class);
        Route::resource('product', ProductController::class)->middleware('permission:view products');
        Route::resource('coupons', CouponController::class)->names('coupons')->middleware('permission:view coupons');
        Route::resource('client', ClientController::class)->middleware('permission:view users');
        Route::post('send-Notification', [MessageController::class, 'send'])->name('sendNotification');
        Route::resource('contacts', ContactsController::class)->middleware('permission:view support');
        Route::resource('orders', OrdersController::class)->middleware('permission:view orders');
        Route::post('details', [OrdersController::class, 'details'])->name('order-details')->middleware('permission:view cities');
        Route::resource('banners', BannerController::class)->middleware('permission:view details');
        Route::resource('story', StoryController::class)->middleware('permission:view details');
        Route::resource('inspiration', InspirationController::class)->middleware('permission:view details');
        Route::resource('messages', SupportController::class);
        Route::post('messages/delete-all', [SupportController::class, 'destroyAll'])->name('messages.deletell');
        Route::resource('contactUs', ContactUsController::class)->middleware('permission:view support');
        Route::resource('giftCards', giftCardController::class)->middleware('permission:view details');
        Route::resource('offers', OfferController::class)->middleware('permission:view offers');
        Route::resource('titles', TitleController::class)->middleware('permission:view cities');
        Route::resource('payments', PaymentController::class)->middleware('permission:view details');
        Route::resource('faqs', FaqsController::class)->middleware('permission:view details');
        Route::resource('groups', GroupsController::class)->middleware('permission:view details');
        Route::post('subcats', [CategoryController::class, 'subcats'])->name('subcats');
        Route::post('cateStore', [SubCategoryController::class, 'cateStore'])->name('cateStore');
        Route::get('setting', [SettingController::class, 'settings'])->name('setting')->middleware('permission:view details');
        Route::post('setting', [SettingController::class, 'settingsSave'])->name('settingSave');
        Route::get('constructions', [ConstructionController::class, 'constriction'])->name('constrictions');
        Route::post('constructions', [ConstructionController::class, 'constructionSave'])->name('constructionSave');
        Route::get('archive', [ArchiveController::class, 'archiveProduct'])->name('archive')->middleware('permission:view archive');
        //Route::post('productdestroy', [ArchiveController::class, 'productdestroy'])->name('productdestroy');
        Route::POST('archivedelete', [ArchiveController::class, 'archivedelete'])->name('archivedelete');
        Route::resource('users', UserController::class)->names('users')->middleware('permission:edit users');

        Route::get('archiveinspiration', [ArchiveController::class, 'archiveinspiration'])->name('archiveinspiration');
        Route::POST('archiveRestore', [ArchiveController::class, 'archiveRestore'])->name('archiveRestore');
        Route::GET('InsRestore', [ArchiveController::class, 'insrestore'])->name('InsRestore');

        Route::POST('archiveSub', [ArchiveController::class, 'archiveSub'])->name('archiveSub')->middleware('permission:view archive');
        Route::post('subRestore', [ArchiveController::class, 'subRestore'])->name('subRestore');
        Route::get('archiveCate', [ArchiveController::class, 'archiveCate'])->name('archiveCate')->middleware('permission:view archive');
        Route::post('cateRestore', [ArchiveController::class, 'cateRestore'])->name('cateRestore');
        Route::get('archiveStore', [ArchiveController::class, 'archiveStore'])->name('archiveStore')->middleware('permission:view archive');
        Route::post('storeRestore', [ArchiveController::class, 'storeRestore'])->name('storeRestore');
        Route::get('archiveOrder', [ArchiveController::class, 'archiveOrder'])->name('archiveOrder');
        Route::post('orderRestore', [ArchiveController::class, 'orderRestore'])->name('orderRestore');
        Route::get('details', [DetailsController::class, 'index'])->name('details')->middleware('permission:view details');
        Route::get('details/create', [DetailsController::class, 'create'])->name('detailsCreate');
        Route::post('details/store', [DetailsController::class, 'store'])->name('detailsstore');
        Route::patch('details/update/{id}', [DetailsController::class, 'update'])->name('detailsupdate');
        Route::get('productfeatures/{id}', [ProductFeaturesController::class, 'index'])->name('productfeatures');
        Route::get('productfeatures/create/{id}', [ProductFeaturesController::class, 'create'])->name('productfeatureCreate');
        Route::post('productfeatures/store', [ProductFeaturesController::class, 'store'])->name('productfeaturestore');
        Route::patch('productfeatures/update/{id}', [ProductFeaturesController::class, 'update'])->name('productfeaturesupdate');
        Route::delete('productfeatures/delete', [ProductFeaturesController::class, 'destroy'])->name('productfeaturesdestroy');
        Route::get('countries/delete/{id}', [ProductFeaturesController::class, 'destroy'])->name('countriesdestroy');
        Route::get('cities/delete/{id}', [CitiesController::class, 'destroy'])->name('citiesdestroy');

        Route::get('/changePassword', [DashboardController::class, 'showChangePasswordForm']);
        Route::post('/changePassword', [DashboardController::class, 'changePassword'])->name('changePassword');
        Route::get('/prient/{id}', [DashboardController::class, 'prient'])->name('prient');

        Route::get('/employees/promo-codes', [EmployeeUserController::class, 'promoCodes'])->name('employees.promo-code.index');
        Route::post('/employees/promo-codes', [EmployeeUserController::class, 'storePromoCode'])->name('employees.promo-code.store');
        Route::put('/employees/promo-codes/{promoCode}', [EmployeeUserController::class, 'updatePromoCode'])->name('employees.promo-code.update');
        Route::delete('/employees/promo-codes/{promoCode}', [EmployeeUserController::class, 'destroyPromoCode'])->name('employees.promo-code.delete');

        Route::resource('employees', EmployeeUserController::class);

        Route::get('/app-settings', [NewSettingController::class, 'index'])->name('app-settings.index');
        Route::put('/app-settings/{newSetting}', [NewSettingController::class, 'update'])->name('app-settings.update');

        Route::group([
            'prefix' => 'notifications-and-emails',
            'as' => 'emails.',
            'controller' => MessageController::class,
        ], function () {
            Route::get('/', 'create')->name('create')->middleware('permission:view cities');
            Route::post('/', 'send')->name('send');
            Route::post('/unregistered-users', 'unregisteredUsers')->name('unregistered-users');
        });
    });
    Route::post('/send-web-notification', [WebNotificationController::class, 'sendWebNotification'])->name('send.web-notification');


    /****************************sales_management***************/
    Route::group(['prefix' => 'sales-management'], function () {
        Route::get('show-my-code', [SalesManagementController::class, 'showMyCode'])->name('show_my_code.sales_management')->middleware('permission:view cities');
        Route::get('email-page', [SalesManagementController::class, 'showMyEmailAddressPage'])->name('showMyEmailAddressPage.sales_management')->middleware('permission:view cities');
        Route::post('email-store', [SalesManagementController::class, 'storeEmailAddressMessage'])->name('storeEmailAddressMessage.sales_management');
        Route::get('employee-wallet', [SalesManagementController::class, 'showEmployeesWallet'])->name('employees_wallets.index')->middleware('permission:view cities');
        Route::get('products-show', [SalesManagementController::class, 'showProducts'])->name('showProducts.index')->middleware('permission:view cities');
    });
    /****************************end sales_management***************/
});


/*    Vendor Route dashboard   */
Route::get('vendor-login', [HomeController::class, 'vendorLogin']);
Route::post('checkVendor', [HomeController::class, 'doLogin'])->name('checkVendor');
Route::group(['middleware' => 'auth:vendors'], function () {
    Route::get('vendorLogout', [HomeController::class, 'logout'])->name('vendorLogout');
    Route::group(['prefix' => 'vendor', 'namespace' => 'Vendor'], function () {
        Route::resource('categories', CategoryController::class)->middleware('permission:view details');
        Route::resource('subCateVendor', SubCateController::class)->middleware('permission:view details');
        Route::resource('productsV', ProductVController::class);
        //        Route::resource('settingsV',SettingVController::class);
        Route::resource('ordersV', OrdersVController::class)->middleware('permission:view orders');
        Route::get('settingV', [VendorSettingController::class, 'index'])->name('settingV');
        Route::post('doSetting', [VendorSettingController::class, 'doSetting'])->name('doSetting');
        Route::post('subCate', [VendorSettingController::class, 'subCate'])->name('subCate');
    });
    Route::get('Vendor-profile', function () {
        return view('vendor.home');
    });
});
