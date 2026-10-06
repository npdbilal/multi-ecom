<?php

use Illuminate\Support\Facades\Route;
use Plugins\MultiVendor\Http\Controllers\AdminVendorController;
use Plugins\MultiVendor\Http\Controllers\VendorController;
use Plugins\MultiVendor\Http\Controllers\VendorDashboardController;

/*
|--------------------------------------------------------------------------
| MultiVendor plugin routes
|--------------------------------------------------------------------------
|
| Loaded automatically when the plugin is enabled.
|
*/

// Public vendor registration (authenticated customers).
Route::prefix('vendor')->name('vendor.')->middleware('auth')->group(function () {
    Route::get('/register', [VendorController::class, 'showRegister'])->name('register');
    Route::post('/register', [VendorController::class, 'register']);

    // Approved-vendor area.
    Route::middleware('vendor')->group(function () {
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/products', [VendorDashboardController::class, 'products'])->name('products');
        Route::get('/products/create', [VendorDashboardController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [VendorDashboardController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}/edit', [VendorDashboardController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{product}', [VendorDashboardController::class, 'updateProduct'])->name('products.update');
        Route::get('/orders', [VendorDashboardController::class, 'orders'])->name('orders');
    });
});

// Admin vendor management.
Route::prefix('admin/vendors')->name('admin.vendors.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminVendorController::class, 'index'])->name('index');
    Route::post('/{vendor}/approve', [AdminVendorController::class, 'approve'])->name('approve');
    Route::post('/{vendor}/reject', [AdminVendorController::class, 'reject'])->name('reject');
    Route::post('/{vendor}/suspend', [AdminVendorController::class, 'suspend'])->name('suspend');
    Route::get('/settings', [AdminVendorController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminVendorController::class, 'updateSettings'])->name('settings.update');
});
