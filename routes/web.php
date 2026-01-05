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
use App\Http\Controllers\Admin\AdminPaymentMethodController;
use App\Http\Controllers\Admin\AdminShippingController;

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountOrderController;
use App\Http\Controllers\AccountAddressController;
use App\Http\Controllers\AccountProfileController;
use App\Http\Controllers\AccountFavoriteController;

use App\Http\Controllers\HitpayController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;


/*
|--------------------------------------------------------------------------
| Public Shop (不需要登录)
|--------------------------------------------------------------------------
*/

Route::get('/', [ShopController::class, 'home'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/count', function () {

    if (auth()->check()) {
        $cart = \App\Models\Cart::where('user_id', auth()->id())->first();
    } else {
        $cart = \App\Models\Cart::where('session_id', session()->getId())->first();
    }

    $count = $cart?->items()?->count() ?? 0;

    return response()->json([
        'count' => $count,
    ]);
})->name('cart.count');

if (app()->environment('local')) {
    Route::get('/test-mail', function () {
        Mail::raw('This is a simple test email from Extech Ecommerce.', function ($message) {
            $message->to('test@example.com')
                ->subject('Test Email from Extech');
        });

        return 'Test mail sent!';
    });
}



/*
|--------------------------------------------------------------------------
| Customer (需要登录的功能：Cart + Checkout + Account
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {


    // Route::get('/cart/count', function () {
    //     $count = auth()->user()?->cart?->items()?->count() ?? 0;

    //     return response()->json([
    //         'count' => $count,
    //     ]);
    // })->name('cart.count');

    // Checkout 也要登录
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');
    Route::post('/checkout/place-order', [CheckoutController::class, 'store'])
        ->name('checkout.store');

    // Account 相关
    Route::prefix('account')->name('account.')->group(function () {

        Route::get('/', [AccountController::class, 'index'])
            ->name('index');

        // Orders 
        Route::get('/orders', [AccountOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AccountOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/complete', [AccountOrderController::class, 'markCompleted'])->name('orders.complete');

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

        // Profile
        Route::get('/profile', [AccountProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('/profile', [AccountProfileController::class, 'update'])
            ->name('profile.update');
        Route::delete('/profile', [AccountProfileController::class, 'destroy'])
            ->name('profile.destroy');

        // Favorites
        Route::get('/favorites', [AccountFavoriteController::class, 'index'])->name('favorites.index');
        Route::post('/favorites/{product}', [AccountFavoriteController::class, 'store'])
            ->middleware('auth')
            ->name('favorites.store');
        Route::delete('/favorites/{product}', [AccountFavoriteController::class, 'destroy'])
            ->middleware('auth')
            ->name('favorites.destroy');
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
    Route::get('/reports/export', [AdminReportController::class, 'export'])->name('reports.export');

    // Banner
    Route::resource('banners', AdminBannerController::class);

    // Payment Method
    Route::resource('payment-methods', AdminPaymentMethodController::class);

    // Shipping
    Route::get('/shipping', [AdminShippingController::class, 'index'])->name('shipping.index');
    Route::get('/shipping/create', [AdminShippingController::class, 'create'])->name('shipping.create');
    Route::post('/shipping', [AdminShippingController::class, 'store'])->name('shipping.store');
    Route::get('/shipping/{rate}/edit', [AdminShippingController::class, 'edit'])->name('shipping.edit');
    Route::put('/shipping/{rate}', [AdminShippingController::class, 'update'])->name('shipping.update');
});

/*
|--------------------------------------------------------------------------
| HitPay Payment Routes
|--------------------------------------------------------------------------
*/

// 用户点击「去付款」→ 生成 HitPay Checkout Link
Route::get('/pay/hitpay/{order}', [HitpayController::class, 'createPayment'])
    ->name('hitpay.pay');

// 付款完成后浏览器跳回
Route::get('/payment/hitpay/return', [HitpayController::class, 'handleReturn'])
    ->name('hitpay.return');

// HitPay 服务器 Webhook（必须允许未登录访问）
// Route::post('/payment/hitpay/webhook', [HitpayController::class, 'handleWebhook'])
//     ->name('hitpay.webhook');

require __DIR__ . '/auth.php';
