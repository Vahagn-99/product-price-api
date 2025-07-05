<?php

namespace App\Base\Product\Exceptions;

use Exception;

class Currency extends Exception
{
    /**
     * Валюта не найдена
     *
     * @param string $currency
     * @return self
     */
    public static function not_found(string $currency) : Currency
    {
        return new self("Валюта [{$currency}] не найдена");
    }

    /**
     * Валюта не содержит курса
     *
     * @return self
     */
    public static function missing_rate() : Currency
    {
        return new self("настройки валюты не содержит курса");
    }
}
