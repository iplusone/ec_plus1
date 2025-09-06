<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\{Order, OrderItem, Payment, Customer};
use App\Support\CartSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create()
    {
        $cart = CartSession::resolve()->load('items.variant.product');
        abort_if($cart->items->isEmpty(), 400, 'カートが空です');
        $sum  = $cart->items->sum(fn($i) => $i->unit_price * $i->qty);
        return view('shop.checkout.create', compact('cart','sum'));
    }

    public function store(CheckoutRequest $req)
    {
        $cart = CartSession::resolve()->load('items.variant.product');
        abort_if($cart->items->isEmpty(), 400, 'カートが空です');

        $order = DB::transaction(function () use ($cart, $req) {
            // 顧客（存在すれば再利用）
            $customer = Customer::firstOrCreate(
                ['email'=>$req->input('email')],
                ['name'=>$req->input('name')]
            );

            $subtotal = $cart->items->sum(fn($i)=> $i->unit_price * $i->qty);
            $tax = 0; $shipping = 0; $discount = 0;
            $total = $subtotal + $tax + $shipping - $discount;

            $order = Order::create([
                'number'          => strtoupper(Str::ulid()),
                'customer_id'     => $customer->id,
                'status'          => 'pending',
                'subtotal_amount' => $subtotal,
                'tax_amount'      => $tax,
                'shipping_amount' => $shipping,
                'discount_amount' => $discount,
                'total_amount'    => $total,
                'currency'        => $cart->currency,
            ]);

            foreach ($cart->items as $ci) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_variant_id' => $ci->product_variant_id,
                    'name'               => $ci->variant->product->name,
                    'sku'                => $ci->variant->sku,
                    'qty'                => $ci->qty,
                    'unit_price'         => $ci->unit_price,
                    'tax_amount'         => 0,
                    'discount_amount'    => 0,
                    'line_total'         => $ci->unit_price * $ci->qty,
                ]);
                // 在庫減算したい場合：
                // $ci->variant()->decrement('stock', $ci->qty);
            }

            // 決済はまずモック（オーソリ済みとして作成）
            Payment::create([
                'order_id'       => $order->id,
                'provider'       => 'manual',
                'status'         => 'authorized',
                'amount'         => $order->total_amount,
                'currency'       => $order->currency,
                'transaction_id' => 'AUTH-'.Str::random(10),
                'payload'        => ['mock'=>true],
            ]);

            // カートクリア
            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('orders.thankyou', ['number'=>$order->number]);
    }

    public function thankyou(string $number)
    {
        return view('shop.checkout.thankyou', compact('number'));
    }
}
