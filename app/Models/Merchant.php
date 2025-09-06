<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model {
  protected $fillable = ['name','slug','is_active'];
  public function users(){ return $this->belongsToMany(User::class); }
  public function products(){ return $this->hasMany(Product::class); }
}
