<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $fillable = ['name','email','password','role'];
    protected $hidden = ['password','remember_token'];

    public function isAdmin(): bool  { return $this->role === 'admin'; }
    public function isSeller(): bool { return $this->role === 'seller'; }

    public function merchants(){ return $this->belongsToMany(Merchant::class); }
}
