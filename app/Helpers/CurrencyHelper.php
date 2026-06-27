<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function format(float|int|string $amount, int $decimals = 0): string {
        return number_format((float) $amount, $decimals, '.', ' ') . ' lei';
    }
}
