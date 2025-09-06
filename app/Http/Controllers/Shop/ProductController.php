<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('variants')->where('is_active', true)->paginate(12);
        return view('shop.products.index', compact('products'));
    }

    public function show(string $slug)
    {
        $product = Product::with('variants')->where('slug', $slug)->firstOrFail();
        return view('shop.products.show', compact('product'));
    }
}
