<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Merchant;

class ProductController extends Controller
{
    public function index(Merchant $merchant)
    {
        $products = $merchant->products()->latest()->paginate(20);
        return view('seller.products.index', compact('merchant','products'));
    }

    public function create(Merchant $merchant)
    {
        return view('seller.products.create', compact('merchant'));
    }

    public function store(Request $request, Merchant $merchant)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $product = $merchant->products()->create($data);

        return redirect()->route('seller.products.index', $merchant->slug)
            ->with('ok', '商品を登録しました');
    }

    public function edit(Merchant $merchant, Product $product)
    {
        abort_unless($product->merchant_id === $merchant->id, 403);
        return view('seller.products.edit', compact('merchant','product'));
    }

    public function update(Request $request, Merchant $merchant, Product $product)
    {
        abort_unless($product->merchant_id === $merchant->id, 403);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($data);

        return redirect()->route('seller.products.index', $merchant->slug)
            ->with('ok','商品を更新しました');
    }

    public function destroy(Merchant $merchant, Product $product)
    {
        abort_unless($product->merchant_id === $merchant->id, 403);
        $product->delete();
        return back()->with('ok','商品を削除しました');
    }
}
