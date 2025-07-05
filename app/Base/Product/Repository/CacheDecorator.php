<?php

declare(strict_types=1);

namespace App\Base\Product\Repository;

use App\Base\Product\Cache\Keys;
use App\Base\Product\Dto\GetPriceFilter;
use Cache;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Декоратор кэша при работе с репозиторием продуктов
 */
class CacheDecorator implements IProductRepository
{
    private const GET_ALL_CONVERTED_TTL = 60;

    /**
     * CachedProductRepository constructor.
     * @param \App\Base\Product\Repository\ProductRepository $actual
     */
    public function __construct(
        private readonly ProductRepository $actual,
    ) {
       //
    }

    //****************************************************************
    //************************ Получение *****************************
    //****************************************************************

    /**
     * Получение всех продуктов в указанной валюте из кэша
     *
     * @param \App\Base\Product\Dto\GetPriceFilter $filter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     *
     * @throws \App\Base\Product\Exceptions\Currency
     */
    public function getAllConverted(GetPriceFilter $filter) : LengthAwarePaginator
    {
       return Cache::tags(Keys::getAllConvertedTag())->remember(Keys::getAllConverted($filter), self::GET_ALL_CONVERTED_TTL, function () use ($filter) {
            return $this->actual->getAllConverted($filter);
        });
    }

    //****************************************************************
    //************************ Получение с джоинами ******************
    //****************************************************************

    //****************************************************************
    //************************ Проверки ******************************
    //****************************************************************

    //****************************************************************
    //********************* Работа с билдерами ***********************
    //****************************************************************
}
