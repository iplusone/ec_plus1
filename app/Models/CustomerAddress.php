<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class CustomerAddress extends Model {
  protected $fillable = [
        'customer_id',
        'name',
        'postal',
        'pref',
        'city',
        'line1',
        'line2',
        'tel'
    ];
  public function customer(){ return $this->belongsTo(\App\Models\Customer::class); }
}
