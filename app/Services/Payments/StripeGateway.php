<?php
namespace App\Services\Payments;

use App\Models\Order;
use Stripe\Stripe; use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Webhook;

class StripeGateway implements PaymentGateway
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckout(Order $order): array
    {
        $session = CheckoutSession::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($order->currency ?? 'jpy'),
                    'product_data' => ['name' => "Order ".$order->number],
                    'unit_amount' => $order->amount_total, // JPYは最小単位=1円
                ],
                'quantity' => 1,
            ]],
            'client_reference_id' => (string)$order->id,
            'success_url' => route('payment.success', ['merchant'=>$order->merchant->slug, 'number'=>$order->number]).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('payment.cancel',  ['merchant'=>$order->merchant->slug, 'number'=>$order->number]),
        ]);

        $order->update([
            'payment_provider'  => 'stripe',
            'payment_intent_id' => $session->payment_intent ?? null,
            'payment_status'    => 'pending',
        ]);

        return ['url' => $session->url, 'id' => $session->id];
    }

    public function handleWebhook(string $payload, string $sigHeader): ?Order
    {
        $endpointSecret = config('services.stripe.webhook');
        $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->client_reference_id ?? null;
            $order = $orderId ? Order::find($orderId) : null;
            return $order;
        }
        return null;
    }
}
