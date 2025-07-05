<?php

declare(strict_types=1);

namespace App\Base\Product\Currency\Convertor;

use App\Base\Product\Exceptions\Currency;

class SQLConvertor implements ISQLConvertor
{
    /**
     * Convertor constructor.
     *
     * @param array $config
     */
    public function __construct(private readonly array $config) {
        //
    }

    /**
     * Конвертация цены в указанную валюту для выборки записей из БД
     *
     * @param string $currency
     * @param string $field
     * @param string $as
     * @return string
     *
     * @throws \App\Base\Product\Exceptions\Currency
     */
    public function convert(string $currency, string $field, string $as = 'price'): string
    {
        if (! isset($this->config[$currency])) {
            throw Currency::not_found($currency);
        }

        $data = $this->config[$currency];

        if (! isset($data['rate'])) {
            throw Currency::missing_rate();
        }

        $rate = $data['rate'];
        $prefix_symbol = $data['prefix_symbol'] ?? "";
        $postfix_symbol = $data['postfix_symbol'] ?? "";

        return "CONCAT('$prefix_symbol', FORMAT($field / $rate, 2), '$postfix_symbol') as $as";
    }
}
