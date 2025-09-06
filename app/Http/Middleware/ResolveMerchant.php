<?php

namespace App\Http\Middleware;

use App\Models\Merchant;
use Closure;

class ResolveMerchant
{
    public function handle($request, Closure $next)
    {
        $slug = $request->route('merchant') ?? config('app.default_merchant_slug');
        $merchant = Merchant::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        abort_unless($merchant, 404, 'Merchant not found');

        app()->instance('merchant', $merchant);
        view()->share('currentMerchant', $merchant);

        return $next($request);
    }
}
