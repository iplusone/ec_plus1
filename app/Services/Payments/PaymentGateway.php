<?php
namespace App\Services\Payments;

use App\Models\Order;

interface PaymentGateway {
    /** プロバイダのCheckoutへリダイレクトするためのURL or セッションIDを返す */
    public function createCheckout(Order $order): array; // ['url' => ..., 'id' => ...]
    /** Webhookで支払い成功かを検証し、成功なら true */
    public function handleWebhook(string $payload, string $sigHeader): ?Order;
}
