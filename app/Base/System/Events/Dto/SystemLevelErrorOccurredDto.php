<?php

declare(strict_types=1);


namespace App\Base\System\Events\Dto;

use Spatie\LaravelData\Data;
use Throwable;

class SystemLevelErrorOccurredDto extends Data
{
    /**
     * Constructor SystemLevelErrorOccurredDto
     *
     * @param string $event_dispatched_at
     * @param string $event_dispatched_by
     * @param string $error_message
     * @param string $error_file
     * @param string $error_type
     * @param int $error_code
     * @param string $error_trace
     * @param int $error_line
     */
    public function __construct(
        public string $event_dispatched_at,
        public string $event_dispatched_by,
        public string $error_message,
        public string $error_file,
        public string $error_type,
        public int $error_code,
        public string $error_trace,
        public int $error_line
    ) {
        //
    }

    /**
     *  Создание объекта из объекта исключения
     *
     * @param Throwable $param
     * @return SystemLevelErrorOccurredDto
     */
    public static function fromException(Throwable $param) : SystemLevelErrorOccurredDto
    {
        return self::from([
            'event_dispatched_at' => now()->toDateTimeString(),
            'event_dispatched_by' => $param->getTraceAsString(),
            'error_message' => $param->getMessage(),
            'error_file' => $param->getFile(),
            'error_type' => get_class($param),
            'error_code' => $param->getCode(),
            'error_trace' => $param->getTraceAsString(),
            'error_line' => $param->getLine(),
        ]);
    }
}
