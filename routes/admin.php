<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.products.index'));
    Route::resource('products', ProductController::class);
    Route::resource('products.variants', ProductVariantController::class)
        ->shallow(); // /products/{product}/variants/{variant}
});


