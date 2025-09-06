<?php
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Mail\OrderPlaced;
use App\Models\{Cart, Order, OrderItem, Customer, CustomerAddress};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function create() {
        $m = app('merchant');
        $cart = Cart::with('items.variant.product')->where('id',session()->getId())->firstOrFail();
        abort_unless($cart->merchant_id === $m->id, 400);
        $items = $cart->items;
        $total = $items->sum(fn($i)=> $i->qty * $i->price_amount);
        return view('shop.checkout.form', compact('cart','items','total'));
    }

    public function store(Request $r) {
        $m = app('merchant');

        $data = $r->validate([
            'email'  => ['required','email'],
            'name'   => ['nullable','string','max:100'],
            'postal' => ['nullable','string','max:16'],
            'pref'   => ['nullable','string','max:32'],
            'city'   => ['nullable','string','max:64'],
            'line1'  => ['nullable','string','max:128'],
            'line2'  => ['nullable','string','max:128'],
            'tel'    => ['nullable','string','max:32'],
            'create_account' => ['sometimes','boolean'],
            'password' => ['nullable','string','min:8'],
        ]);

        $cart = Cart::with('items.variant.product')->where('id',session()->getId())->lockForUpdate()->firstOrFail();
        abort_unless($cart->merchant_id === $m->id, 400);
        $items = $cart->items;
        abort_if($items->isEmpty(), 400, 'カートが空です');

        return DB::transaction(function () use ($m,$data,$cart,$items) {

            // 顧客の確定（既存 or 新規）
            $customer = Customer::where('email',$data['email'])->first();
            if (!$customer && ($data['create_account'] ?? false) && !empty($data['password'])) {
                $customer = Customer::create([
                    'email'=>$data['email'],
                    'name'=>$data['name'] ?? null,
                    'password'=>Hash::make($data['password']),
                ]);
            }

            // 在庫引当（variants.stock を減算）— 競合対策
            foreach ($items as $ci) {
                $aff = DB::table('product_variants')
                    ->where('id',$ci->product_variant_id)
                    ->where('stock','>=',$ci->qty)
                    ->decrement('stock', $ci->qty);
                if ($aff === 0) abort(409, "在庫不足: {$ci->variant->sku}");
            }

            $number = strtoupper(Str::random(10));
            $order = Order::create([
                'number'=>$number,
                'merchant_id'=>$m->id,
                'customer_id'=>$customer?->id,
                'email'=>$data['email'],
                'status'=>'paid',         // 決済連携前は 'pending' にしてもOK
                'amount_total'=>$items->sum(fn($i)=> $i->qty * $i->price_amount),
                'ship_to'=>json_encode([
                    'name'=>$data['name'] ?? null,
                    'postal'=>$data['postal'] ?? null,
                    'pref'=>$data['pref'] ?? null,
                    'city'=>$data['city'] ?? null,
                    'line1'=>$data['line1'] ?? null,
                    'line2'=>$data['line2'] ?? null,
                    'tel'=>$data['tel'] ?? null,
                ], JSON_UNESCAPED_UNICODE),
            ]);

            foreach ($items as $ci) {
                OrderItem::create([
                    'order_id'=>$order->id,
                    'product_id'=>$ci->product_id,
                    'product_variant_id'=>$ci->product_variant_id,
                    'sku'=>$ci->variant->sku,
                    'name'=>$ci->variant->product->name,
                    'price_amount'=>$ci->price_amount,
                    'qty'=>$ci->qty,
                    'subtotal'=>$ci->qty * $ci->price_amount,
                ]);
            }

            // カートを空に
            $cart->items()->delete();

            // メール通知（Mailpit）
            Mail::to($order->email)->send(new OrderPlaced($order));

            return redirect()->route('orders.thankyou', ['merchant'=>$m->slug,'number'=>$order->number]);
        });
    }

    public function thankyou(string $merchant, string $number) {
        $order = Order::where('number',$number)->firstOrFail();
        return view('shop.checkout.thankyou', compact('order'));
    }
}
