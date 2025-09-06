<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withCount('variants')->latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $product = new Product(['is_active' => true]);
        return view('admin.products.form', compact('product'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $product = Product::create($data);

        return redirect()->route('admin.products.edit', $product)
                         ->with('ok', '商品を作成しました');
    }

    public function edit(Product $product)
    {
        $product->load('variants');
        return view('admin.products.form', compact('product'));
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $product->update($data);

        return back()->with('ok', '商品を更新しました');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
                         ->with('ok', '商品を削除しました');
    }
}
