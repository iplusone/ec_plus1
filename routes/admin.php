<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DashboardController,
    MerchantController,
    UserController,
    ProductController as AdminProductController,
    ProductVariantController as AdminProductVariantController,
    OrderController as AdminOrderController
};
use App\Http\Controllers\Admin\MerchantUserController;

/*
|--------------------------------------------------------------------------
| Platform Admin（サイト運営者）
|--------------------------------------------------------------------------
| guard:admin 専用。全商社（merchant）を管理。
| ※ admin のログイン/ログアウトルートは admin.php の外（web.phpやauth用ファイル）で定義。
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth:admin'])
    ->group(function () {

        // ダッシュボード（必要に応じて products.index へのリダイレクトに変更可）
        Route::get('/', [DashboardController::class, 'index'])->name('home');
        // 例: Route::get('/', fn () => redirect()->route('admin.products.index'))->name('home');

        // ▼▼▼ ここを seller から merchant-users に置換 ▼▼▼
        Route::resource('merchant-users', MerchantUserController::class)->except(['show','destroy']);
        Route::post('merchant-users/{user}/toggle', [MerchantUserController::class, 'toggle'])
            ->name('merchant-users.toggle');

        // マーチャント管理（作成/停止/設定）
        Route::resource('merchants', MerchantController::class)
            ->only(['index','create','store','edit','update']);

        Route::post('merchants/{merchant}/toggle', [MerchantController::class, 'toggle'])
            ->name('merchants.toggle');

        // プラットフォーム全体のユーザ管理
        Route::resource('users', UserController::class);

        // プラットフォーム全体の商品/注文（横断参照）
        Route::resource('products', AdminProductController::class)->only(['index','show','destroy']);

        // ネスト: /admin/products/{product}/variants/{variant}
        // shallow により単体参照は /admin/variants/{variant}
        Route::resource('products.variants', AdminProductVariantController::class)->shallow();

        // 注文（横断）
        Route::resource('orders', AdminOrderController::class)->only(['index','show','update']);
    });
