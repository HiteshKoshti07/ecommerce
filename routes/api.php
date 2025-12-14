<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\StoreSettingController;
use App\Http\Controllers\Api\ProductReviewController;

/*
|--------------------------------------------------------------------------
| Public API Routes (No Token Required)
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
|  MAIN API ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['throttle:100,1'])->group(function () {



    Route::middleware(['auth', 'is.admin'])->group(function () {

        Route::apiResource('categories', CategoryController::class)->names([
            'index' => 'api.categories.index'
        ]);


        // Orders
        Route::get('/orders', [OrderController::class, 'index'])->name('api.orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('api.orders.show');
        Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('api.orders.update-status');
        Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('api.orders.destroy');
    });



    Route::post('/orders', [OrderController::class, 'store'])->name('api.orders.store');


    // Categories
    // Route::apiResource('categories', CategoryController::class)->names([
    //     'index' => 'api.categories.index'
    // ]);


    // Products (custom + standard CRUD)
    Route::prefix('products')
        ->name('api.products.')
        ->controller(ProductController::class)
        ->group(function () {
            // Custom
            Route::get('/collection', 'getCollection')->name('collection');
            Route::post('/upsell', 'upsellProducts')->name('upsellProducts');

            // CRUD
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{product}', 'show')->name('show');
            Route::put('/{product}', 'update')->name('update');
            Route::delete('/{product}', 'destroy')->name('destroy');
        });



    // Customers
    // Route::apiResource('customers', CustomerController::class)->names([
    //     'index' => 'api.customers.index'
    // ]);

    // // Stores
    // Route::apiResource('stores', StoreSettingController::class)->names([
    //     'index' => 'api.stores.index'
    // ]);


    // Review stats
    Route::get('/reviewsData', [ProductReviewController::class, 'reviewWithCount'])
        ->name('api.reviews.data');



    Route::apiResource('reviews', ProductReviewController::class)->names(['index' => 'api.reviews.index',]);

    // Update order status
    // Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
});
