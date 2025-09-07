<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\MerchantLoginController;

// トップ：とりあえず merchant ログインへ（好きに変更OK）
Route::get('/', fn() => redirect()->route('merchant.login.form'));

// ===== Admin 認証（公開） =====
Route::controller(AdminLoginController::class)
    // set.guard を使うなら 'admin' を渡す（別に無くてもOK）
    ->middleware('set.guard:admin')
    ->group(function () {
        Route::get('/admin/login',  'showLoginForm')->name('admin.login.form');
        Route::post('/admin/login', 'login')->middleware('throttle:login')->name('admin.login');
        Route::post('/admin/logout','logout')->name('admin.logout');
    });

// ===== Merchant 認証（公開） =====
Route::prefix('merchant')->name('merchant.')->controller(MerchantLoginController::class)
    // set.guard を使うなら 'merchant'（※複数形じゃない）
    ->middleware('set.guard:merchant')
    ->group(function () {
        Route::get('login',  'showLoginForm')->name('login.form');   // GET  /merchant/login
        Route::post('login', 'login')->middleware('throttle:login')->name('login'); // POST /merchant/login
        Route::post('logout','logout')->name('logout');              // POST /merchant/logout
    });

// 役割ごとのルート
require __DIR__.'/admin.php';
require __DIR__.'/merchant.php';
require __DIR__.'/customer.php';
