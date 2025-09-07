<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Merchant\{
    DashboardController,
    ProductController,
    OrderController
};

Route::prefix('merchant')
    ->name('merchant.')
    ->middleware(['auth:merchant','ensure.active.merchant']) // is_active=1 などの確認
    ->group(function () {
        // ダッシュボード
        Route::get('/', [DashboardController::class, 'index'])->name('home');

        // 自社商品の管理
        Route::resource('products', ProductController::class);

        // 受注管理
        Route::resource('orders', OrderController::class)
            ->only(['index','show','update']);
    });
