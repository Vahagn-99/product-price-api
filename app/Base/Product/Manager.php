<?php

declare(strict_types=1);

namespace App\Base\Product;

use App\Base\Product\Dto\GetPriceFilter;
use App\Base\Product\Repositories\IProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class Manager
{
    /**
     * @param \App\Base\Product\Repositories\IProductRepository $product_repository
     */
    public function __construct(
        private readonly IProductRepository $product_repository,
    ) {
        //
    }

    /**
     * Получение всех продуктов в указанной валюте
     *
     * @param \App\Base\Product\Dto\GetPriceFilter $filter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     *
     * @throws \App\Base\Product\Exceptions\Currency
     */
    public function getAllConverted(GetPriceFilter $filter): LengthAwarePaginator
    {
        return $this->product_repository->getAllConverted($filter);
    }
}
