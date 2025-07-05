<?php

declare(strict_types=1);


namespace App\Base\Product\Events\Dto;

use Spatie\LaravelData\Data;

class ProductData extends Data
{
    public function __construct(
        public int $id,
        public string $event_dispatched_at,
        public string $event_dispatched_by,
        // Остальные поля для обработки метрик
    ) {
        //
    }
}
