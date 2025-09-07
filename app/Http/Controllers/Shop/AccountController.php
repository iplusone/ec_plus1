<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Cart;
use App\Models\ProductVariant;

class AccountController extends Controller
{
    // マイページトップ（リンク集など）
    public function index(string $merchant)
    {
        $customer = Auth::guard('customer')->user();
        return view('shop.account.index', compact('customer'));
    }

    // 注文履歴
    public function orders(string $merchant)
    {
        $m = app('merchant'); // ResolveMerchant ミドルウェアでセット済み想定
        $customer = Auth::guard('customer')->user();

        $orders = Order::where('merchant_id', $m->id)
            ->where('customer_id', $customer->id)
            ->latest('id')
            ->paginate(20);

        return view('shop.account.orders', compact('orders'));
    }

    // 注文詳細
    public function show(string $merchant, string $number)
    {
        $m = app('merchant');
        $customer = Auth::guard('customer')->user();

        $order = Order::with('items')
            ->where('merchant_id', $m->id)
            ->where('customer_id', $customer->id)
            ->where('number', $number)
            ->firstOrFail();

        return view('shop.account.order_show', compact('order'));
    }

    // 再注文：在庫・所属チェックしながらカートへ復元
    public function reorder(Request $r, string $merchant, string $number)
    {
        $m = app('merchant');
        $customer = Auth::guard('customer')->user();

        $order = Order::with('items')
            ->where('merchant_id', $m->id)
            ->where('customer_id', $customer->id)
            ->where('number', $number)
            ->firstOrFail();

        $cart = Cart::firstOrCreate(
            ['id' => session()->getId()],
            ['merchant_id' => $m->id, 'currency' => 'JPY']
        );

        $added = 0; $skipped = [];
        foreach ($order->items as $it) {
            $variant = ProductVariant::with('product')->find($it->product_variant_id);
            if (!$variant || ($variant->product->merchant_id ?? null) !== $m->id) { $skipped[]=$it->sku; continue; }
            if (($variant->stock ?? 0) <= 0) { $skipped[]=$it->sku; continue; }

            $qty = min($it->qty, max(1, $variant->stock));
            $ci = $cart->items()->firstOrNew(['product_variant_id'=>$variant->id], [
                'product_id' => $variant->product_id,
                'price_amount' => $variant->price_amount,
            ]);
            $ci->qty += $qty;
            $ci->save();
            $added++;
        }

        $msg = $added>0 ? "再注文としてカートに追加しました（{$added}件）" : "追加できる商品がありませんでした";
        if ($skipped) $msg .= "／在庫等: ".implode(', ', $skipped);

        return redirect()->route('cart.show', ['merchant'=>$m->slug])->with('ok', $msg);
    }
}
