<?php
namespace App\Services\Pricing;

use App\Models\ShippingMethod;

class ShippingCalculator
{
    public static function listActive(): array
    {
        // DBがあればDB優先、なければconfig
        if (class_exists(ShippingMethod::class) && ShippingMethod::query()->exists()) {
            return ShippingMethod::where('is_active', true)->get()
              ->map(fn($m)=>[
                'code'=>$m->code,'name'=>$m->name,'base_fee'=>$m->base_fee,'free_threshold'=>$m->free_threshold
              ])->keyBy('code')->toArray();
        }
        return collect(config('shipping.methods'))
            ->filter(fn($m)=>$m['active'] ?? false)
            ->map(fn($m,$code)=>['code'=>$code,'name'=>$m['name'],'base_fee'=>$m['base_fee'],'free_threshold'=>$m['free_threshold'] ?? null])
            ->keyBy('code')->toArray();
    }

    public static function compute(string $code, int $orderTotalIncl): array
    {
        $methods = self::listActive();
        $m = $methods[$code] ?? reset($methods);
        $fee = ($m['free_threshold'] && $orderTotalIncl >= $m['free_threshold']) ? 0 : (int)$m['base_fee'];
        return ['code'=>$m['code'], 'name'=>$m['name'], 'fee'=>$fee];
    }
}
