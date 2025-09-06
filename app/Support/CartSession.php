<?php

namespace App\Support;

use App\Models\Cart;
use Illuminate\Support\Str;

class CartSession
{
    public static function resolve(): Cart
    {
        $id = request()->cookie('cart_id');
        if (!$id) {
            $id = (string) Str::uuid();
        }
        $cart = Cart::firstOrCreate(['id'=>$id], ['currency'=>'JPY']);
        // 次回以降も使えるよう、レスポンス時にクッキーを付与するために保存
        app()->terminating(function() use ($id) {
            cookie()->queue(cookie()->forever('cart_id', $id));
        });
        return $cart;
    }
}
