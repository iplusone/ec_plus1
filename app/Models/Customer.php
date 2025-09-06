<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $fillable = ['email','name','password'];
    protected $hidden = ['password','remember_token'];
}
