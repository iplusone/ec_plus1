<?php
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Mail\OrderPlaced;
use App\Models\Order;
use App\Services\Payments\PaymentFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function pay(Request $r)
    {
        $m = app('merchant');
        $number = session('order_number') ?? $r->get('number');
        abort_unless($number, 400, '注文が見つかりません');

        $order = Order::where('number',$number)->where('merchant_id',$m->id)->firstOrFail();
        abort_if($order->status !== 'pending', 400, 'この注文は決済済みです');

        $pg = PaymentFactory::make();
        $session = $pg->createCheckout($order);

        // StripeはURLへリダイレクト、Pay.jp簡易は成功URLへ
        return redirect()->away($session['url']);
    }

    public function success(Request $r, string $merchant, string $number)
    {
        $order = Order::with('items')->where('number',$number)->firstOrFail();
        if ($order->status === 'paid') return view('shop.checkout.thankyou', compact('order'));

        // 1) 明細ごとの税計算
        $subtotalEx = 0; $tax10 = 0; $tax8 = 0; $totalIncl = 0;
        foreach ($order->items as $it) {
            $variant = $it->variant;
            $product = $variant->product ?? null;
            $taxClass = $variant->tax_class ?? $product->tax_class ?? 'standard';
            $priceMode = $variant->price_mode ?? $product->price_mode ?? 'tax_incl';
            $rate = TaxCalculator::rateFor($taxClass);

            $calc = TaxCalculator::compute($it->price_amount, $it->qty, $rate, $priceMode);

            // 注文明細へ保存（税率・税額・価格モード）
            $it->update([
                'tax_rate'   => $rate,
                'tax_amount' => $calc['line_tax'],
                'price_mode' => $priceMode,
            ]);

            $subtotalEx += $calc['line_excl'];
            if ($rate === 10) $tax10 += $calc['line_tax']; else $tax8 += $calc['line_tax'];
            $totalIncl += $calc['line_incl'];
        }

        // 2) 送料（選択なければ既定）
        $shippingCode = $order->shipping_method ?: config('shipping.default_method', 'flat');
        $ship = ShippingCalculator::compute($shippingCode, $totalIncl);
        $shippingFee = $ship['fee'];

        // 3) 合計確定
        $discount = $order->discount_amount ?? 0;
        $grand = $totalIncl + $shippingFee - $discount;

        // 4) 在庫引当 & ステータス確定
        DB::transaction(function () use ($order, $subtotalEx, $tax10, $tax8, $shippingFee, $grand, $ship) {
            foreach ($order->items as $it) {
                $aff = DB::table('product_variants')
                    ->where('id',$it->product_variant_id)
                    ->where('stock','>=',$it->qty)
                    ->decrement('stock', $it->qty);
                if ($aff === 0) abort(409, "在庫不足: {$it->sku}");
            }
            $order->update([
                'subtotal_excl_tax' => $subtotalEx,
                'tax_10_amount'     => $tax10,
                'tax_8_amount'      => $tax8,
                'shipping_fee'      => $shippingFee,
                'grand_total'       => $grand,
                'shipping_method'   => $ship['code'],
                'status'            => 'paid',
                'payment_status'    => 'paid',
                'amount_total'      => $grand, // 既存フィールドを総額用途に使うなら同期
            ]);
        });
        

        // カートを空に（セッションID基準・存在するなら）
        \App\Models\Cart::where('id', session()->getId())->first()?->items()->delete();

        Mail::to($order->email)->send(new OrderPlaced($order));
        return view('shop.checkout.thankyou', compact('order'));
    }

    public function cancel(string $merchant, string $number)
    {
        $order = Order::where('number',$number)->firstOrFail();
        // 必要なら status=cancelled
        return redirect()->route('checkout.create', ['merchant'=>$merchant])->with('ok','支払いをキャンセルしました');
    }

    /** Stripe Webhook (checkout.session.completed) */
    public function stripeWebhook(Request $r)
    {
        $payload = $r->getContent();
        $sig = $r->header('Stripe-Signature');
        try {
            $order = app(\App\Services\Payments\StripeGateway::class)->handleWebhook($payload, $sig);
            if (!$order) return response()->json(['ok'=>true]); // 対象外イベント

            // 二重実行ガード
            if ($order->status === 'paid') return response()->json(['ok'=>true]);

            // 在庫・確定
            DB::transaction(function () use ($order) {
                foreach ($order->items as $it) {
                    $aff = DB::table('product_variants')
                        ->where('id',$it->product_variant_id)
                        ->where('stock','>=',$it->qty)
                        ->decrement('stock', $it->qty);
                    if ($aff === 0) abort(409, "在庫不足: {$it->sku}");
                }
                $order->update(['status'=>'paid','payment_status'=>'paid']);
            });
            Mail::to($order->email)->send(new OrderPlaced($order));
            return response()->json(['ok'=>true]);
        } catch (\Throwable $e) {
            return response('Webhook Error', 400);
        }
    }
}
