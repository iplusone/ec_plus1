<?php
namespace App\Services\Pricing;

class TaxCalculator
{
    public static function rateFor(string $taxClass): int
    {
        $rates = config('tax.rates');
        return $rates[$taxClass] ?? $rates['standard'];
    }

    /**
     * @param int    $priceAmount   単価（税抜 or 税込）
     * @param int    $qty
     * @param int    $rate          例: 10 or 8
     * @param string $priceMode     'tax_incl'|'tax_excl'
     * @return array [unit_excl, unit_tax, unit_incl, line_excl, line_tax, line_incl]
     */
    public static function compute(int $priceAmount, int $qty, int $rate, string $priceMode): array
    {
        if ($priceMode === 'tax_excl') {
            $unit_excl = $priceAmount;
            $unit_tax  = (int) floor($unit_excl * $rate / 100);
            $unit_incl = $unit_excl + $unit_tax;
        } else {
            // 税込 → 税抜を逆算（端数は切り捨て）
            $unit_incl = $priceAmount;
            $unit_excl = (int) floor($unit_incl * 100 / (100 + $rate));
            $unit_tax  = $unit_incl - $unit_excl;
        }
        $line_excl = $unit_excl * $qty;
        $line_tax  = $unit_tax * $qty;
        $line_incl = $unit_incl * $qty;

        return compact('unit_excl','unit_tax','unit_incl','line_excl','line_tax','line_incl');
    }
}
