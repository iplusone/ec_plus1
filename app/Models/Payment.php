<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $fillable = ['order_id','provider','status','amount','currency','transaction_id','payload'];
    protected $casts = ['payload'=>'array'];
    public function order(){ return $this->belongsTo(Order::class); }
}
