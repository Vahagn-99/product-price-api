<?php

declare(strict_types=1);

namespace App\Repository\Support;

use Spatie\LaravelData\Data;

class Pagination extends Data
{
    /**
     * Pagination constructor.
     *
     * @param int $per_page
     * @param array $columns
     * @param string $page_name
     * @param int $page
     * @param int $total
     */
    public function __construct(
        public int $per_page = 20,
        public array $columns = ['*'],
        public string $page_name = 'page',
        public int $page = 1,
        public int $total = 50
    ) {
        //
    }
}
