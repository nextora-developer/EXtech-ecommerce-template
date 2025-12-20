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
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminAddressController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminBannerController;


use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountOrderController;
use App\Http\Controllers\AccountAddressController;
use App\Http\Controllers\AccountProfileController;

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
Route::patch('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout.index');

/*
|--------------------------------------------------------------------------
| Customer (Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {

        Route::get('/', [AccountController::class, 'index'])
            ->name('index');

        // Orders 
        Route::get('/orders', [AccountOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AccountOrderController::class, 'show'])->name('orders.show');

        // Address
        Route::get('/addresses', [AccountAddressController::class, 'index'])
            ->name('address.index');
        Route::get('/addresses/create', [AccountAddressController::class, 'create'])
            ->name('address.create');
        Route::post('/addresses', [AccountAddressController::class, 'store'])
            ->name('address.store');
        Route::get('/addresses/{address}/edit', [AccountAddressController::class, 'edit'])
            ->name('address.edit');
        Route::put('/addresses/{address}', [AccountAddressController::class, 'update'])
            ->name('address.update');
        Route::delete('/addresses/{address}', [AccountAddressController::class, 'destroy'])
            ->name('address.destroy');
        Route::put('/addresses/{address}/default', [AccountAddressController::class, 'setDefault'])
            ->name('address.set-default');

        //Profile
        Route::get('/profile', [AccountProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('/profile', [AccountProfileController::class, 'update'])
            ->name('profile.update');
        Route::delete('/profile', [AccountProfileController::class, 'destroy'])
            ->name('profile.destroy');
    });
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
    Route::patch('products/{product}/toggle', [AdminProductController::class, 'toggle'])
        ->name('products.toggle');


    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    Route::resource('users', AdminUserController::class)
        ->only(['index', 'show', 'edit', 'update']);


    // 地址：新增依附 user，其他用 address id
    Route::get('users/{user}/addresses/create', [AdminAddressController::class, 'create'])
        ->name('addresses.create');
    Route::post('users/{user}/addresses', [AdminAddressController::class, 'store'])
        ->name('addresses.store');

    Route::get('addresses/{address}/edit', [AdminAddressController::class, 'edit'])
        ->name('addresses.edit');
    Route::put('addresses/{address}', [AdminAddressController::class, 'update'])
        ->name('addresses.update');

    Route::delete('addresses/{address}', [AdminAddressController::class, 'destroy'])
        ->name('addresses.destroy');

    Route::post('addresses/{address}/make-default', [AdminAddressController::class, 'makeDefault'])
        ->name('addresses.make-default');

    // Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [AdminReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/products', [AdminReportController::class, 'products'])->name('reports.products');
    Route::get('/reports/orders', [AdminReportController::class, 'orders'])->name('reports.orders');
    Route::get('/reports/customers', [AdminReportController::class, 'customers'])->name('reports.customers');

    // Banner
    Route::resource('banners', AdminBannerController::class);
});

require __DIR__ . '/auth.php';
