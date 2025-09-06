<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model {
    protected $fillable = ['product_id','sku','price_amount','currency','stock'];
    public function product(){ return $this->belongsTo(Product::class); }
}
