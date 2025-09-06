<?php
namespace App\Services\Payments;

class PaymentFactory {
    public static function make(): PaymentGateway {
        return match (config('payments.provider')) {
            'stripe' => app(StripeGateway::class),
            'payjp'  => app(PayJPGateway::class),
            default  => throw new \RuntimeException('Unknown payment provider'),
        };
    }
}
