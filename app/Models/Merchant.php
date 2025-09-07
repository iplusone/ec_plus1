<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model 
{

    protected $fillable = [
        'name',
        'name_kana',
        'code',
        'email',
        'phone',
        'zip',
        'address',
        'lat',
        'lng',
        'corporate_number',
        'registration_number',
        'is_active',
        'slug',
    ];
    protected $casts = [
        'is_active' => 'bool', 'lat'=>'float', 'lng'=>'float'
    ];

    public function users() { return $this->hasMany(\App\Models\User::class); }

}
