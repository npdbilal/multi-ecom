<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LanguageController as AdminLanguageController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PluginController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FirebaseAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Storefront routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Language switcher (session based)
Route::get('/language/{code}', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Cart (session based, works for guests)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{rowId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{rowId}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Firebase login page (Phone OTP / Email+Password / Google Sign-In).
    Route::get('/login', [FirebaseAuthController::class, 'showLogin'])->name('login');
    // Sign-up happens inside the Firebase login page (Email tab → Create Account).
    Route::get('/register', fn () => redirect()->route('login', ['mode' => 'signup']))->name('register');
});

// Verifies the Firebase ID token from the JS SDK and starts a Laravel session.
Route::post('/auth/firebase/verify', [FirebaseAuthController::class, 'verify'])->name('auth.firebase.verify');

Route::post('/logout', [FirebaseAuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Customer account
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

/*
|--------------------------------------------------------------------------
| Admin panel
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);

    // Theme & plugin management
    Route::get('/themes', [ThemeController::class, 'index'])->name('themes.index');
    Route::post('/themes/activate/{theme}', [ThemeController::class, 'activate'])->name('themes.activate');

    Route::get('/plugins', [PluginController::class, 'index'])->name('plugins.index');
    Route::post('/plugins/{plugin}/enable', [PluginController::class, 'enable'])->name('plugins.enable');
    Route::post('/plugins/{plugin}/disable', [PluginController::class, 'disable'])->name('plugins.disable');

    // Language & translation management
    Route::resource('languages', AdminLanguageController::class)->except(['show']);
    Route::post('/languages/{language}/default', [AdminLanguageController::class, 'makeDefault'])->name('languages.default');
    Route::post('/languages/{language}/toggle', [AdminLanguageController::class, 'toggle'])->name('languages.toggle');

    Route::get('/translations', [TranslationController::class, 'index'])->name('translations.index');
    Route::get('/translations/create', [TranslationController::class, 'create'])->name('translations.create');
    Route::post('/translations', [TranslationController::class, 'store'])->name('translations.store');
    Route::get('/translations/{translation}/edit', [TranslationController::class, 'edit'])->name('translations.edit');
    Route::put('/translations/{translation}', [TranslationController::class, 'update'])->name('translations.update');
    Route::delete('/translations/{translation}', [TranslationController::class, 'destroy'])->name('translations.destroy');
    Route::post('/translations/sync-missing', [TranslationController::class, 'syncMissing'])->name('translations.sync-missing');
});
