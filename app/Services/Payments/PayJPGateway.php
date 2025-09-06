<?php
namespace App\Services\Payments;

use App\Models\Order;
use Payjp\Payjp; use Payjp\Charge;

class PayJPGateway implements PaymentGateway
{
    public function __construct()
    {
        Payjp::setApiKey(config('services.payjp.secret'));
    }

    public function createCheckout(Order $order): array
    {
        // シンプルにサーバー側チャージ（テスト用）。実運用は token をフロントで取得して送る。
        $charge = Charge::create([
            'amount' => $order->amount_total, // JPY 最小単位
            'currency' => 'jpy',
            'card' => 'tok_visa', // テストトークン（本番はJSで生成したトークンを使う）
            'capture' => true,
            'description' => "Order ".$order->number,
        ]);

        $order->update([
            'payment_provider'  => 'payjp',
            'payment_intent_id' => $charge->id,
            'payment_status'    => ($charge->paid && $charge->captured) ? 'paid' : 'pending',
        ]);

        // 直ちに成功扱い（テスト簡易）。UIは成功URLへ。
        return ['url' => route('payment.success', ['merchant'=>$order->merchant->slug,'number'=>$order->number]), 'id'=>$charge->id];
    }

    public function handleWebhook(string $payload, string $sigHeader): ?Order
    {
        // 省略（必要ならeventを検証して order を返す）
        return null;
    }
}
