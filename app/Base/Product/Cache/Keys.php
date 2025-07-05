<?php

declare(strict_types=1);

namespace App\Base\Product\Cache;

use App\Base\Product\Dto\GetPriceFilter;
use App\Models\Product;

class Keys
{
    /**
     * @var string
     */
    private const PREFIX = Product::class;

    //****************************************************************
    //************************ Ключи *****************************
    //****************************************************************

    /**
     * Получение ключа для получения всех продуктов по фильтру
     *
     * @param \App\Base\Product\Dto\GetPriceFilter $filter
     * @return string
     */
    public static function getAllConverted(GetPriceFilter $filter): string
    {
        return self::makeKey($filter->toJson());
    }


    //****************************************************************
    //************************ Теги **********************************
    //****************************************************************

    /**
     * Получение тега для получения всех продуктов по фильтру
     *
     * @return string
     */
    public static function getAllConvertedTag(): string
    {
        return self::PREFIX . ':getAllConverted';
    }

    //****************************************************************
    //************************ Support **********************************
    //****************************************************************

    /**
     * Создание ключа кеширования
     *
     * @param string $key
     * @return string
     */
    private static function makeKey(string $key) : string
    {
        return md5($key);
    }
}
