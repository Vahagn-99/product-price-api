<?php

declare(strict_types=1);

namespace App\Base\Product\Dto;

use App\Repository\Pagination;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class GetPriceFilter extends Data
{
    /**
     * Constructor GetPriceFilter
     *
     * @param string $currency
     * @param \App\Repository\Pagination $pagination
     */
    public function __construct(
        public string $currency = 'rub',
        public Pagination $pagination = new Pagination(),
    ) {
        $this->currency = mb_strtolower($this->currency);
    }

    /**
     * Валидация данных
     *
     * @return array
     */
    public static function rules() : array
    {
        return [
            'currency' => [
                'nullable',
                'string',
                Rule::in(array_keys(config('app_currency.values')))
            ],
            'pagination' => ['nullable', 'array'],
        ];
    }
}
