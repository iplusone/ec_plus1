<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model {
  protected $fillable = ['code','name','base_fee','free_threshold','is_active'];
}
