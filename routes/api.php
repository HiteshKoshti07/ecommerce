<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\StoreSettingController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories', CategoryController::class)->names([
    'index' => 'api.categories.index'
]);


// Route::apiResource('products', ProductController::class)->names([
//     'index' => 'api.products.index'
// ]);

// Route::prefix('products')->name('api.products.')->group(function () {


//     // Custom route for collection-wise products
//     Route::get('/collection', [ProductController::class, 'getCollection'])
//         ->name('collection');
// });


Route::prefix('products')
    ->name('api.products.')
    ->controller(ProductController::class)
    ->group(function () {
        // custom route first
        Route::get('/collection', 'getCollection')->name('collection');

        // standard CRUD routes
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{product}', 'show')->name('show');
        Route::put('/{product}', 'update')->name('update');
        Route::delete('/{product}', 'destroy')->name('destroy');
    });

Route::apiResource('orders', OrderController::class)->names([
    'index' => 'api.orders.index'

]);
Route::apiResource('customers', CustomerController::class)->names([
    'index' => 'api.customers.index'
]);


Route::apiResource('stores', StoreSettingController::class)->names([
    'index' => 'api.stores.index'
]);




Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
