<?php

namespace App\Exceptions;

interface IReportable
{
    /**
     * Report the exception.
     */
    public function report(): void;
}