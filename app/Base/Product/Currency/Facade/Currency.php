<?php

declare(strict_types=1);


namespace App\Base\Product\Currency\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string convert(string $currency, string $field, string $as = 'price')
 *
 * @see \App\Base\Product\Currency\Convertor\ISQLConvertor
 */
class Currency extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return 'currency';
    }
}
