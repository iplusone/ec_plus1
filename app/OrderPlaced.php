<?php
namespace App\Mail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class OrderPlaced extends Mailable
{
    use Queueable;
    public function __construct(public Order $order) {}
    public function build(){
        return $this->subject("ご注文ありがとうございます ({$this->order->number})")
            ->view('mail.order_placed');
    }
}
