<?php

use Illuminate\Support\Facades\Route;

/*
|　ガードは必ず明示：auth:admin / auth:seller / auth:customer。
|
|　マルチテナント：{merchant:slug} + merchant ミドルウェア（ResolveMerchant）で現在テナントを解決。
|
|　単社モード：/ を既定 merchant へリダイレクト。.env に DEFAULT_MERCHANT_SLUG=default を用意。
|
|　ログインURL：/admin/login、/seller/login、/customer/login を分離。
|
|　**管理者（Platform Admin）**は全商社を横断、**販売者（Seller）**は自社のみにスコープ。
|
|　購入フローは /m/{merchant} 配下で商品→カート→チェックアウト→サンクスまで完結。
|
|　「チェックアウト時は customer ログイン必須」にしたい場合、/checkout に auth:customer を付ければOK。
|
|--------------------------------------------------------------------------
| 認証（ガード明示）— 管理者 / 販売者 / 購入者
|--------------------------------------------------------------------------
| いずれも guard を明示して使う想定
| ログインフォームは GET、実処理は POST、ログアウトは POST
*/

/** Admin 認証 */
Route::controller(\App\Http\Controllers\Auth\AdminLoginController::class)->group(function () {
    Route::get('/admin/login', 'showLoginForm')->name('admin.login.form');
    Route::post('/admin/login', 'login')->name('admin.login');
    Route::post('/admin/logout', 'logout')->name('admin.logout');               // uses guard:admin
});

/** Seller 認証（販売者はログイン後に複数 merchant を切替可能想定） */
Route::controller(\App\Http\Controllers\Auth\SellerLoginController::class)->group(function () {
    Route::get('/seller/login', 'showLoginForm')->name('seller.login.form');
    Route::post('/seller/login', 'login')->name('seller.login');
    Route::post('/seller/logout', 'logout')->name('seller.logout');             // uses guard:seller
});

/** Customer 認証（購入者） */
Route::controller(\App\Http\Controllers\Auth\CustomerAuthController::class)->group(function () {
    Route::get('/customer/login', 'showLoginForm')->name('customer.login.form');
    Route::post('/customer/login', 'login')->name('customer.login');
    Route::get('/customer/register', 'showRegisterForm')->name('customer.register.form');
    Route::post('/customer/register', 'register')->name('customer.register');
    Route::post('/customer/logout', 'logout')->name('customer.logout');         // uses guard:customer
    // （必要ならパスワードリセット系もここに）
});


/*
|--------------------------------------------------------------------------
| Platform Admin（サイト運営者）
|--------------------------------------------------------------------------
| guard:admin 専用。全商社（merchant）を管理。
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth:admin'])
    ->group(function () {

        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('home');

        // マーチャント管理（作成/停止/設定）
        Route::resource('merchants', \App\Http\Controllers\Admin\MerchantController::class);

        // 販売者ユーザ（users）管理
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

        // 全体横断の商品/注文（プラットフォーム視点での参照・操作が必要なら）
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->only(['index','show','destroy']);
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index','show','update']);
    });


/*
|--------------------------------------------------------------------------
| Seller（販売者用コンソール）
|--------------------------------------------------------------------------
| guard:seller 専用。URLに {merchant:slug} を含め、ミドルウェア 'merchant' で現在テナントを解決。
| ※ ResolveMerchant ミドルウェアで app('merchant') に詰める設計。
*/
Route::prefix('seller/{merchant:slug}')
    ->name('seller.')
    ->middleware(['auth:seller','merchant'])
    ->group(function () {

        Route::get('/', [\App\Http\Controllers\Seller\DashboardController::class,'index'])
            ->name('home');

        // 自社(=現在 merchant)の商品・バリアント・注文を操作
        Route::resource('products', \App\Http\Controllers\Seller\ProductController::class);
        Route::resource('products.variants', \App\Http\Controllers\Seller\ProductVariantController::class)->shallow();

        Route::resource('orders', \App\Http\Controllers\Seller\OrderController::class)
            ->only(['index','show','update']);

        // 在庫・価格一括更新など（任意）
        Route::post('products/bulk/update', [\App\Http\Controllers\Seller\ProductBulkController::class,'update'])
            ->name('products.bulk.update');
    });


/*
|--------------------------------------------------------------------------
| Storefront（購入フロー：商品一覧〜カート〜注文）
|--------------------------------------------------------------------------
| guard は不要（閲覧）。チェックアウトで customer 認証/作成を使う設計も可。
| 常に 'merchant' ミドルウェアで現在テナントを解決。
*/
Route::prefix('m/{merchant:slug}')
    ->middleware(['merchant'])
    ->group(function () {
        // 商品閲覧
        Route::get('/', [\App\Http\Controllers\Shop\ProductController::class,'index'])->name('shop.index');
        Route::get('/products/{slug}', [\App\Http\Controllers\Shop\ProductController::class,'show'])->name('shop.product.show');

        // カート
        Route::get('/cart', [\App\Http\Controllers\Shop\CartController::class,'show'])->name('cart.show');
        Route::post('/cart/add', [\App\Http\Controllers\Shop\CartController::class,'add'])->name('cart.add');
        Route::post('/cart/update', [\App\Http\Controllers\Shop\CartController::class,'update'])->name('cart.update');
        Route::post('/cart/remove', [\App\Http\Controllers\Shop\CartController::class,'remove'])->name('cart.remove');

        // チェックアウト（認証必須にするなら auth:customer を追加）
        Route::get('/checkout', [\App\Http\Controllers\Shop\CheckoutController::class,'create'])->name('checkout.create');
        Route::post('/checkout', [\App\Http\Controllers\Shop\CheckoutController::class,'store'])->name('checkout.store');

        // 完了
        Route::get('/orders/thankyou/{number}', [\App\Http\Controllers\Shop\CheckoutController::class,'thankyou'])
            ->name('orders.thankyou');

        // 顧客マイページ系（任意）— 保護したい場合は auth:customer を付与
        Route::middleware('auth:customer')->group(function(){
            Route::get('/account', [\App\Http\Controllers\Shop\AccountController::class,'index'])->name('customer.account');
            Route::get('/orders',  [\App\Http\Controllers\Shop\AccountController::class,'orders'])->name('customer.orders');
            Route::get('/orders/{number}', [\App\Http\Controllers\Shop\AccountController::class,'show'])->name('customer.orders.show');
        });
    });


/*
|--------------------------------------------------------------------------
| 単社モードのトップ（/ → 既定 merchant へ委譲）
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $default = config('app.default_merchant_slug', 'default');
    return redirect()->route('shop.index', ['merchant' => $default]);
})->name('root');


/*
|--------------------------------------------------------------------------
| 健康チェック / 監視向け
|--------------------------------------------------------------------------
*/
Route::get('/health', fn() => response()->json(['ok' => true, 'ts' => now()->toIso8601String()]))->name('health');


// Seller: 画像アップロード対応（1枚サムネ＋複数画像）
Route::post('products/{product}/thumbnail', [\App\Http\Controllers\Seller\ProductMediaController::class,'uploadThumbnail'])
  ->name('products.thumbnail.upload');
Route::post('products/{product}/images', [\App\Http\Controllers\Seller\ProductMediaController::class,'uploadImages'])
  ->name('products.images.upload');

