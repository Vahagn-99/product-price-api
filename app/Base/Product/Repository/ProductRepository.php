<?php

declare(strict_types=1);

namespace App\Base\Product\Repository;

use App\Base\Product\Currency\Facade\Currency;
use App\Base\Product\Dto\GetPriceFilter;
use App\Repository\Query;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements IProductRepository
{
    /**
     * ProductRepository constructor.
     *
     * @param \App\Repository\Query $query
     */
    public function __construct(
        private readonly Query $query,
    ) {
        //
    }

    //****************************************************************
    //************************ Получение *****************************
    //****************************************************************

    /**
     * Получение всех продуктов в указанной валюте
     *
     * @param \App\Base\Product\Dto\GetPriceFilter $filter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     *
     * @throws \App\Base\Product\Exceptions\Currency
     */
    public function getAllConverted(GetPriceFilter $filter) : LengthAwarePaginator
    {
        return $this->query->builder()
            ->select('id', 'title', DB::raw(Currency::convert($filter->currency, "price")))
            ->paginate($filter->pagination->per_page, $filter->pagination->columns, $filter->pagination->page_name, $filter->pagination->page, $filter->pagination->total);
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
