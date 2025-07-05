<?php

declare(strict_types=1);


if (! function_exists('my_logger')) {
    function my_logger() : App\Support\MyLog
    {
        return app(App\Support\MyLog::class);
    }
}