<?php
// app/Http/Middleware/EnsureActiveMerchant.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureActiveMerchant
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user('merchant');        // auth:merchant のユーザ
        $merchant = $user?->merchant;              // User::merchant() リレーション必須
        //dd($user);
        //dd($merchant);

        if (!$user || !$merchant || !$merchant->is_active) {
            abort(403, 'マーチャントが無効です');
        }
        return $next($request);
    }
}
