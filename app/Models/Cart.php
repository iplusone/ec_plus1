<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Cart extends Model {
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','customer_id','currency'];
    public function items(){ return $this->hasMany(CartItem::class); }

    public function merchant(){ return $this->belongsTo(Merchant::class); }
}
