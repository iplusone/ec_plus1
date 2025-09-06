<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVariantRequest;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductVariantController extends Controller
{
    public function create(Product $product)
    {
        $variant = new ProductVariant(['currency' => 'JPY','stock' => 0]);
        return view('admin.variants.form', compact('product','variant'));
    }

    public function store(StoreVariantRequest $request)
    {
        $variant = ProductVariant::create($request->validated());
        return redirect()->route('admin.products.edit',$variant->product_id)
                         ->with('ok','バリアントを追加しました');
    }

    public function edit(ProductVariant $variant)
    {
        $product = $variant->product;
        return view('admin.variants.form', compact('product','variant'));
    }

    public function update(StoreVariantRequest $request, ProductVariant $variant)
    {
        $variant->update($request->validated());
        return redirect()->route('admin.products.edit',$variant->product_id)
                         ->with('ok','バリアントを更新しました');
    }

    public function destroy(ProductVariant $variant)
    {
        $pid = $variant->product_id;
        $variant->delete();
        return redirect()->route('admin.products.edit',$pid)
                         ->with('ok','バリアントを削除しました');
    }
}
