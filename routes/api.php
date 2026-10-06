<?php

use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Lightweight read-only API for the storefront. Extend as needed.
|
*/

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::get('/products', [ProductApiController::class, 'index'])->name('products.index');
    Route::get('/products/{product:slug}', [ProductApiController::class, 'show'])->name('products.show');
    Route::get('/languages', [ProductApiController::class, 'languages'])->name('languages');
});
