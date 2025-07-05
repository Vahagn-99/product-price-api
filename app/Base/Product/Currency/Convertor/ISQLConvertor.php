<?php

namespace App\Base\Product\Currency\Convertor;

interface ISQLConvertor
{
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
    public function convert(string $currency, string $field, string $as = 'price') : string;
}