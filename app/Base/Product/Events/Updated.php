<?php

namespace App\Base\Product\Events;

use App\Base\Product\Events\Dto\ProductData;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Updated
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public ProductData $product_data)
    {
        //
    }
}
