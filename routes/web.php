<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Shop
|--------------------------------------------------------------------------
*/
Route::get('/', [ShopController::class, 'home'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');

/*
|--------------------------------------------------------------------------
| Customer (Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'place'])->name('checkout.place');

    Route::get('/my/orders', [CheckoutController::class, 'myOrders'])->name('orders.mine');
    Route::get('/my/orders/{order}', [CheckoutController::class, 'myOrderShow'])->name('orders.mine.show');
});

/*
|--------------------------------------------------------------------------
| Admin Auth  (IMPORTANT: must be BEFORE protected admin routes)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])
        ->middleware('guest')
        ->name('login');

    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('guest')
        ->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| Admin Panel (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::get('/', [AdminDashboardController::class, 'index'])->name('home');

    Route::resource('categories', AdminCategoryController::class);
    Route::resource('products', AdminProductController::class);

    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
});

require __DIR__ . '/auth.php';
