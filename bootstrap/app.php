<?php

use App\Base\System\Events\Dto\SystemLevelErrorOccurredDto;
use App\Base\System\Events\SystemLevelErrorOccurred;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath : dirname(__DIR__))
    ->withRouting(
        web : __DIR__.'/../routes/web.php',
        api : __DIR__.'/../routes/api.php',
        commands : __DIR__.'/../routes/console.php',
        health : '/up',
    )
    ->withProviders([
        App\Providers\EventServiceProvider::class,
        App\Providers\RepositoryServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (Throwable $throwable) {
            SystemLevelErrorOccurred::dispatch(SystemLevelErrorOccurredDto::fromException($throwable));
        });
    })->create();
