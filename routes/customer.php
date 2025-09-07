<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Shop\{AccountController, CartController, CheckoutController, PaymentController, ProductController as ShopProductController};

/* 認証（顧客） */
Route::controller(CustomerAuthController::class)->group(function () {
    Route::get('/customer/login',     'showLoginForm')->name('customer.login.form');
    Route::post('/customer/login',    'login')->middleware('throttle:login')->name('customer.login');
    Route::get('/customer/register',  'showRegisterForm')->name('customer.register.form');
    Route::post('/customer/register', 'register')->name('customer.register');
    Route::post('/customer/logout',   'logout')->name('customer.logout');
});

/* 購入フロー（テナント配下） */
Route::prefix('m/{merchant:slug}')
    ->middleware(['merchant'])
    ->name('shop.')
    ->group(function () {
        // 商品閲覧（例）
        Route::get('/', [ShopProductController::class,'index'])->name('index');

        // カート
        Route::get('cart',         [CartController::class,'show'])->name('cart.show');
        Route::post('cart/add',    [CartController::class,'add'])->name('cart.add');
        Route::post('cart/update', [CartController::class,'update'])->name('cart.update');
        Route::post('cart/remove', [CartController::class,'remove'])->name('cart.remove');

        // チェックアウト（customer ログイン必須にしたい場合は下の行に auth:customer を追加）
        Route::get('checkout',  [CheckoutController::class,'create'])->name('checkout.create');
        Route::post('checkout', [CheckoutController::class,'store'])
            // ->middleware('auth:customer')  // ← 必須にするなら有効化
            ->name('checkout.store');

        // 決済コールバック
        Route::post('checkout/pay',         [PaymentController::class,'pay'])->name('checkout.pay');
        Route::get('payment/{number}/success', [PaymentController::class,'success'])->name('payment.success');
        Route::get('payment/{number}/cancel',  [PaymentController::class,'cancel'])->name('payment.cancel');

        // 顧客マイページ（要ログイン）
        Route::middleware('auth:customer')->group(function () {
            Route::get('account',              [AccountController::class,'index'])->name('customer.account');
            Route::get('orders',               [AccountController::class,'orders'])->name('customer.orders');
            Route::get('orders/{number}',      [AccountController::class,'show'])->name('customer.orders.show');
            Route::post('orders/{number}/reorder', [AccountController::class,'reorder'])->name('customer.orders.reorder');
        });
    });
