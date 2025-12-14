<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\Api\ProductReviewController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES  (Laravel 12)
|--------------------------------------------------------------------------
| - Login must be inside web middleware
| - Protect dashboard with auth + admin
| - Define route('login') to avoid errors
|--------------------------------------------------------------------------
*/

// ----------------------------------------------------------------------
// 🔐 WEB + SESSION ROUTES
// ----------------------------------------------------------------------
Route::middleware('web')->group(function () {

    // Laravel internal authentication expects this:
    Route::get('/login', function () {
        return redirect()->route('auth.login');
    })->name('login');

    // ------- Admin Login ---------
    Route::get('/admin/login', [AuthWebController::class, 'showLogin'])
        ->name('auth.login');

    Route::post('/admin/login', [AuthWebController::class, 'login'])
        ->name('auth.login.submit');


    // ------- Protected Admin Dashboard ---------
    Route::middleware(['auth', 'is.admin'])->group(function () {

        Route::post('/logout', [AuthWebController::class, 'logout'])->name('auth.logout');


        Route::get('/home', function () {
            return view('admin.layouts.dashboard');
        })->name('dashboard.home');




        // ----------------------------------------------------------------------
        // 📦 PRODUCTS MODULE
        // ----------------------------------------------------------------------
        Route::prefix('products')->name('products.')->group(function () {
            Route::view('/', 'admin.products.index')->name('index');
            Route::view('/create', 'admin.products.create')->name('create');
            Route::view('/edit', 'admin.products.create')->name('edit');
        });


        // ----------------------------------------------------------------------
        // 🏷 CATEGORIES
        // ----------------------------------------------------------------------
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::view('/', 'admin.categories.index')->name('index');
        });



        // ----------------------------------------------------------------------
        // 🧾 ORDERS
        // ----------------------------------------------------------------------
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::view('/list', 'admin.orders.order-lists')->name('list');
            Route::view('/details', 'admin.orders.order-details')->name('details');
        });



        // ----------------------------------------------------------------------
        // 👥 CUSTOMERS
        // ----------------------------------------------------------------------
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::view('/all', 'admin.customers.all-customers')->name('all');
            Route::view('/overview', 'admin.customers.customer-overview')->name('overview');
            Route::view('/security', 'admin.customers.customer-security')->name('security');
            Route::view('/billing', 'admin.customers.customer-details-billing')->name('billing');
        });


        // ----------------------------------------------------------------------
        // 🧾 COUPONS / DISCOUNTS
        // ----------------------------------------------------------------------
        Route::prefix('discounts')->name('discounts.')->group(function () {
            Route::view('/all', 'admin.discounts.all-coupons')->name('all');
            Route::view('/add', 'admin.discounts.add-new-coupons')->name('add');
            Route::view('/active', 'admin.discounts.all-active-coupons')->name('active');
            Route::view('/expired', 'admin.discounts.all-expired-coupons')->name('expired');
        });


        // ----------------------------------------------------------------------
        // ⭐ REVIEWS
        // ----------------------------------------------------------------------
        Route::prefix('reviews')->name('reviews.')->group(function () {
            Route::view('/all', 'admin.review.all-review')->name('all');
            Route::get('/add', [ProductReviewController::class, 'form'])->name('add');
        });


        // ----------------------------------------------------------------------
        // ⚙️ STORE SETTINGS
        // ----------------------------------------------------------------------
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::view('/store-setting', 'admin.store-details.store-details')->name('store-details');
        });
    });
});


// ----------------------------------------------------------------------
// 🌐 PUBLIC FALLBACK + ROOT
// ----------------------------------------------------------------------
Route::fallback(function () {
    return redirect()->route('login');
});

Route::get('/', function () {
    return redirect()->route('login');
});


// ----------------------------------------------------------------------
// 📝 AUTH PAGES (Blade Only)
// ----------------------------------------------------------------------
Route::get('/register', function () {
    return view('admin.auth.register');
})->name('auth.register');

Route::get('/forgot-password', function () {
    return view('admin.auth.forgot');
})->name('auth.forgot');
