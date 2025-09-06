<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $fillable = ['name','slug','description','is_active'];
    public function variants(){ return $this->hasMany(ProductVariant::class); }

    public function merchant(){ return $this->belongsTo(Merchant::class); }

    public function images(){ return $this->hasMany(\App\Models\ProductImage::class)->orderBy('sort'); }

}
