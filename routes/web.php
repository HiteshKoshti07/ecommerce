<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', function () {
    return view('admin.layouts.dashboard');
})->name('dashboard.home');

// ================= Products Routes =================
Route::prefix('products')->name('products.')->group(function () {
    Route::view('/', 'admin.products.index')->name('index');
    Route::view('/create', 'admin.products.create')->name('create');
    Route::view('/edit', 'admin.products.create')->name('edit'); // ✅ renamed
});


// ================= Categories Routes =================
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', function () {
        return view('admin.categories.index');
    })->name('index');
});
// ================= Orders Routes =================
Route::prefix('orders')->name('orders.')->group(function () {
    Route::view('/list', 'admin.orders.order-lists')->name('list');
    Route::view('/details', 'admin.orders.order-details')->name('details');
});

// ================= Customers Routes =================
Route::prefix('customers')->name('customers.')->group(function () {
    Route::view('/all', 'admin.customers.all-customers')->name('all');
    Route::view('/overview', 'admin.customers.customer-overview')->name('overview');
    Route::view('/security', 'admin.customers.customer-security')->name('security');
    Route::view('/billing', 'admin.customers.customer-details-billing')->name('billing');
});

// ================= Coupons & Discounts Routes =================
Route::prefix('discounts')->name('discounts.')->group(function () {
    Route::view('/all', 'admin.discounts.all-coupons')->name('all');
    Route::view('/add', 'admin.discounts.add-new-coupons')->name('add');
    Route::view('/active', 'admin.discounts.all-active-coupons')->name('active');
    Route::view('/expired', 'admin.discounts.all-expired-coupons')->name('expired');
});

// ================= Review Routes =================
Route::prefix('reviews')->name('reviews.')->group(function () {
    Route::view('/all', 'admin.review.all-review')->name('all');
    Route::view('/add', 'admin.review.add-review')->name('add');
});

// ================= Store Setting =================

Route::prefix('settings')->name('settings.')->group(function () {
    Route::view('/store-setting', 'admin.store-details.store-details')->name('store-detials');
});
