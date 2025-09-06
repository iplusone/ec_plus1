<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
    protected $fillable = ['order_id','product_variant_id','name','sku','qty','unit_price','tax_amount','discount_amount','line_total'];
    public function order(){ return $this->belongsTo(Order::class); }
    public function variant(){ return $this->belongsTo(ProductVariant::class); }
}
