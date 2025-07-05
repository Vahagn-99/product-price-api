<?php

declare(strict_types=1);


namespace App\Support;

use Log;
use Psr\Log\LoggerInterface;

class MyLog
{
    /**
     * Название файла для логов
     *
     * @param string $file
     * @param bool $group_by_date
     * @return \Psr\Log\LoggerInterface
     */
    public function file(string $file, bool $group_by_date = true) : LoggerInterface
    {
        if ($group_by_date) {
            $file .= '_'.date('Y-m-d');
        }

        return Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/'.$file.'.log'),
        ]);
    }
}
