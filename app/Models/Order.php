<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $fillable = ['number','customer_id','status','subtotal_amount','tax_amount','shipping_amount','discount_amount','total_amount','currency'];
    public function items(){ return $this->hasMany(OrderItem::class); }
    public function payments(){ return $this->hasMany(Payment::class); }

    public function merchant(){ return $this->belongsTo(Merchant::class); }
}
