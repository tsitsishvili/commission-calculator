<?php

declare(strict_types=1);

namespace App\Helper;

use App\Enums\Currency;

class Rounding
{
    public static function roundUp(float $amount, Currency $currency): string
    {
        $precision = match ($currency) {
            Currency::JPY => 0,
            default => 2,
        };

        $multiplier = pow(10, $precision);

        return number_format(ceil($amount * $multiplier) / $multiplier, $precision, '.', '');
    }
}
