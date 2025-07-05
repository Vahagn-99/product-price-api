<?php

declare(strict_types=1);

namespace App\Base\Product\Converters;

use App\Base\Product\Exceptions\Currency;
use DB;
use Illuminate\Contracts\Database\Query\Expression;

class Convertor
{
    /**
     * Конвертация цены в указанную валюту
     *
     * @param string $currency
     * @param string $field
     * @param string $as
     * @return \Illuminate\Contracts\Database\Query\Expression
     *
     * @throws \App\Base\Product\Exceptions\Currency
     */
    public static function convert(string $currency, string $field, string $as = 'price'): Expression
    {
        $config = config('app_currency.values', []);

        if (! isset($config[$currency])) {
            throw Currency::not_found($currency);
        }

        $data = $config[$currency];

        if (! isset($data['rate'])) {
            throw Currency::missing_symbol_or_rate();
        }

        $rate = $data['rate'];
        $prefix_symbol = $data['prefix_symbol'] ?? "";
        $postfix_symbol = $data['postfix_symbol'] ?? "";

        return DB::raw("CONCAT('$prefix_symbol', FORMAT($field / $rate, 2), '$postfix_symbol') as $as");
    }
}
