<?php

use App\Helpers\CurrencyHelper;

if(!function_exists('money')) {
    function money(float|int|string $amount, int $decimals = 0): string{
        return CurrencyHelper::format($amount, $decimals);
    }
}
