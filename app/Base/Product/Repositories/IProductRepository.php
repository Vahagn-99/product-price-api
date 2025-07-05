<?php

declare(strict_types=1);

namespace App\Base\Product\Repositories;

use App\Base\Product\Dto\GetPriceFilter;
use Illuminate\Pagination\LengthAwarePaginator;

interface IProductRepository
{
    /**
     * Получение всех продуктов в указанной валюте
     *
     * @param \App\Base\Product\Dto\GetPriceFilter $filter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     *
     * @throws \App\Base\Product\Exceptions\Currency
     */
    public function getAllConverted(GetPriceFilter $filter) : LengthAwarePaginator;
}
