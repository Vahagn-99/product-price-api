<?php

namespace App\Base\System\Events;

use App\Base\System\Events\Dto\SystemLevelErrorOccurredDto;
use Illuminate\Foundation\Events\Dispatchable;

class SystemLevelErrorOccurred
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(public SystemLevelErrorOccurredDto $system_level_error_occurred_dto)
    {
        //
    }
}
